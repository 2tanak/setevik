<?php

namespace App\Services\Sib;

use App\ExpectedWallet;
use App\User;
use App\Models\Package;
use App\Models\BonusBinaryPoints as Points;
use App\Models\Trees\BinaryTreeNode as Node;

class BonusBinaryService
{
    protected $binaryTreeService;

    public function __construct(BinaryTreeService $binaryTreeService)
    {
        $this->binaryTreeService = $binaryTreeService;
    }

    /**
     * Enrolling
     *
     * @param User $user
     */
    public function activate(User $user)
    {
        $initiator      = $user;
        $initiatorNode  = Node::findOrFail($initiator->tree_node_id);
        $reward         = $initiator->package->getBinaryReward();

        if ($reward->amount > 0) {

            $rootNodeIdPrev = null;
            $teamIdPrev     = $initiatorNode->team_id;
            $level          = 1;
            foreach ($this->binaryTreeService->getChainNode($initiatorNode->id) as $parentNode) {

                if (is_null($rootNodeIdPrev)) {
                    $rootNodeIdPrev = $parentNode->root_node_id;
                }

                $earnedMoney    = 0;

                $parent         = $parentNode->user;
                $package        = $parent->package;
                $nodeInfo       = $parentNode->info;
                $parentPts      = $package->getBinaryReward()->amount;

                $pts            = $reward->amount;
                $ptsCut         = 0;

                // cutting
                if (in_array($package->id, [Package::BASIC, Package::STANDARD])) {
                    if ($pts > $parentPts) {

                        $ptsCut = $pts - $parentPts;

                        if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                            $nodeInfo->increment('pts_missed_left', $ptsCut);
                        } else {
                            $nodeInfo->increment('pts_missed_right', $ptsCut);
                        }

                        $pts = $parentPts;
                    }
                }

                //
                if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                    $nodeInfo->increment('pts_left', $pts);
                } else {
                    $nodeInfo->increment('pts_right', $pts);
                }

                //
                if ($nodeInfo->pts_left > $nodeInfo->pts_right) {
                    $nodeInfo->decrement('pts_left', $nodeInfo->pts_right);
                    $earnedMoney = $nodeInfo->pts_right;
                    $nodeInfo->pts_right = 0;
                } else {
                    $nodeInfo->decrement('pts_right', $nodeInfo->pts_left);
                    $earnedMoney = $nodeInfo->pts_left;
                    $nodeInfo->pts_left = 0;
                }
                $nodeInfo->save();

                // levels
                if ($rootNodeIdPrev != $parentNode->root_node_id) {
                    $level++;
                }

                $rootNodeIdPrev = $parentNode->root_node_id;
                $teamIdPrev = $parentNode->team_id;

                Points::create([
                    'from_user_id'  => $initiator->id,
                    'to_user_id'    => $parent->id,
                    'from_node_id'  => $initiatorNode->id,
                    'to_node_id'    => $parentNode->id,
                    'pts'           => $reward->amount,
                    'pts_real'      => $pts,
                    'pts_cut'       => $ptsCut,
                    'level'         => $level,
                    'team_id'       => $teamIdPrev,
                ]);

                //Пишем в кошелек данные по пользователю, которого активируем
                // Проверяем - нету ли у нас такой проводки чтобы не дублировать запись
                $orders_checker = ExpectedWallet::query()
                    ->where('user_id', '=', $parent->id)
                    ->where('bonus_id', '=', 5)
                    ->where('red_bonus_expected', '=', $ptsCut)
                    ->where('expected', '=', $pts)
                    ->where('upd_expected', '=', $pts)
                    ->where('status', '=', 0)
                    ->where('customer_id', '=', $initiator->id)
                    ->where('level', '=', $level)
                    ->where('type', '=', 1)
                    ->get();

                // если в нашем кошельке еще нету такой проводки - плюсуем бонус
                if ($orders_checker->count() == 0) {
                    //Получаем значение активированного пакета
                    $user_checker = User::findOrFail($initiator->id)->first();
                    //$user_package = $user_checker->package_id;
                    $user_package = $initiator->package->id;

                    ExpectedWallet::create([
                        'user_id' => $parent->id,
                        'bonus_id' => 5,
                        'red_bonus_expected' => $ptsCut,
                        'expected' => $pts,
                        'upd_expected' => $pts,
                        'status' => 0,
                        'customer_id' => $initiator->id,
                        'product_id' => $user_package,
                        'level' => $level,
                        'type' => 1,
                    ]);
                }
            }
        }
    }

    /**
     * Upgrading
     *
     * @param User $user
     */
    public function upgrade(User $user)
    {
        $initiator      = $user;
        $initiatorNode  = Node::findOrFail($initiator->tree_node_id);
        $reward         = $initiator->package->getBinaryReward();
        $rewardHistory  = Points::where('from_node_id', $initiatorNode->id)->get();

        if ($reward->amount > 0) {

            $rootNodeIdPrev = null;
            $teamIdPrev     = $initiatorNode->team_id;
            $level          = 1;
            foreach ($this->binaryTreeService->getChainNode($initiatorNode->id) as $parentNode) {

                if (is_null($rootNodeIdPrev)) {
                    $rootNodeIdPrev = $parentNode->root_node_id;
                }

                $earnedMoney    = 0;

                $parent         = $parentNode->user;
                $package        = $parent->package;
                $nodeInfo       = $parentNode->info;
                $parentPts      = $package->getBinaryReward()->amount;

                $pts            = $reward->amount;
                $ptsCut         = 0;

                // search already use points
                foreach ($rewardHistory as $item) {
                    if ($item->to_node_id == $parentNode->id) {
                        $pts    -= $item->pts_real;
                        $ptsCut += $item->pts_cut;
                    }
                }

                // prepared point's amount
                $pts -= $ptsCut;

                // cutting
                if (in_array($package->id, [Package::BASIC, Package::STANDARD])) {

                    //
                    if ($initiator->getOriginal('package_id') == Package::BASIC) {
                        $pts -= $parentPts;

                        if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                            $nodeInfo->increment('pts_missed_left', $pts);
                        } else {
                            $nodeInfo->increment('pts_missed_right', $pts);
                        }

                        $ptsCut = $pts;
                        $pts = $parentPts;

                    } else {
                        if ($pts > $parentPts) {

                            if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                                $nodeInfo->increment('pts_missed_left', $pts);
                            } else {
                                $nodeInfo->increment('pts_missed_right', $pts);
                            }

                            $ptsCut = $pts;
                            $pts = 0;
                        }
                    }
                } else {
                    $ptsCut = 0;
                }

                // if there is points to enrol
                if ($pts) {
                    //
                    if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                        $nodeInfo->increment('pts_left', $pts);
                    } else {
                        $nodeInfo->increment('pts_right', $pts);
                    }

                    //
                    if ($nodeInfo->pts_left > $nodeInfo->pts_right) {
                        $nodeInfo->decrement('pts_left', $nodeInfo->pts_right);
                        $earnedMoney = $nodeInfo->pts_right;
                        $nodeInfo->pts_right = 0;
                    } else {
                        $nodeInfo->decrement('pts_right', $nodeInfo->pts_left);
                        $earnedMoney = $nodeInfo->pts_left;
                        $nodeInfo->pts_left = 0;
                    }
                    $nodeInfo->save();

                    // levels
                    if ($rootNodeIdPrev != $parentNode->root_node_id) {
                        $level++;
                    }
                }

                $rootNodeIdPrev = $parentNode->root_node_id;
                $teamIdPrev = $parentNode->team_id;

                Points::create([
                    'from_user_id'  => $initiator->id,
                    'to_user_id'    => $parent->id,
                    'from_node_id'  => $initiatorNode->id,
                    'to_node_id'    => $parentNode->id,
                    'pts'           => $reward->amount,
                    'pts_real'      => $pts,
                    'pts_cut'       => $ptsCut,
                    'level'         => $level,
                    'team_id'       => $teamIdPrev,
                ]);

                //Пишем в кошелек данные по пользователю, которого активируем
                // Проверяем - нету ли у нас такой проводки чтобы не дублировать запись
                $orders_checker = ExpectedWallet::query()
                    ->where('user_id', '=', $parent->id)
                    ->where('bonus_id', '=', 5)
                    ->where('red_bonus_expected', '=', $ptsCut)
                    ->where('expected', '=', $pts)
                    ->where('upd_expected', '=', $pts)
                    ->where('status', '=', 0)
                    ->where('customer_id', '=', $initiator->id)
                    ->where('level', '=', $level)
                    ->where('type', '=', 1)
                    ->get();

                // если в нашем кошельке еще нету такой проводки - плюсуем бонус
                if ($orders_checker->count() == 0) {
                    //Получаем значение активированного пакета
                    $user_checker = User::findOrFail($initiator->id)->first();
                    //$user_package = $user_checker->package_id;
                    $user_package = $initiator->package->id;

                    ExpectedWallet::create([
                        'user_id' => $parent->id,
                        'bonus_id' => 5,
                        'red_bonus_expected' => $ptsCut,
                        'expected' => $pts,
                        'upd_expected' => $pts,
                        'status' => 0,
                        'customer_id' => $initiator->id,
                        'product_id' => $user_package,
                        'level' => $level,
                        'type' => 1,
                    ]);
                }
            }
        }
    }

    /**
     * Refunding
     *
     * @param User $user
     */
    public function deactivate(User $user)
    {
        $initiator      = $user;
        $initiatorNode  = Node::findOrFail($initiator->tree_node_id);
        $rewardHistory  = Points::where('from_node_id', $initiatorNode->id)->get();

        $rootNodeIdPrev = null;
        $teamIdPrev     = $initiatorNode->team_id;
        $level          = 1;

        foreach ($this->binaryTreeService->getChainNode($initiatorNode->id) as $parentNode) {

            if (is_null($rootNodeIdPrev)) {
                $rootNodeIdPrev = $parentNode->root_node_id;
            }

            $nodeInfo       = $parentNode->info;
            $pts            = 0;
            $ptsCut         = 0;

            // search already use points
            foreach ($rewardHistory as $item) {
                if ($item->to_node_id == $parentNode->id) {
                    $pts    += $item->pts_real;
                    $ptsCut += $item->pts_cut;
                    $item->update(['is_refunded' => true]);
                }
            }

            //
            if ($ptsCut > 0) {
                if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                    $nodeInfo->decrement('pts_missed_left', $ptsCut);
                } else {
                    $nodeInfo->decrement('pts_missed_right', $ptsCut);
                }
            }

            //
            if ($pts > 0) {
                if ($teamIdPrev == BinaryTreeService::TEAM_LEFT) {
                    if ($nodeInfo->pts_left == 0) {
                        $nodeInfo->increment('pts_right', $pts);
                        $pts_upd = $pts;
                    } elseif ($nodeInfo->pts_left > $pts) {
                        $nodeInfo->decrement('pts_left', $pts);
                        $pts_upd = $pts;
                    } else {
                        $nodeInfo->increment('pts_right', $pts - $nodeInfo->pts_left);
                        $nodeInfo->pts_left = 0;
                        $pts_upd = $pts - $nodeInfo->pts_left;
                    }
                } else {
                    if ($nodeInfo->pts_right == 0) {
                        $nodeInfo->increment('pts_left', $pts);
                        $pts_upd = $pts;
                    } elseif ($nodeInfo->pts_right > $pts) {
                        $nodeInfo->decrement('pts_right', $pts);
                        $pts_upd = $pts;
                    } else {
                        $nodeInfo->increment('pts_left', $pts - $nodeInfo->pts_right);
                        $pts_upd = $pts - $nodeInfo->pts_right;
                        $nodeInfo->pts_right = 0;
                    }
                }
            }
            $nodeInfo->save();



            $rootNodeIdPrev = $parentNode->root_node_id;
            $teamIdPrev = $parentNode->team_id;

            // Тест записи в кошелек при деактивации
            // Если сумма возврата > 0
            if($pts > 0){
                //Получаем айдишку юзера которому заводим минусовую проводку
                $user_data = User::where('tree_node_id', '=', $nodeInfo->id)->first();
                if(!empty($user_data)){
                    $user_reward_id = $user_data->id;
                }
                else{
                    $user_reward_id = '1';
                }
                if(isset($ptsCut) && !empty($ptsCut)){
                    $missed = $ptsCut;
                }
                else{
                    $missed = 0;
                }

                    //перевод в минуса
                    if($missed > 0){
                        $up_missed = -$missed;
                    }
                    else{
                        $up_missed = $missed;
                    }

                    if($pts_upd > 0){
                        $up_pts_upd = -$pts_upd;
                    }
                    else{
                        $up_pts_upd = $pts_upd;
                    }

                    // Проверяем - нету ли у нас такой проводки чтобы не дублировать запись
                    $orders_checker = ExpectedWallet::query()
                        ->where('user_id', '=', $user_reward_id)
                        ->where('bonus_id', '=', 5)
                        ->where('status', '=', 0)
                        ->where('customer_id', '=', $initiator->id)
                        ->where('type', '=', 2)
                        ->get();

                    // если в нашем кошельке еще нету такой проводки - плюсуем бонус
                    if ($orders_checker->count() == 0) {
                        //Работает - но пропускает первого пользователя
//                        if($up_missed > 0 || $up_missed < 0  && $up_pts_upd > 0 || $up_pts_upd < 0){
//                            ExpectedWallet::create([
//                                'user_id' => $user_reward_id,
//                                'bonus_id' => 5,
//                                'red_bonus_expected' => $up_missed,
//                                'expected' => $up_pts_upd,
//                                'upd_expected' => $up_pts_upd,
//                                'status' => 0,
//                                'customer_id' => $initiator->id,
//                                'product_id' => 0,
//                                'level' => 0,
//                            ]);
//                        }

                        //Работает - но 1 пользователю вместо минуса 0

                            ExpectedWallet::create([
                                'user_id' => $user_reward_id,
                                'bonus_id' => 5,
                                'red_bonus_expected' => $up_missed,
                                'expected' => $up_pts_upd,
                                'upd_expected' => $up_pts_upd,
                                'status' => 0,
                                'customer_id' => $initiator->id,
                                'product_id' => 0,
                                'level' => 0,
                                'type' => 2, //rewards operation
                            ]);
                    }


                // levels
                if ($rootNodeIdPrev != $parentNode->root_node_id) {
                    $level++;
                }
            }
        }
    }
}