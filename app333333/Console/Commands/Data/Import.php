<?php

namespace App\Console\Commands\Data;

use App\User;
use App\Role;
use App\Models\Package;
use App\Models\Product;
use App\Models\Journal;
use App\Models\Country;
use App\Models\Cabinet;
use App\Models\EventType;
use App\Models\Requisition;
use App\Models\Registration;
use App\Models\BePartnerRequest;
use App\Models\Trees\BinaryTreeNode;

use App\Services\Oss\TreeService;
use App\Services\Oss\ProductService;
use App\Services\Sib\BinaryTreeService;
use App\Services\Sib\BonusBinaryService;

use App\Events\Requisitions\ConfirmedByOwner;
use App\Events\Requisitions\ConfirmedByCurator;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import {--path=} {--to_step=0} {--to_num=0} {--v}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from file (CSV)';

    protected $_packages;
    protected $_binaryTreeService;
    protected $_bonusBinaryService;

    /**
     * Create a new command instance.
     *
     * @param BinaryTreeService $binaryTreeService
     * @param BonusBinaryService $bonusBinaryService
     * @throws \Exception
     */
    public function __construct(BinaryTreeService $binaryTreeService, BonusBinaryService $bonusBinaryService)
    {
        parent::__construct();

        if (app()->environment(['local', 'testing', 'staging']) == false) {
            throw new \Exception('This command could not be run on production');
        }

        $this->_binaryTreeService = $binaryTreeService;
        $this->_bonusBinaryService = $bonusBinaryService;
    }

    public function info($string, $verbosity = null)
    {
        if ($this->option('v')) {
            parent::info($string, $verbosity);
        }
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->callSilent('log:clear');
        $this->callSilent('data:reset');

        //
        if (Schema::hasTable('packages')) {
            $this->_packages = Package::all();
        }

        if ($this->option('path')) {
            $data = $this->getData($this->option('path'));
        } else {
            $data = $this->getData(sprintf('%s/app/storage/import/import.csv', storage_path()));
        }

        $bar = $this->output->createProgressBar(count($data));

        for ($i = 0; $i < count($data); $i++) {
            if ($i < 2) continue;
            $row = $this->getObjectForRow($data[$i]);

            //
            if ($this->option('to_step') > 0 && $i > $this->option('to_step')) {
                break;
            } elseif ($this->option('to_num') > 0 && $this->option('to_num') + 1 == $row->num) {
                break;
            }

            //
            $bar->advance();

            //
            if (preg_match('/Запрос/', $row->action) ||
                preg_match('/Подтверждение/', $row->action) ||
                preg_match('/Начисление/', $row->action)) {
                continue;
            }

            //
            $this->setDateTestNow($row->date->format('Y-m-d H:i:s'));
            $this->checkResidentsActivities();

            //
            switch (trim($row->action)) {
                case 'Регистрация':
                    $this->actionRegister($row);
                    break;
                case 'Активация пакета [Basic]':
                    $this->actionActivatePartner($row, 'basic');
                    break;
                case 'Активация пакета [Standard]':
                    $this->actionActivatePartner($row, 'standard');
                    break;
                case 'Активация пакета [Standart]':
                    $this->actionActivatePartner($row, 'standard');
                    break;
                case 'Активация пакета [Premium]':
                    $this->actionActivatePartner($row, 'premium');
                    break;
                case 'Активация пакета [VIP]':
                    $this->actionActivatePartner($row, 'vip');
                    break;
                case 'Апгрейд пакета [Standart]':
                    $this->actionUpgradePartner($row, 'standard');
                    break;
                case 'Апгрейд пакета [Premium]':
                    $this->actionUpgradePartner($row, 'premium');
                    break;
                case 'Апгрейд пакета [VIP]':
                    $this->actionUpgradePartner($row, 'vip');
                    break;
                case 'Деактивация пакета':
                    $this->deactivate($row);
                    break;
                case 'Активация':
                    $this->actionActivateResident($row, 'vip');
                    break;
                case 'Продление':
                    $this->prolongation($row);
                    break;
                default:
                    throw new \Exception(sprintf('Action [%s] type not defined', $row->action));
                    break;
            }
        }

        $bar->finish();
        echo "\n";
    }

    /**
     * Register User
     *
     * @param object $row
     * @throws \Exception
     */
    protected function actionRegister($row)
    {
        try {
            $user           = User::where('login', $row->login)->first();
            $inviter        = User::where('login', $row->inviter)->firstOrFail();
            $parent         = User::where('login', $row->parent)->firstOrFail();
            $inviterNode    = BinaryTreeNode::find($inviter->tree_node_id);
            $parentNode     = BinaryTreeNode::where('user_id', $parent->id)->orderBy('id')->first();

            //
            if ($row->team == 'L') {
                if (is_null($parentNode)) {
                    $parentNodeLeft = BinaryTreeNode::where('parent_id', $parent->tree_node_id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeLeft->id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                } else {
                    $parentNodeLeft = $parentNode->getTriangleLeftNode();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeLeft->id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                }
            } elseif ($row->team == 'R') {
                if (is_null($parentNode)) {
                    $parentNodeRight = BinaryTreeNode::where('parent_id', $parent->tree_node_id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeRight->id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                } else {
                    $parentNodeRight = $parentNode->getTriangleRightNode();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeRight->id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                }
            } else {
                throw new \Exception(sprintf('Team not found [%s]', $row->login));
            }

            //
            if ($user) {
                $this->info(sprintf(' register (repeat): %s', $row->login));

                //
                $user->update([
                    'tree_node_id'          => $node->id,
                    'tree_inviter_node_id'  => $inviterNode->root_node_id,
                ]);

                //
                $this->_binaryTreeService->generateNodeGroupWithoutUser($node);

                //
                $link           = $this->_binaryTreeService->registerRefLink($user->id, $node->id);
                $registration   = Registration::create(['user_id' => $user->id, 'link_id' => $link->id]);

            } else {
                $this->info(sprintf(' register: %s', $row->login));

                //
                $user = User::create([
                    'name'                  => $row->name,
                    'last_name'             => $row->last_name,
                    'login'                 => $row->login,
                    'email'                 => $row->login,
                    'password'              => bcrypt(env('TEST_PASSWORD', '#zrfs-0dfa-1Ggop-@vCpr')),
                    'phone'                 => $row->phone,
                    'photo'                 => '/img/avatars/no-photo.png',
                    'city'                  => $row->city,
                    //'birthday'              => ($row->birthday) ? $row->birthday->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                    'is_active'             => false,
                    'is_qualified'          => false,
                    'has_activity_sib'      => true, // mark manually
                    'has_activity_oss'      => false,
                    'tree_node_id'          => $node->id,
                    'tree_inviter_node_id'  => $inviterNode->root_node_id,
                ]);

                //
                if ($row->birthday) {
                    $user->birthday = $row->birthday->format('Y-m-d');
                }

                //
                $this->_binaryTreeService->generateNodeGroupWithoutUser($node);

                //
                $link           = $this->_binaryTreeService->registerRefLink($user->id, $node->id);
                $registration   = Registration::create(['user_id' => $user->id, 'link_id' => $link->id]);

                $cabinet = Cabinet::where('code', 'sib')->first();
                $country = Country::where('name', 'Казахстан')->first();
                $roles = Role::whereIn('slug', ['partner-na'])->get();

                $user->roles()->sync($roles);
                $user->country()->associate($country);
                $user->cabinet()->associate($cabinet);
                $user->sib_registered_at = Carbon::now();
                $user->save();

                event(new Registered($user));
            }
        } catch (ModelNotFoundException $e) {
            $this->error(sprintf(' %s [%s]', $e->getMessage(), $row->num));
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Activate a partner
     *
     * @param object $row
     * @param string $package
     * @return void
     * @throws \Exception
     */
    protected function actionActivatePartner($row, $package)
    {
        $this->info(sprintf(' activate: %s', $row->login));

        try {
            $user = User::where('login', $row->login)->firstOrFail();

            if (strlen($row->note)) {
                if ($row->note != 'с OSS') {
                    throw new \Exception(sprintf('note [с OSS] [%s]', $row->num));
                } else {
                    if (!$user) {
                        throw new \Exception(sprintf('Не найден пользователь OSS [%s]', $row->num));
                    }
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->actionRegister($row);
            $user = User::where('login', $row->login)->firstOrFail();
        } catch (\Exception $e) {
            throw $e;
        }

        $activationPackage = $this->_packages->first(function ($item) use ($package) {
            return $item->code == $package;
        });

        // check double activation
        if ($user->cabinet_id == Cabinet::SIB && $user->activated_at) {

            /**
             * Двое нижеперечисленных юзера 'shaltaeva.b@gmail.com', 'a_ermek@mail.ru'
             * находятся в исключениях по проверке двойной активации, потому что начисленные по их регистрациям
             * и активациям деньги уже были выведены у их кураторов
             */
            if (in_array($user->login, ['shaltaeva.b@gmail.com', 'a_ermek@mail.ru'])) {
                $user->package_id = $activationPackage->id;
                Journal::create([
                    'event_type_id' => EventType::where('slug', 'partner-activated')->firstOrFail()->id,
                    'data'          => $user,
                ]);
                return;
            } else {
                throw new \Exception(sprintf(' Двойная активация пакета [%s]', $row->num));
            }
        }

        // OSS -> SIB
        if ($user->cabinet_id == Cabinet::OSS) {
            $inviter        = User::where('login', $row->inviter)->firstOrFail();
            $parent         = User::where('login', $row->parent)->firstOrFail();
            $inviterNode    = BinaryTreeNode::find($inviter->tree_node_id);
            $parentNode     = BinaryTreeNode::where('user_id', $parent->id)->orderBy('id')->first();

            //
            if ($row->team == 'L') {
                if (is_null($parentNode)) {
                    $parentNodeLeft = BinaryTreeNode::where('parent_id', $parent->tree_node_id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeLeft->id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                } else {
                    $parentNodeLeft = $parentNode->getTriangleLeftNode();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeLeft->id)
                        ->where('team_id', BinaryTreeService::TEAM_LEFT)
                        ->first();
                }
            } elseif ($row->team == 'R') {
                if (is_null($parentNode)) {
                    $parentNodeRight = BinaryTreeNode::where('parent_id', $parent->tree_node_id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeRight->id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                } else {
                    $parentNodeRight = $parentNode->getTriangleRightNode();
                    $node = BinaryTreeNode::where('parent_id', $parentNodeRight->id)
                        ->where('team_id', BinaryTreeService::TEAM_RIGHT)
                        ->first();
                }
            } else {
                throw new \Exception(sprintf('Team not found [%s]', $row->login));
            }

            $user->tree_node_id         = $node->id;
            $user->tree_inviter_node_id = $inviterNode->root_node_id;

            $user->save();

            //
            $bePartnerRequest = BePartnerRequest::create([
                'user_id'       => $user->id,
                'curator_id'    => $inviter->id,
                'package_id'    => $activationPackage->id,
                'link'          => 'import',
            ]);
            $bePartnerRequest->is_confirmed = true;
            $bePartnerRequest->save();

        } else {
            $user->is_active = true;
            $user->package_id = $activationPackage->id;
            $user->save();
        }
    }

    /**
     *
     *
     * @param object $row
     * @param string $package
     */
    protected function actionUpgradePartner($row, $package)
    {
        $this->info(sprintf(' upgrade: %s', $row->login));

        $user = User::where('login', $row->login)->firstOrFail();
        $upgradingPackage = $this->_packages->first(function ($item) use ($package) {
            return $item->code == $package;
        });

        $user->package_id = $upgradingPackage->id;
        $user->save();
    }

    /**
     *
     *
     * @param object $row
     */
    protected function deactivate($row)
    {
        $this->info(sprintf(' deactivate: %s', $row->login));

        $user = User::where('login', $row->login)->firstOrFail();
        $user->is_active = false;
        $user->save();
    }

    /**
     *
     *
     * @param object $row
     * @throws \Exception
     */
    protected function actionActivateResident($row)
    {
        $this->info(sprintf(' activate (oss): %s', $row->login));

        try {
            $inviter    = User::where('login', $row->inviter)->firstOrFail();
            $product    = Product::where('name', 'WakeUpERA')->first();
            $link       = (new ProductService())->registerRefLink($product->id, $inviter->id);
            $user       = User::where('login', $row->login)->first();

            if ($user) {
                $user->is_oss_ever = true;
                $user->save();
            } else {
                $user = User::create([
                    'name'                  => $row->name,
                    'last_name'             => $row->last_name,
                    'login'                 => $row->login,
                    'email'                 => $row->login,
                    'password'              => bcrypt(env('TEST_PASSWORD', '#zrfs-0dfa-1Ggop-@vCpr')),
                    'phone'                 => $row->phone,
                    'photo'                 => '/img/avatars/no-photo.png',
                    'city'                  => $row->city,
                    //'birthday'              => ($row->birthday) ? $row->birthday->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                    'is_active'             => false,
                    'has_activity_oss'      => true,
                ]);

                //
                if ($row->birthday) {
                    $user->birthday = $row->birthday->format('Y-m-d');
                }

                $cabinet = Cabinet::where('code', 'oss')->first();
                $country = Country::where('name', 'Казахстан')->first();
                $roles = Role::whereIn('slug', ['resident'])->get();

                $user->roles()->sync($roles);
                $user->country()->associate($country);
                $user->cabinet()->associate($cabinet);
                $user->save();

                event(new Registered($user));
            }

            Registration::create([
                'user_id' => $user->id,
                'link_id' => $link->id,
            ]);

            $requisition = Requisition::create([
                'user_id'               => $user->id,
                'curator_id'            => $inviter->id,
                'product_id'            => $product->id,
                'requisition_type_id'   => $user->isResident() ? 1 : 4,
            ]);

            event(new ConfirmedByOwner($requisition));
            event(new ConfirmedByCurator($requisition));

            if (!$user->oss_registered_at) {
                $user->oss_registered_at = Carbon::now();
            }

            if (!$user->oss_activated_at) {
                $user->oss_activated_at = Carbon::now();
            }

            $user->save();

            $requisition->is_confirmed = true;
            $requisition->save();

        } catch (ModelNotFoundException $e) {
            $this->error(sprintf(' %s [%s]', $e->getMessage(), $row->num));
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     *
     * @param $row
     * @throws \Exception
     */
    protected function prolongation($row)
    {
        $this->info(sprintf(' prolongate (oss): %s', $row->login));

        try {
            $ossTreeService = new TreeService();

            $user               = User::where('login', $row->login)->first();
            $inviter            = $ossTreeService->getActiveCurator($user);
            $product            = Product::where('name', 'WakeUpERA')->first();
            $requisitionTypeId  = null;

            // check requisition type
            if ($user->is_active == false) {
                throw new \Exception('Данный юзер неактивный');
            } else {
                if ((new ProductService())->hasSubscription($product, $user)) {
                    $requisitionTypeId = 2;
                } else {
                    $requisitionTypeId = 3;
                }
            }

            $requisition = Requisition::create([
                'user_id'               => $user->id,
                'curator_id'            => $inviter->id,
                'product_id'            => $product->id,
                'requisition_type_id'   => $requisitionTypeId,
            ]);

            event(new ConfirmedByOwner($requisition));
            event(new ConfirmedByCurator($requisition));

            $requisition->is_confirmed = true;
            $requisition->save();

        } catch (ModelNotFoundException $e) {
            $this->error(sprintf(' %s [%s]', $e->getMessage(), $row->num));
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Objectify row
     *
     * @param array $row
     * @return object
     * @throws \Exception
     */
    protected function getObjectForRow($row)
    {
        $arr = [];
        $cols   = [
            'num'           => 0,   // #
            'date'          => 1,   // Дата
            'system'        => 2,   // Система
            'action'        => 3,   // Действие
            'register_by'   => 4,   // Вход в систему
            'login'         => 5,   // Логин
            'name'          => 6,   // Имя
            'last_name'     => 7,   // Фамилия
            'inviter'       => 8,   // Инвайтер
            'parent'        => 9,   // Родитель (SIB)
            'team'          => 10,  // Команда (SIB)
            'birthday'      => 11,  // Дата рождения
            'country'       => 12,  // Страна
            'city'          => 13,  // Город
            'phone'         => 14,  // Мобильный
            'note'          => 15,  // Заметки
        ];

        foreach ($cols as $k => $v) {
            $arr[$k] = trim($row[$v]);
        }

        $result = (object) $arr;

        // format login
        if (preg_match('/useross\d+/', $result->login)) {
            $result->login = sprintf('%s@example.com', $result->login);
        }

        // format inviter
        if (preg_match('/useross\d+/', $result->inviter)) {
            $result->inviter = sprintf('%s@example.com', $result->inviter);
        }

        // format parent
        if (preg_match('/useross\d+/', $result->parent)) {
            $result->parent = sprintf('%s@example.com', $result->parent);
        }

        // format application time
        $result->date = Carbon::createFromTimeString($result->date);
        if ($result->date->hour == 0) {
            $result->date->addHours(18);
        }

        // format birthday
        if ($result->birthday) {
            if (preg_match('/\d+\.\d+\.\d+/', $result->birthday)) {
                $result->birthday = Carbon::createFromFormat('d.m.Y', $result->birthday);
            } elseif (preg_match('/\d+-\d+-\d+/', $result->birthday)) {
                $result->birthday = Carbon::createFromFormat('Y-m-d', $result->birthday);
            } elseif (preg_match('/\d+\/\d+\/\d+/', $result->birthday)) {
                $result->birthday = Carbon::createFromFormat('m/d/y', $result->birthday);
            } else {
                throw new \Exception(sprintf('Undefined datetime format [%s]', $result->birthday));
            }
        }

        return $result;
    }

    /**
     * Read data from file
     *
     * @param $path
     * @return array
     */
    protected function getData($path)
    {
        $data = [];

        if ($handle = fopen($path, 'r')) {
            while (($item = fgetcsv($handle, 1000, ',')) !== false) {
                $arr = [];
                $num = count($item);
                for ($i = 0; $i < $num; $i++) {
                    $arr[] = $item[$i];
                }
                $data[] = $arr;
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Check residents activity
     *
     *
     */
    protected function checkResidentsActivities()
    {
        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $subscriptions = $user->subscriptions()
                ->where('started_at', '<=', Carbon::now())
                ->where('expired_at', '>', Carbon::now());

            if ((int) $subscriptions->count() == 0) {
                $user->update(['has_activity_oss' => false]);
            } else {
                $user->update(['has_activity_oss' => true]);
            }
        }
    }

    /**
     * Set application time
     *
     * @param $dateTimeString
     */
    protected function setDateTestNow($dateTimeString)
    {
        Carbon::setTestNow($dateTimeString);
    }
}
