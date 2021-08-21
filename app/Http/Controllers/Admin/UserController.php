<?php

namespace App\Http\Controllers\Admin;

use App\ExpectedWallet;
use App\Models\Badge;
use App\Models\BePartnerRequest;
use App\Models\BonusBinaryPoints;
use App\Models\Quittance;
use App\Models\Requisition;
use App\Models\Status;
use App\Models\BonusBinaryPoints as Points;
use App\Models\Trees\BinaryTreeNode;
use App\Models\Trees\BinaryTreeNode as Node;
use App\Models\Trees\BinaryTreeNodeInfo;
use App\Models\Trees\BinaryTreeTeam as Top_team;
use App\Models\Wallet;
use App\Services\Oss\TreeService;
use App\Models\Trees\OnlineSmartSystemTreeNode as Node_tree;
use App\User;
use App\Models\File;
use App\Models\Country;
use App\Models\Subscription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * @package App\Http\Controllers\Admin
 */
class UserController extends AdminController
{
    public function index()
    {
//        $keyword = Input::get('q');
//
//        if (strlen($keyword)) {
//            $data = User::query()
//                ->with('package')
//                ->with('status')
//                ->with('cabinet')
//                ->where('name', 'LIKE', "%{$keyword}%")
//                ->orWhere('last_name', 'LIKE', "%{$keyword}%")
//                ->orWhere('email', 'LIKE', "%{$keyword}%")
//                ->paginate(20)
//                ->appends(request()->query())
//            ;
//        } else {
//            $data = User::with('package')
//                ->with('status')
//                ->with('cabinet')
//                ->orderBy('id', 'desc')
//                ->paginate(20);
//        }
//
//        return view('admin.users', compact('data'))->with('q', $keyword);

        $data = User::with(['package', 'status', 'cabinet'])->orderByDesc('id')->get();

        return view('admin.users', compact('data'));
    }

    /**
     * @param integer $id - User ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with('cabinet')
            ->with('status')
            ->with('package')
            ->with([
                'requisitions' => function ($query) {
                    $query->orderByDesc('id');
                },
                'requisitions.product',
                'requisitions.curator',
                'requisitions.user',
                'requisitions.requisitionType',
                'requisitions.userQuittance',
                'requisitions.curatorQuittance',
            ])
            ->findOrFail($id);

        $countries = Country::all();
        $statuses = Status::all();

        return view('admin.user')
            ->with('user', $user)
            ->with('countries', $countries)
            ->with('statuses', $statuses);
    }

    public function updateUserData(Request $request, $id)
    {
        $rules = [
            'name'          => 'required|latin|string|max:255',
            'last_name'     => 'required|latin|string|max:255',
            'birthday'      => 'date',
            'phone'         => 'required',
            'country_id'    => 'required|exists:countries,id',
            //'city'          => 'required|latin',
            'city'          => 'required',
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            //'status_id'     => 'exists:statuses,id',
        ];

        if ($request->has('password')) {
            $rules['password'] = 'min:6|confirmed';
        }

        if ($request->has('email')) {
            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        $this->validate($request, $rules);
        $user = User::findOrFail($id);

        \Log::debug(__METHOD__, $request->all());

        // photo
        if ($request->hasFile('photo')) {

            $image      = $request->file('photo');
            $filename   = sprintf('%d_%d.%s', $user->id, time(), $image->getClientOriginalExtension());

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(150, 150);
            $image_resize->save(public_path('storage/avatars/' .$filename));

            $user->photo = sprintf('/storage/avatars/%s', $filename);

            File::create([
                'dir'           => '/storage/avatars/',
                'path'          => '/storage/avatars/' . $filename,
                'size'          => $image->getSize(),
                'mime_type'     => $image->getClientMimeType(),
                'name'          => $filename,
                'original_name' => $image->getClientOriginalName(),
            ]);
        }

        // password
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // email & login
        if ($request->has('email')) {
            $user->login = $request->input('email');
            $user->email = $request->input('email');
        }

        // status
        if ($request->has('status_id')) {
            $user->status_id = $request->input('status_id');
        }

        $user->save();

        $user->update($request->only([
            'name',
            'last_name',
            'birthday',
            'phone',
            'country_id',
            'city',
        ]));

        return $user;
    }

    public function update(Request $request, $id)
    {
        //$user = User::where('id', $id);
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }


    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function delete(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function updatebinary(Request $request, $id, $tek_package, $package, $date, TreeService $tree)
    {
        //Текущий пакет пользователя дял теста 2 - Стандарт
//        $tek_package = '2';

        //Получаем данные пользователя по ID
        $user_data  = User::findOrFail($id);
        $node_id  = $user_data['tree_node_id'];

        //Получаем данные родительского пользователя
        $initiatorNode  = Node::findOrFail($node_id);

        //Получаем сетку пользователей под заданным юзером
        $node = Node_tree::with('user')->where('user_id', $id)->first();
        $tree = $tree->getTree($node);

        //Получаем данные пользоателей и строим сетку над заданным юзером
        $top_team = Top_team::with('node')->where('team_node_id', $node_id)->get();


        //Уровень начисления
        $level = 1;

        foreach($top_team as $item){
            //print_r($item);

            $parent_id = $item['node']['inviter_id'];
            $user_id = $item['node']['user_id'];
            $user_status = $item['node']['is_active'];
            $users = User::query()->where('tree_node_id', '=', $item['node_id'])->get();
            $user_package_data  = User::findOrFail($user_id);
            //Получаем активный пакет и ФИО пользователя
            if(!empty($users)){
                foreach($users as $user_data){
                    if(!empty($user_data['package_id'])){
//                        $user_package = $user_data['package_id'];
                        $user_package = 3;
                        $user_name = $user_data['name'].' '.$user_data['last_name'];
                        //break;
                    }
                    else{
                        $user_package = NULL;
                    }
                }
            }
            $user_package = $user_package_data['package_id'];
            if($user_package == 1){
                $package_name = 'Basic';
            }
            if($user_package == 2){
                $package_name = 'Standart';
            }
            if($user_package == 3){
                $package_name = 'Premium';
            }
            if($user_package == 4){
                $package_name = 'Vip';
            }

            //Получаем пакет по которому начисляем баллы по сетке
//            $package = '1'; //Для теста начисляем баллы за Basic
//            $package = '2'; //Для теста начисляем баллы за Standart
//            $package = '3'; //Для теста начисляем баллы за Premium
//            $package = '4'; //Для теста начисляем баллы за VIP

            //Вычисляем начисление баллов и реальных баллов
            //Переход с пакета BASIC
            if($tek_package == 1){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 600;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 600;
                        $pts_real = 0;
                        $pts_cut = 600;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 600;
                        $pts_real = 100;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 600;
                        $pts_real = 600;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 2000;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 2000;
                        $pts_real = 0;
                        $pts_cut = 2000;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 2000;
                        $pts_real = 100;
                        $pts_cut = 1900;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 2000;
                        $pts_real = 2000;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета STANDART
            if($tek_package == 2){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 500;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 500;
                        $pts_real = 500;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета PREMIUM
            if($tek_package == 3){
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }


            //TEAM ID
            $team_id = $item['node']['team_id'];
            if(empty($side)){
                $side = $team_id;
            }
            if($team_id == 1){
                $team_id_name = 'LEFT';
            }
            if($team_id == 2){
                $team_id_name = 'RIGHT';
            }
            $team_left = $item['node']['last_left_node_id'];
            $team_right = $item['node']['last_right_node_id'];


            //Тестовый вывод основной записи
//            echo 'USER_NAME - '.$user_name."<br>";
//            echo 'FROM USER_ID - '.$user_id."<br>";
//            echo 'PACKAGE_ID - '.$user_package."<br>";
//            echo 'PACKAGE_NAME - '.$package_name."<br>";
//            echo 'FROM BINARY - '.$node_id."<br>";//            echo 'TO BINARY - '.$item['node_id']."<br>";
//            echo 'PARENT_ID - '.$parent_id."<br>";
//            echo 'PTS - '.$pts."<br>";
//            echo 'PTS_REAL - '.$pts_real."<br>";
//            echo 'PTS_CUT - '.$pts_cut."<br>";
//            echo 'LEVEL - '.$level."<br>";
//            echo 'TEAM_ID - '.$team_id."<br>";
//            echo 'IS_REFUNDED - 0 <br>';
//            echo 'TEAM_LEFT - '.$team_left.'<br>';
//            echo 'TEAM_RIGHT - '.$team_right.'<br>';
//            echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
//            echo "<hr>";


            //Проверяем есть ли для начисления инвайтер
//                if(!empty($parent_id) && $parent_id !== $user_id){
            if(!empty($parent_id)){
                //Получаем данные
                $parent_data = BinaryTreeNode::findOrFail($parent_id);

                $parent_arr =  BinaryTreeNode::query()->where('user_id', '=', $user_id)->where('team_id', '=', $team_id)->get();
                foreach($parent_arr as $parent_line){
                    $parent_data_id = $parent_data['user_id'];
                    $parent_team_id = $parent_data['team_id'];

                    if($user_id !== $parent_data_id)
                    {
                        if($parent_team_id == 1){
                            $parent_team_id_name = 'LEFT';
                        }
                        if($parent_team_id == 2){
                            $parent_team_id_name = 'RIGHT';
                        }
                        $parent_user_data = User::findOrFail($parent_data_id);

                        //Готовим данные
                        $parent_user_name = $parent_user_data['name'].' '.$parent_user_data['last_name'];
                        // $parent_user_package = $parent_user_data['package_id'];
                        $parent_user_package = $user_package;

                        //Обработка по пакету
                        if($parent_user_package == 1){
                            $parent_package_name = 'Basic';
                        }
                        if($parent_user_package == 2){
                            $parent_package_name = 'Standart';
                        }
                        if($parent_user_package == 3){
                            $parent_package_name = 'Premium';
                        }
                        if($parent_user_package == 4){
                            $parent_package_name = 'Vip';
                        }

                        //Вычисляем начисление баллов и реальных баллов
                        //С BASIC
                        if($tek_package == 1){
                            if($package == 1){
                                //Перебираем разные варианты начислений баллов пользователям
                                $package_reward = 0;
                            }
                            if($package == 2){
                                $package_reward = 100;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 100;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 100;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 3){
                                $package_reward = 600;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 600;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 600;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 600;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 600;
                                    $parent_pts_real = 600;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 2000;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 2000;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 1900;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 2000;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //При переходе со STANDART
                        if($tek_package == 2){
                            if($package == 3){
                                $package_reward = 500;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 500;
                                    $parent_pts_real = 500;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //С Premium
                        if($tek_package == 3){
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }

                        //Пишем здесь данные пользователя
//                            echo 'USER NAME ----- '.$user_name.'<br>';
//                            echo 'FROM ID ----- '.$id.'<br>';
//                            echo 'PACKAGE ID  ----- '.$parent_user_package.'<br>';
//                            echo 'PACKAGE NAME  ----- '.$parent_package_name.'<br>';
//                            echo 'TO ID ----- '.$user_id.'<br>';
//                            echo 'FROM BINARY ----- '.$node_id.'<br>';
//                            echo 'TO BINARY ----- '.$parent_line['id'].'<br>';
//                            echo 'PARENT_PTS - '.$parent_pts."<br>";
//                            echo 'PARENT_PTS_REAL - '.$parent_pts_real."<br>";
//                            echo 'PARENT_PTS_CUT - '.$parent_pts_cut."<br>";
//                            echo 'PARENT_IS_REFUNDED - 0 <br>';
//                            echo 'TEAM_ID - '.$team_id."<br>";
//                            echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
//                            echo 'PARENT_TEAM_ID - '.$parent_team_id."<br>";
//                            echo 'PARENT_TEAM_ID_NAME - '.$parent_team_id_name.'<br>';

                        //Пишем данные о заявке
						 //Пишем данные о заявке
  	                        if($level == 1){
  	                            $stat_user_team_id = Node::where('user_id', $id)->orderBy('id', 'asc')->first();
  	                            $upd_team_id = $stat_user_team_id->team_id;
  	                            $side = $upd_team_id;
  	                        }
                        $binary_points = new BonusBinaryPoints();
                        $binary_points->from_user_id = $id;
                        $binary_points->to_user_id = $user_id;
                        $binary_points->from_node_id = $node_id;
                        $binary_points->to_node_id = $parent_line['id'];
                        $binary_points->pts = $parent_pts;
                        $binary_points->pts_real = $parent_pts_real;
                        $binary_points->pts_cut = $parent_pts_cut;
                        $binary_points->level = $level;
                        $binary_points->team_id = $team_id;
                        $binary_points->is_refunded = '0';
                        $binary_points->created_at = $date;
                        $binary_points->save();

                        //Обновляем количество бонусов
                        $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $parent_line['id'])->first();
                        $packs_left = $binary_points['packs_left'];
                        $packs_right = $binary_points['packs_right'];
                        $pts_left = $binary_points['pts_left'];
                        $pts_right = $binary_points['pts_right'];
                        $pts_left_old = $binary_points['pts_left'];
                        $pts_right_old = $binary_points['pts_right'];
                        $pts_missed_left = $binary_points['pts_missed_left'];
                        $pts_missed_right = $binary_points['pts_missed_right'];
                        $pts_missed_left_old = $binary_points['pts_missed_left'];
                        $pts_missed_right_old = $binary_points['pts_missed_right'];
                        $pts_wallet_add = 0;
                        $wallet_pts_missed = 0;
                        if($side == 1){
                            //LEFT
                            $pts_left = $pts_left + $parent_pts_real;
                            $pts_missed_left = $pts_missed_left + $parent_pts_cut;

                            //Сливаем стороны
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_left == $pts_right && $pts_left !== 0 && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_left = 0;
                                $pts_right = 0;
                            }
                            //Если слева больше
                            if($pts_left > $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = $pts_left - $pts_right;
                                $pts_right = 0;
                            }
                            //Если справа больше
                            if($pts_left < $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_right = $pts_right - $pts_left;
                                $pts_left = 0;
                            }

                            //Сливаем стороны красных бонусов
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_missed_left == $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }
                            //Если слева больше
                            if($pts_missed_left > $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_right;
                            }
                            //Если справа больше
                            if($pts_missed_left < $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }

                            //Обновляем запись
                            $binary_points->packs_left = $packs_left;
                            $binary_points->pts_left = $pts_left;
                            $binary_points->pts_right = $pts_right;
                            $binary_points->pts_missed_left = $pts_missed_left;
                            $binary_points->save();

                            //Пишем данные в кошелек
                            if($pts_wallet_add > 0 OR $wallet_pts_missed > 0){
                                $wallet_data = [];
                                $wallet_data['user_id'] = $user_id; //ID
                                if(!empty($user_name)){
                                    $wallet_data['user_name'] = $user_name; //Пользователь
                                }
                                if($pts_wallet_add > 0){
                                    $wallet_data['add_money'] = $pts_wallet_add; //Значение для добавления данных в кошелек
                                }
                                if($wallet_pts_missed > 0){
                                    $wallet_data['update_red_pts'] = $wallet_pts_missed; //Значение для обновления данных по красным бонусам
                                }

                                $wallet_data_json = json_encode($wallet_data);

                                //Пишем данные в файл
                                $date = date('Y-m-d');
                                $url = "./wallet/".$date;
                                if(!is_dir($url)) {
                                    mkdir($url, 0775, true);
                                }

                                //Если нету файла - создаем, если есть - дописываем данные
                                if(!empty($user_name))
                                {
                                    $filename = './wallet/'.$date.'/'.$user_id.'.json';
                                    if (file_exists($filename)) {
                                        $data[] = $wallet_data;
                                        $fp = fopen($filename, 'a');
                                        fwrite($fp, json_encode($data));
                                        fclose($fp);
                                    } else {
                                        $data[] = $wallet_data_json;
                                        file_put_contents('./wallet/'.$date.'/'.$user_id.'.json', $data);
                                    }
                                }
                            }
                        }
                        if($side == 2){
                            //RIGHT
                            $pts_right = $pts_right + $parent_pts_real;
                            $pts_missed_right = $pts_missed_right + $parent_pts_cut;

                            //Сливаем стороны
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_left == $pts_right && $pts_left !== 0 && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = 0;
                                $pts_right = 0;
                            }
                            //Если слева больше
                            if($pts_left > $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = $pts_left - $pts_right;
                                $pts_right = 0;
                            }
                            //Если справа больше
                            if($pts_left < $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_right = $pts_right - $pts_left;
                                $pts_left = 0;
                            }

                            //Сливаем стороны красных бонусов
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_missed_left == $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }
                            //Если слева больше
                            if($pts_missed_left > $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_right;
                            }
                            //Если справа больше
                            if($pts_missed_left < $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }

                            //Обновляем запись
                            $binary_points->packs_right = $packs_right;
                            $binary_points->pts_left = $pts_left;
                            $binary_points->pts_right = $pts_right;
                            $binary_points->pts_missed_right = $pts_missed_right;
                            $binary_points->save();

                            //Пишем данные в кошелек
                            if($pts_wallet_add > 0 OR $wallet_pts_missed > 0){
                                $wallet_data = [];
                                $wallet_data['user_id'] = $user_id; //ID
                                if(!empty($user_name)){
                                    $wallet_data['user_name'] = $user_name; //Пользователь
                                }
                                if($pts_wallet_add > 0){
                                    $wallet_data['add_money'] = $pts_wallet_add; //Значение для добавления данных в кошелек
                                }
                                if($wallet_pts_missed > 0){
                                    $wallet_data['update_red_pts'] = $wallet_pts_missed; //Значение для обновления данных по красным бонусам
                                }

                                //Пишем данные в базу по основным бонусам
//                                if(isset($pts_wallet_add) && !empty($pts_wallet_add) && $pts_wallet_add > 0){
//                                    $expected_wallet = new ExpectedWallet();
//                                    $expected_wallet->user_id = $user_id;
//                                    $expected_wallet->bonus_id = 5;
//                                    if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                        $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                                    }
//                                    $expected_wallet->expected = $pts_wallet_add;
//                                    $expected_wallet->upd_expected = $pts_wallet_add;
//                                    $expected_wallet->status = 0;
//                                    $expected_wallet->customer_id = $id;
//                                    $expected_wallet->product_id = $parent_user_package;
//                                    $expected_wallet->created_at = $create_date;
//                                    $expected_wallet->save();
//                                }
//                                else{
//                                    //Пишем данные в базу по красным бонусам
//                                    if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                        $expected_wallet = new ExpectedWallet();
//                                        $expected_wallet->user_id = $user_id;
//                                        $expected_wallet->bonus_id = 5;
//                                        if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                            $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                                        }
//                                        $expected_wallet->expected = 0;
//                                        $expected_wallet->upd_expected = 0;
//                                        $expected_wallet->status = 0;
//                                        $expected_wallet->customer_id = $id;
//                                        $expected_wallet->product_id = $parent_user_package;
//                                        $expected_wallet->created_at = $create_date;
//                                        $expected_wallet->save();
//                                    }
//                                }


                                $wallet_data_json = json_encode($wallet_data);

                                //Пишем данные в файл
                                $date = date('Y-m-d');
                                $url = "./wallet/".$date;
                                if(!is_dir($url)) {
                                    mkdir($url, 0775, true);
                                }
                                //Если нету файла - создаем, если есть - дописываем данные
                                if(!empty($user_name))
                                {
                                    $filename = './wallet/'.$date.'/'.$user_id.'.json';
                                    if (file_exists($filename)) {
                                        $data[] = $wallet_data;
                                        $fp = fopen($filename, 'a');
                                        fwrite($fp, json_encode($data));
                                        fclose($fp);
                                    } else {
                                        $data[] = $wallet_data_json;
                                        file_put_contents('./wallet/'.$date.'/'.$user_id.'.json', $data);
                                    }
                                }
                            }
                        }
                        if($side == 1){
                            $side_name = 'LEFT';
                        }
                        if($side == 2){
                            $side_name = 'RIGHT';
                        }

//                            echo 'WRITE PACKS TO (ID) - '.$side.'<br>';
//                            echo 'WRITE PACKS TO SIDE - '.$side_name.'<br>';
                        if(!empty($user_name)){
                            echo 'ID пользователя ----- '.$user_id.'<br>';
                            echo 'Имя пользователя ----- '.$user_name.'<br>';
                            echo 'Пакет  ----- '.$parent_package_name.'<br>';
                            echo 'Бонусы - '.$parent_pts."<br>";
                            echo 'Начисление - '.$parent_pts_real."<br>";
                            echo 'Заморожено - '.$parent_pts_cut."<br>";
                            echo 'Сторона зачисления - '.$side_name.'<br>';
                            echo 'Левое крыло было - '.$pts_left_old.'<br>';
                            echo 'Левое крыло стало - '.$pts_left.'<br>';
                            echo 'Правое крыло было - '.$pts_right_old.'<br>';
                            echo 'Правое крыло стало - '.$pts_right.'<br>';
                            echo 'Красная зона слева - '.$pts_missed_left.'<br>';
                            echo 'Красная зона справа - '.$pts_missed_right.'<br>';
                            echo 'В кошелек - '.$pts_wallet_add.'<br>';
                            echo 'В кошелек в красную зону - '.$wallet_pts_missed.'<br>';
                        }
                        $user_name = NULL;
                        $parent_data_id = NULL;
                    }
                }
                $side = $team_id;
                echo "<hr>";
            }
            //Пишем данные одинарного пользователя
            else{

                echo 'FROM ID - '.$id."<br>";
                echo 'TO ID - '.$user_id."<br>";
                echo 'USER_NAME - '.$user_name."<br>";
                echo 'PACKAGE_ID - '.$user_package."<br>";
                echo 'PACKAGE_NAME - '.$package_name."<br>";
                echo 'FROM BINARY - '.$node_id."<br>";
                echo 'TO BINARY - '.$item['node_id']."<br>";
                echo 'PARENT_ID - '.$parent_id."<br>";
                echo 'PTS - '.$pts."<br>";
                echo 'PTS_REAL - '.$pts_real."<br>";
                echo 'PTS_CUT - '.$pts_cut."<br>";
                echo 'LEVEL - '.$level."<br>";
                echo 'TEAM_ID - '.$team_id."<br>";
                echo 'IS_REFUNDED - 0 <br>';
                echo 'TEAM_LEFT - '.$team_left.'<br>';
                echo 'TEAM_RIGHT - '.$team_right.'<br>';
                echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
                echo "<hr>";
				
				 //Пишем данные о заявке
  	                        if($level == 1){
  	                            $stat_user_team_id = Node::where('user_id', $id)->orderBy('id', 'asc')->first();
  	                            $upd_team_id = $stat_user_team_id->team_id;
  	                            $side = $upd_team_id;
  	                        }

                //Пишем данные о заявке
                $binary_points = new BonusBinaryPoints();
                $binary_points->from_user_id = $id;
                $binary_points->to_user_id = $user_id;
                $binary_points->from_node_id = $node_id;
                $binary_points->to_node_id = $item['node_id'];
                $binary_points->pts = $pts;
                $binary_points->pts_real = $pts_real;
                $binary_points->pts_cut = $pts_cut;
                $binary_points->level = $level;
                $binary_points->team_id = $team_id;
                $binary_points->is_refunded = '0';
                $binary_points->created_at = $date;
                $binary_points->save();

                //Обновляем количество бонусов
                $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $item['node_id'])->first();
                $packs_left = $binary_points['packs_left'];
                $packs_right = $binary_points['packs_right'];
                $pts_left = $binary_points['pts_left'];
                $pts_right = $binary_points['pts_right'];
                $pts_missed_left = $binary_points['pts_missed_left'];
                $pts_missed_right = $binary_points['pts_missed_right'];

                if($team_id == 1){
                    //LEFT
                    //$packs_left = $packs_left + 1;
                    $packs_left = $packs_left;
                    $pts_left = $pts_left + $pts_real;
                    $pts_missed_left = $pts_missed_left + $pts_cut;

                    //Обновляем запись
                    $binary_points->packs_left = $packs_left;
                    $binary_points->pts_left = $pts_left;
                    $binary_points->pts_missed_left = $pts_missed_left;
                    $binary_points->save();
                }
                if($team_id == 2){
                    //RIGHT
                    //$packs_right = $packs_right + 1;
                    $packs_right = $packs_right;
                    $pts_right = $pts_right + $pts_real;
                    $pts_missed_right = $pts_missed_right + $pts_cut;

                    //Обновляем запись
                    $binary_points->packs_right = $packs_right;
                    $binary_points->pts_right = $pts_right;
                    $binary_points->pts_missed_right = $pts_missed_right;
                    $binary_points->save();
                }
            }

            //Очищаем переменные
//                $user_id = NULL;
//                $user_package = NULL;
//                $package_name = NULL;
//                $team_id = NULL;
//                $pts = NULL;
//                $pts_real = NULL;
//                $pts_cut = NULL;
//                $team_left = NULL;
//                $team_right = NULL;
            $level++;
        }
    }

    public function updatebinaryview(Request $request, $id, $tek_package, $package, $date, TreeService $tree)
    {
        $create_date = $date;
        //Получаем данные пользователя по ID
        $user_data  = User::findOrFail($id);
        $node_id  = $user_data['tree_node_id'];

        //Получаем данные родительского пользователя
        $initiatorNode  = Node::findOrFail($node_id);

        //Получаем сетку пользователей под заданным юзером
        $node = Node_tree::with('user')->where('user_id', $id)->first();
        $tree = $tree->getTree($node);

        //Получаем данные пользоателей и строим сетку над заданным юзером
        $top_team = Top_team::with('node')->where('team_node_id', $node_id)->get();

        //Уровень начисления
        $level = 1;
        foreach($top_team as $item){
            //print_r($item);

            $parent_id = $item['node']['inviter_id'];
            $user_id = $item['node']['user_id'];
            $user_status = $item['node']['is_active'];
            $users = User::query()->where('tree_node_id', '=', $item['node_id'])->get();
            $user_package_data  = User::findOrFail($user_id);
            //Получаем активный пакет и ФИО пользователя
            if(!empty($users)){
                foreach($users as $user_data){
                    if(!empty($user_data['package_id'])){
//                        $user_package = $user_data['package_id'];
                        $user_package = 3;
                        $user_name = $user_data['name'].' '.$user_data['last_name'];
                        //break;
                    }
                    else{
                        $user_package = NULL;
                    }
                }
            }
            $user_package = $user_package_data['package_id'];
            if($user_package == 1){
                $package_name = 'Basic';
            }
            if($user_package == 2){
                $package_name = 'Standart';
            }
            if($user_package == 3){
                $package_name = 'Premium';
            }
            if($user_package == 4){
                $package_name = 'Vip';
            }

            //Вычисляем начисление баллов и реальных баллов
            //Переход с пакета BASIC
            if($tek_package == 1){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 600;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 600;
                        $pts_real = 0;
                        $pts_cut = 600;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 600;
                        $pts_real = 100;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 600;
                        $pts_real = 600;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 2000;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 2000;
                        $pts_real = 0;
                        $pts_cut = 2000;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 2000;
                        $pts_real = 100;
                        $pts_cut = 1900;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 2000;
                        $pts_real = 2000;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета STANDART
            if($tek_package == 2){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 500;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 500;
                        $pts_real = 500;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета PREMIUM
            if($tek_package == 3){
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }


            //TEAM ID
            $team_id = $item['node']['team_id'];
            if(empty($side)){
                $side = $team_id;
            }
            if($team_id == 1){
                $team_id_name = 'LEFT';
            }
            if($team_id == 2){
                $team_id_name = 'RIGHT';
            }
            $team_left = $item['node']['last_left_node_id'];
            $team_right = $item['node']['last_right_node_id'];

            //Проверяем есть ли для начисления инвайтер
//                if(!empty($parent_id) && $parent_id !== $user_id){
            if(!empty($parent_id)){
                //Получаем данные
                $parent_data = BinaryTreeNode::findOrFail($parent_id);

                $parent_arr =  BinaryTreeNode::query()->where('user_id', '=', $user_id)->where('team_id', '=', $team_id)->get();
                foreach($parent_arr as $parent_line){
                    $parent_data_id = $parent_data['user_id'];
                    $parent_team_id = $parent_data['team_id'];

                    if($user_id !== $parent_data_id)
                    {
                        if($parent_team_id == 1){
                            $parent_team_id_name = 'LEFT';
                        }
                        if($parent_team_id == 2){
                            $parent_team_id_name = 'RIGHT';
                        }
                        $parent_user_data = User::findOrFail($parent_data_id);

                        //Готовим данные
                        $parent_user_name = $parent_user_data['name'].' '.$parent_user_data['last_name'];
                        // $parent_user_package = $parent_user_data['package_id'];
                        $parent_user_package = $user_package;

                        //Обработка по пакету
                        if($parent_user_package == 1){
                            $parent_package_name = 'Basic';
                        }
                        if($parent_user_package == 2){
                            $parent_package_name = 'Standart';
                        }
                        if($parent_user_package == 3){
                            $parent_package_name = 'Premium';
                        }
                        if($parent_user_package == 4){
                            $parent_package_name = 'Vip';
                        }

                        //Вычисляем начисление баллов и реальных баллов
                        //С BASIC
                        if($tek_package == 1){
                            if($package == 1){
                                //Перебираем разные варианты начислений баллов пользователям
                                $package_reward = 0;
                            }
                            if($package == 2){
                                $package_reward = 100;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 100;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 100;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 3){
                                $package_reward = 600;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 600;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 600;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 600;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 600;
                                    $parent_pts_real = 600;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 2000;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 2000;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 1900;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 2000;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //При переходе со STANDART
                        if($tek_package == 2){
                            if($package == 3){
                                $package_reward = 500;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 500;
                                    $parent_pts_real = 500;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //С Premium
                        if($tek_package == 3){
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
						
						 //Пишем данные о заявке
  	                        if($level == 1){
  	                            $stat_user_team_id = Node::where('user_id', $id)->orderBy('id', 'asc')->first();
  	                            $upd_team_id = $stat_user_team_id->team_id;
  	                            $side = $upd_team_id;
  	                        }
						
                        //Пишем данные о заявке
                        $binary_points = new BonusBinaryPoints();
                        $binary_points->from_user_id = $id;
                        $binary_points->to_user_id = $user_id;
                        $binary_points->from_node_id = $node_id;
                        $binary_points->to_node_id = $parent_line['id'];
                        $binary_points->pts = $parent_pts;
                        $binary_points->pts_real = $parent_pts_real;
                        $binary_points->pts_cut = $parent_pts_cut;
                        $binary_points->level = $level;
                        $binary_points->team_id = $team_id;
                        $binary_points->is_refunded = '0';
                        $binary_points->created_at = $date;
                        $binary_points->save();

                        //Обновляем количество бонусов
                        $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $parent_line['id'])->first();
                        $packs_left = $binary_points['packs_left'];
                        $packs_right = $binary_points['packs_right'];
                        $pts_left = $binary_points['pts_left'];
                        $pts_right = $binary_points['pts_right'];
                        $pts_left_old = $binary_points['pts_left'];
                        $pts_right_old = $binary_points['pts_right'];
                        $pts_missed_left = $binary_points['pts_missed_left'];
                        $pts_missed_right = $binary_points['pts_missed_right'];
                        $pts_missed_left_old = $binary_points['pts_missed_left'];
                        $pts_missed_right_old = $binary_points['pts_missed_right'];
                        $pts_wallet_add = 0;
                        $wallet_pts_missed = 0;
                        if($side == 1){
                            //LEFT
                            $pts_left = $pts_left + $parent_pts_real;
                            $pts_missed_left = $pts_missed_left + $parent_pts_cut;

                            //Сливаем стороны
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_left == $pts_right && $pts_left !== 0 && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_left = 0;
                                $pts_right = 0;
                            }
                            //Если слева больше
                            if($pts_left > $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = $pts_left - $pts_right;
                                $pts_right = 0;
                            }
                            //Если справа больше
                            if($pts_left < $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_right = $pts_right - $pts_left;
                                $pts_left = 0;
                            }

                            //Сливаем стороны красных бонусов
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_missed_left == $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }
                            //Если слева больше
                            if($pts_missed_left > $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_right;
                            }
                            //Если справа больше
                            if($pts_missed_left < $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }

                            //Обновляем запись
                            $binary_points->packs_left = $packs_left;
                            $binary_points->pts_left = $pts_left;
                            $binary_points->pts_right = $pts_right;
                            $binary_points->pts_missed_left = $pts_missed_left;
                            $binary_points->save();

                            //Пишем данные в кошелек
                            if($pts_wallet_add > 0 OR $wallet_pts_missed > 0){
                                $wallet_data = [];
                                $wallet_data['user_id'] = $user_id; //ID
                                if(!empty($user_name)){
                                    $wallet_data['user_name'] = $user_name; //Пользователь
                                }
                                if($pts_wallet_add > 0){
                                    $wallet_data['add_money'] = $pts_wallet_add; //Значение для добавления данных в кошелек
                                }
                                if($wallet_pts_missed > 0){
                                    $wallet_data['update_red_pts'] = $wallet_pts_missed; //Значение для обновления данных по красным бонусам
                                }

                                $wallet_data_json = json_encode($wallet_data);

                                //Пишем данные в файл
                                $date = date('Y-m-d');
                                $url = "./wallet/".$date;
                                if(!is_dir($url)) {
                                    mkdir($url, 0775, true);
                                }

                                //Если нету файла - создаем, если есть - дописываем данные
                                if(!empty($user_name))
                                {
                                    $filename = './wallet/'.$date.'/'.$user_id.'.json';
                                    if (file_exists($filename)) {
                                        $data[] = $wallet_data;
                                        $fp = fopen($filename, 'a');
                                        fwrite($fp, json_encode($data));
                                        fclose($fp);
                                    } else {
                                        $data[] = $wallet_data_json;
                                        file_put_contents('./wallet/'.$date.'/'.$user_id.'.json', $data);
                                    }
                                }
                            }
                        }
                        if($side == 2){
                            //RIGHT
                            $pts_right = $pts_right + $parent_pts_real;
                            $pts_missed_right = $pts_missed_right + $parent_pts_cut;

                            //Сливаем стороны
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_left == $pts_right && $pts_left !== 0 && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = 0;
                                $pts_right = 0;
                            }
                            //Если слева больше
                            if($pts_left > $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = $pts_left - $pts_right;
                                $pts_right = 0;
                            }
                            //Если справа больше
                            if($pts_left < $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_right = $pts_right - $pts_left;
                                $pts_left = 0;
                            }

                            //Сливаем стороны красных бонусов
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_missed_left == $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }
                            //Если слева больше
                            if($pts_missed_left > $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_right;
                            }
                            //Если справа больше
                            if($pts_missed_left < $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }

                            //Обновляем запись
                            $binary_points->packs_right = $packs_right;
                            $binary_points->pts_left = $pts_left;
                            $binary_points->pts_right = $pts_right;
                            $binary_points->pts_missed_right = $pts_missed_right;
                            $binary_points->save();

                            //Пишем данные в кошелек
                            if($pts_wallet_add > 0 OR $wallet_pts_missed > 0){
                                $wallet_data = [];
                                $wallet_data['user_id'] = $user_id; //ID
                                if(!empty($user_name)){
                                    $wallet_data['user_name'] = $user_name; //Пользователь
                                }
                                if($pts_wallet_add > 0){
                                    $wallet_data['add_money'] = $pts_wallet_add; //Значение для добавления данных в кошелек
                                }
                                if($wallet_pts_missed > 0){
                                    $wallet_data['update_red_pts'] = $wallet_pts_missed; //Значение для обновления данных по красным бонусам
                                }

                                $wallet_data_json = json_encode($wallet_data);

                                //Пишем данные в файл
                                $date = date('Y-m-d');
                                $url = "./wallet/".$date;
                                if(!is_dir($url)) {
                                    mkdir($url, 0775, true);
                                }

                                //Если нету файла - создаем, если есть - дописываем данные
                                if(!empty($user_name))
                                {
                                    $filename = './wallet/'.$date.'/'.$user_id.'.json';
                                    if (file_exists($filename)) {
                                        $data[] = $wallet_data;
                                        $fp = fopen($filename, 'a');
                                        fwrite($fp, json_encode($data));
                                        fclose($fp);
                                    } else {
                                        $data[] = $wallet_data_json;
                                        file_put_contents('./wallet/'.$date.'/'.$user_id.'.json', $data);
                                    }
                                }
                            }
                        }
                        if($side == 1){
                            $side_name = 'LEFT';
                        }
                        if($side == 2){
                            $side_name = 'RIGHT';
                        }

                        //Пишем данные в базу по схлп. основных бонусов
                        if(!empty($user_name)){
//                            if(isset($pts_wallet_add) && !empty($pts_wallet_add) && $pts_wallet_add > 0){
//                                $expected_wallet = new ExpectedWallet();
//                                $expected_wallet->user_id = $user_id;
//                                $expected_wallet->bonus_id = 5;
//                                if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                    $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                                }
//                                $expected_wallet->expected = $pts_wallet_add;
//                                $expected_wallet->upd_expected = $pts_wallet_add;
//                                $expected_wallet->status = 0;
//                                $expected_wallet->customer_id = $id;//Пользователь который перешел
//                                $expected_wallet->product_id = $parent_user_package; //Пакет на который пользователь перешел
//                                $expected_wallet->created_at = $create_date;
//                                $expected_wallet->save();
//                            }
//                            else{
//                                //Пишем данные в базу по схлп. красных бонусов
//                                if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                    $expected_wallet = new ExpectedWallet();
//                                    $expected_wallet->user_id = $user_id;
//                                    $expected_wallet->bonus_id = 5;
//                                    if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                                        $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                                    }
//                                    $expected_wallet->expected = 0;
//                                    $expected_wallet->upd_expected = 0;
//                                    $expected_wallet->status = 0;
//                                    $expected_wallet->customer_id = $id;//Пользователь который перешел
//                                    $expected_wallet->product_id = $parent_user_package; //Пакет на который пользователь перешел
//                                    $expected_wallet->created_at = $create_date;
//                                    $expected_wallet->save();
//                                }
//                            }
                        }

//                        if(!empty($user_name)){
//                            echo 'ID пользователя ----- '.$user_id.'<br>';
//                            echo 'Имя пользователя ----- '.$user_name.'<br>';
//                            echo 'Пакет  ----- '.$parent_package_name.'<br>';
//                            echo 'Бонусы - '.$parent_pts."<br>";
//                            echo 'Начисление - '.$parent_pts_real."<br>";
//                            echo 'Заморожено - '.$parent_pts_cut."<br>";
//                            echo 'Сторона зачисления - '.$side_name.'<br>';
//                            echo 'Левое крыло было - '.$pts_left_old.'<br>';
//                            echo 'Левое крыло стало - '.$pts_left.'<br>';
//                            echo 'Правое крыло было - '.$pts_right_old.'<br>';
//                            echo 'Правое крыло стало - '.$pts_right.'<br>';
//                            echo 'Красная зона слева - '.$pts_missed_left.'<br>';
//                            echo 'Красная зона справа - '.$pts_missed_right.'<br>';
//                            echo 'В кошелек - '.$pts_wallet_add.'<br>';
//                            echo 'В кошелек в красную зону - '.$wallet_pts_missed.'<br>';
//                        }
                        $user_name = NULL;
                        $parent_data_id = NULL;
                    }
                }
                $side = $team_id;
                //echo "<hr>";
            }
            //Пишем данные одинарного пользователя
            else{
//
//                echo 'FROM ID - '.$id."<br>";
//                echo 'TO ID - '.$user_id."<br>";
//                echo 'USER_NAME - '.$user_name."<br>";
//                echo 'PACKAGE_ID - '.$user_package."<br>";
//                echo 'PACKAGE_NAME - '.$package_name."<br>";
//                echo 'FROM BINARY - '.$node_id."<br>";
//                echo 'TO BINARY - '.$item['node_id']."<br>";
//                echo 'PARENT_ID - '.$parent_id."<br>";
//                echo 'PTS - '.$pts."<br>";
//                echo 'PTS_REAL - '.$pts_real."<br>";
//                echo 'PTS_CUT - '.$pts_cut."<br>";
//                echo 'LEVEL - '.$level."<br>";
//                echo 'TEAM_ID - '.$team_id."<br>";
//                echo 'IS_REFUNDED - 0 <br>';
//                echo 'TEAM_LEFT - '.$team_left.'<br>';
//                echo 'TEAM_RIGHT - '.$team_right.'<br>';
//                echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
//                echo "<hr>";


                //Пишем данные о заявке
                $binary_points = new BonusBinaryPoints();
                $binary_points->from_user_id = $id;
                $binary_points->to_user_id = $user_id;
                $binary_points->from_node_id = $node_id;
                $binary_points->to_node_id = $item['node_id'];
                $binary_points->pts = $pts;
                $binary_points->pts_real = $pts_real;
                $binary_points->pts_cut = $pts_cut;
                $binary_points->level = $level;
                $binary_points->team_id = $team_id;
                $binary_points->is_refunded = '0';
                $binary_points->created_at = $date;
                $binary_points->save();

                //Обновляем количество бонусов
                $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $item['node_id'])->first();
                $packs_left = $binary_points['packs_left'];
                $packs_right = $binary_points['packs_right'];
                $pts_left = $binary_points['pts_left'];
                $pts_right = $binary_points['pts_right'];
                $pts_missed_left = $binary_points['pts_missed_left'];
                $pts_missed_right = $binary_points['pts_missed_right'];

                if($team_id == 1){
                    //LEFT
                    //$packs_left = $packs_left + 1;
                    $packs_left = $packs_left;
                    $pts_left = $pts_left + $pts_real;
                    $pts_missed_left = $pts_missed_left + $pts_cut;

                    //Обновляем запись
                    $binary_points->packs_left = $packs_left;
                    $binary_points->pts_left = $pts_left;
                    $binary_points->pts_missed_left = $pts_missed_left;
                    $binary_points->save();
                }
                if($team_id == 2){
                    //RIGHT
                    //$packs_right = $packs_right + 1;
                    $packs_right = $packs_right;
                    $pts_right = $pts_right + $pts_real;
                    $pts_missed_right = $pts_missed_right + $pts_cut;

                    //Обновляем запись
                    $binary_points->packs_right = $packs_right;
                    $binary_points->pts_right = $pts_right;
                    $binary_points->pts_missed_right = $pts_missed_right;
                    $binary_points->save();
                }

                //Пишем данные в базу по схлп. основных бонусов
//                if(isset($pts_wallet_add) && !empty($pts_wallet_add) && $pts_wallet_add > 0){
//                    $expected_wallet = new ExpectedWallet();
//                    $expected_wallet->user_id = $user_id;
//                    $expected_wallet->bonus_id = 5;
//                    if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                        $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                    }
//                    $expected_wallet->expected = $pts_wallet_add;
//                    $expected_wallet->upd_expected = $pts_wallet_add;
//                    $expected_wallet->status = 0;
//                    $expected_wallet->customer_id = $id;//Пользователь который перешел
//                    $expected_wallet->product_id = $package; //Пакет на который пользователь перешел
//                    $expected_wallet->created_at = $create_date;
//                    $expected_wallet->save();
//                }
//                else{
//                    //Пишем данные в базу по схлп. красных бонусов
//                    if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                        $expected_wallet = new ExpectedWallet();
//                        $expected_wallet->user_id = $user_id;
//                        $expected_wallet->bonus_id = 5;
//                        if(isset($wallet_pts_missed) && !empty($wallet_pts_missed) && $wallet_pts_missed > 0){
//                            $expected_wallet->red_bonus_expected = $wallet_pts_missed;
//                        }
//                        $expected_wallet->expected = 0;
//                        $expected_wallet->upd_expected = 0;
//                        $expected_wallet->status = 0;
//                        $expected_wallet->customer_id = $id;//Пользователь который перешел
//                        $expected_wallet->product_id = $package; //Пакет на который пользователь перешел
//                        $expected_wallet->created_at = $create_date;
//                        $expected_wallet->save();
//                    }
//                }
            }
            $level++;
        }
        return view('admin.updatesuccess');
    }

    public function detailsuser(Request $request, $id)
    {
//        return view('admin.updatebinarypoints', compact('data'));
        return view('admin.detailsuser', ['user_id' => $id]);
    }

    public function updatebinarypoints(Request $request, $id)
    {
//        return view('admin.updatebinarypoints', compact('data'));
        return view('admin.updatebinarypoints', ['user_id' => $id]);
    }

    public function testcreate(Request $request, $id, $date)
    {
        $binary_points = new BonusBinaryPoints();
        $binary_points->from_user_id = $id;
        $binary_points->to_user_id = '123';
        $binary_points->from_node_id = '123';
        $binary_points->to_node_id = '123';
        $binary_points->pts = '123';
        $binary_points->pts_real = '123';
        $binary_points->pts_cut = '123';
        $binary_points->level = '123';
        $binary_points->team_id = '123';
        $binary_points->is_refunded = '123';
//      $binary_points->created_at = '2021-01-01';
        $binary_points->created_at = $date;
        $binary_points->save();
        echo 'CREATE  OK';
    }

    public function testupdate(Request $request, $node_id)
    {
        $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $node_id)->first();

        //Обновляем инфо
        $packs_left = $binary_points['packs_left'];
        //$packs_left = $packs_left + 1;
        $packs_left = $packs_left;

        //Обновляем запись
        $binary_points->packs_left = $packs_left;
        $binary_points->save();

        echo 'UPDATE OK';
    }

    //Копия данных пользователя без удаления данных
    public function handbackup(Request $request, $id)
    {
        //Тащим старые данные пользователя
        $old_user_data  = User::findOrFail($id);
        $old_user_wallets_data = Wallet::query()->where('user_id', '=', $id)->get();
        $old_user_requisitions_data = Requisition::query()->where('user_id', '=', $id)->get();
        $old_user_requisitions_data_curator = Requisition::query()->where('curator_id', '=', $id)->get();
        $old_user_quittances_data = Quittance::query()->where('user_id', '=', $id)->get();
        $old_user_badges_data = Badge::query()->where('user_id', '=', $id)->get();

        $old_password = $old_user_data->password;
        $old_token = $old_user_data->remember_token;
        $old_user = json_encode($old_user_data);
        $old_wallets = json_encode($old_user_wallets_data);
        $old_requisitions = json_encode($old_user_requisitions_data);
        $old_requisitions_curator = json_encode($old_user_requisitions_data_curator);
        $old_quittances = json_encode($old_user_quittances_data);
        $old_badges = json_encode($old_user_badges_data);

        //Пишем старые данные в файл
        $url = "./temp_user_data/backup_".$id;
        if(!is_dir($url)) {
            mkdir($url, 0775, true);
        }

        file_put_contents('./temp_user_data/backup_'.$id.'/old_password.json', $old_password, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_token.json', $old_token, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_user.json', $old_user, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_wallets.json', $old_wallets, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_requisitions.json', $old_requisitions, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_requisitions_curator.json', $old_requisitions_curator, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_quittances.json', $old_quittances, FILE_APPEND);
        file_put_contents('./temp_user_data/backup_'.$id.'/old_badges.json', $old_badges, FILE_APPEND);
    }

    //Копия данных пользователя с удалением текущих данных
    public function handsave(Request $request, $id)
    {
        //Тащим старые данные пользователя
        $old_user_data  = User::findOrFail($id);
        $old_user_wallets_data = Wallet::query()->where('user_id', '=', $id)->get();
        $old_user_requisitions_data = Requisition::query()->where('user_id', '=', $id)->get();
        $old_user_requisitions_data_curator = Requisition::query()->where('curator_id', '=', $id)->get();
        $old_user_quittances_data = Quittance::query()->where('user_id', '=', $id)->get();
        $old_user_badges_data = Badge::query()->where('user_id', '=', $id)->get();

        $old_password = $old_user_data->password;
        $old_token = $old_user_data->remember_token;
        $old_user = json_encode($old_user_data);
        $old_wallets = json_encode($old_user_wallets_data);
        $old_requisitions = json_encode($old_user_requisitions_data);

        if(!empty($old_user_requisitions_data_curator)){
            $old_requisitions_curator = json_encode($old_user_requisitions_data_curator);
        }

        $old_quittances = json_encode($old_user_quittances_data);
        $old_badges = json_encode($old_user_badges_data);

        //Пишем старые данные в файл
        $url = "./temp_user_data/".$id;
        if(!is_dir($url)) {
            mkdir($url, 0775, true);
        }

        file_put_contents('./temp_user_data/'.$id.'/old_password.json', $old_password, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_token.json', $old_token, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_user.json', $old_user, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_wallets.json', $old_wallets, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_requisitions.json', $old_requisitions, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_requisitions_curator.json', $old_requisitions_curator, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_quittances.json', $old_quittances, FILE_APPEND);
        file_put_contents('./temp_user_data/'.$id.'/old_badges.json', $old_badges, FILE_APPEND);

        //Удаляем старые данные пользователя
        $old_user_wallets_data = Wallet::query()->where('user_id', '=', $id)->delete();
        $old_user_requisitions_data = Requisition::query()->where('user_id', '=', $id)->delete();
        $old_user_badges_data = Badge::query()->where('user_id', '=', $id)->delete();
        $old_user_data  = User::query()->where('id', '=', $id)->delete();
        $old_user_requisitions_data_curator = Requisition::query()->where('curator_id', '=', $id)->delete();
        $old_user_be_partner_request = BePartnerRequest::query()->where('user_id', '=', $id)->delete();
        $old_user_be_partner_request_curator = BePartnerRequest::query()->where('curator_id', '=', $id)->delete();
    }

    //Обновление данных нового пользователя
    public function handupdate(Request $request, $id, $old_id, $date)
    {
        //Обрабатываем файлы со старыми данными пользователя
        $old_password_json = file_get_contents("./temp_user_data/".$old_id."/old_password.json");
        $old_token_json = file_get_contents("./temp_user_data/".$old_id."/old_token.json");
        $old_user_json_file = file_get_contents("./temp_user_data/".$old_id."/old_user.json");
        $old_wallets_json_file = file_get_contents("./temp_user_data/".$old_id."/old_wallets.json");
        $old_requisitions_json_file = file_get_contents("./temp_user_data/".$old_id."/old_requisitions.json");
        $old_requisitions_json_file_curator = file_get_contents("./temp_user_data/".$old_id."/old_requisitions_curator.json");
        $old_quittances_json_file = file_get_contents("./temp_user_data/".$old_id."/old_quittances.json");
        $old_badges_json_file = file_get_contents("./temp_user_data/".$old_id."/old_wallets.json");

        $old_user_json = json_decode($old_user_json_file);
        $old_wallets_json = json_decode($old_wallets_json_file);
        $old_requisitions_json = json_decode($old_requisitions_json_file);
        $old_requisitions_json_curator = json_decode($old_requisitions_json_file_curator);
        $old_quittances_json = json_decode($old_quittances_json_file);
        $old_badges_json = json_decode($old_badges_json_file);

        //Сливаем новые данные пользователя со старыми
        //Профиль
        $user_data  = User::findOrFail($id);
        $user_data->name = $old_user_json->name;
        $user_data->last_name = $old_user_json->last_name;
        $user_data->login = $old_user_json->login;
        $user_data->email = $old_user_json->email;
        $user_data->password = $old_password_json;
        $user_data->remember_token = $old_token_json;
        $user_data->birthday = $old_user_json->birthday;
        $user_data->phone = $old_user_json->phone;
        $user_data->city = $old_user_json->city;
        $user_data->photo = $old_user_json->photo;
        $user_data->country_id = $old_user_json->country_id;
        $user_data->cabinet_id = $old_user_json->cabinet_id;
        $user_data->package_id = $old_user_json->package_id;
        $user_data->status_id = $old_user_json->status_id;
        //$user_data->tree_node_id = $old_user_json->tree_node_id;
        $user_data->tree_inviter_node_id = $old_user_json->tree_inviter_node_id;
        $user_data->is_blocked = $old_user_json->is_blocked;
        $user_data->is_active = $old_user_json->is_active;
        $user_data->is_qualified = $old_user_json->is_qualified;
        $user_data->is_oss_ever = $old_user_json->is_oss_ever;
        $user_data->has_activity_sib = $old_user_json->has_activity_sib;
        $user_data->has_activity_oss = $old_user_json->has_activity_oss;
        $user_data->is_wizard_activated = $old_user_json->is_wizard_activated;
        $user_data->is_female = $old_user_json->is_female;
        $user_data->activated_at = $old_user_json->activated_at;
        $user_data->oss_activated_at = $date;
        $user_data->oss_registered_at = $date;
        $user_data->sib_registered_at = $date;
        $user_data->created_at = $date;
        $user_data->updated_at = $date;
        $user_data->save();

        //Кошелек
        foreach($old_wallets_json as $wallet){
            $user_wallet = new Wallet();
            $user_wallet->user_id = $id;
            $user_wallet->bonus_id = $wallet->bonus_id;
            $user_wallet->earned = $wallet->earned;
            $user_wallet->expected = $wallet->expected;
            $user_wallet->available = $wallet->available;
            $user_wallet->created_at = $date;
            $user_wallet->updated_at = $date;
            $user_wallet->save();
        }

        //Метки
        foreach($old_badges_json as $badge){
            if(!empty($badge->menu_id)){
                $user_badges = new Badge();
                $user_badges->menu_id = $badge->menu_id;
                $user_badges->user_id = $id;
                $user_badges->badgable_id = $badge->badgable_id;
                $user_badges->badgable_type = $badge->badgable_type;
                $user_badges->save();
            }
        }

        //Квитанция
        if(!empty($old_quittances_json)){
            foreach($old_quittances_json as $quittances){
                $user_quittances = new Quittance();
                $user_quittances->user_id = $id;
                $user_quittances->file_id = $quittances->file_id;
                $user_quittances->created_at = $date;
                $user_quittances->updated_at = $date;
                $user_quittances->save();
                $quit_id = $user_quittances->id;
            }
            //Запросы
            foreach($old_requisitions_json as $requisitions){
                if(!empty($requisitions->curator_id)){
                    $user_quittances = new Quittance();
                    $user_quittances->user_id = $id;
                    $user_quittances->curator_id = $requisitions->curator_id;
                    $user_quittances->product_id = $requisitions->product_id;
                    $user_quittances->requisition_type_id = $requisitions->requisition_type_id;
                    $user_quittances->user_quittance_id = $quit_id;
                    $user_quittances->curator_quittance_id = $requisitions->curator_quittance_id;
                    $user_quittances->is_confirmed = $requisitions->is_confirmed;
                    $user_quittances->is_cancelled = $requisitions->is_cancelled;
                    $user_quittances->confirmed_at = $date;
                    $user_quittances->curator_confirmed_at = $requisitions->curator_confirmed_at;
                    $user_quittances->cancelled_at = $requisitions->cancelled_at;
                    $user_quittances->created_at = $date;
                    $user_quittances->updated_at = $date;
                    $user_quittances->save();
                }
            }
        }


        //Запросы куратор
        foreach($old_requisitions_json_curator as $requisitions_curator){
            if(!empty($requisitions_curator->curator_id)){
                $user_quittances = new Quittance();
                $user_quittances->user_id = $requisitions_curator->user_id;
                $user_quittances->curator_id = $id;
                $user_quittances->product_id = $requisitions_curator->product_id;
                $user_quittances->requisition_type_id = $requisitions_curator->requisition_type_id;
                $user_quittances->user_quittance_id = $quit_id;
                $user_quittances->curator_quittance_id = $requisitions_curator->curator_quittance_id;
                $user_quittances->is_confirmed = $requisitions_curator->is_confirmed;
                $user_quittances->is_cancelled = $requisitions_curator->is_cancelled;
                $user_quittances->confirmed_at = $date;
                $user_quittances->curator_confirmed_at = $requisitions_curator->curator_confirmed_at;
                $user_quittances->cancelled_at = $requisitions_curator->cancelled_at;
                $user_quittances->created_at = $date;
                $user_quittances->updated_at = $date;
                $user_quittances->save();
            }
        }
//        print_r($old_password_json);
//        print_r($user_data);
    }

    //Восстановление старого пароля пользователя
    public function handrestore(Request $request, $id)
    {
        $filename_backup = "./temp_user_data/backup_".$id."/old_password.json";
        $filename = "./temp_user_data/backup_".$id."/old_password.json";
        //если есть бекап
        if (file_exists($filename_backup)) {
            $old_password_json = file_get_contents($filename_backup);
        }
        else{
            //если есть данные с обновления
            if (file_exists($filename)) {
                $old_password_json = file_get_contents($filename);
            }
            else{
                $old_password_json = NULL;
            }
        }
        //обновляем данные
        if($old_password_json !== NULL){
            $user_data  = User::findOrFail($id);
            $user_data->password = $old_password_json;
            $user_data->save();
        }
    }

    //Полное восстановление старого профиля пользователя
    public function handfullrestore(Request $request, $id)
    {
        //Удаляем старые данные пользователя
        $old_user_wallets_data = Wallet::query()->where('user_id', '=', $id)->delete();
        $old_user_requisitions_data = Requisition::query()->where('user_id', '=', $id)->delete();
        $old_user_badges_data = Badge::query()->where('user_id', '=', $id)->delete();
        $old_user_data  = User::query()->where('id', '=', $id)->delete();
        $old_user_requisitions_data_curator = Requisition::query()->where('curator_id', '=', $id)->delete();
        $old_user_be_partner_request = BePartnerRequest::query()->where('user_id', '=', $id)->delete();
        $old_user_be_partner_request_curator = BePartnerRequest::query()->where('curator_id', '=', $id)->delete();

        //Обрабатываем файлы со старыми данными пользователя
        //Пароль
        $old_password_backup = "./temp_user_data/backup_".$id."/old_password.json";
        $old_password_file = "./temp_user_data/".$id."/old_password.json";
        if (file_exists($old_password_backup)) {
            $old_password_json = file_get_contents($old_password_backup);
        }
        else{
            $old_password_json = file_get_contents($old_password_file);
        }
        //Токен
        $old_token_backup = "./temp_user_data/backup_".$id."/old_token.json";
        $old_token_file = "./temp_user_data/".$id."/old_token.json";
        if (file_exists($old_token_backup)) {
            $old_token_json = file_get_contents($old_token_backup);
        }
        else{
            $old_token_json = file_get_contents($old_token_file);
        }
        //Старые данные
        $old_user_backup = "./temp_user_data/backup_".$id."/old_user.json";
        $old_user_file = "./temp_user_data/".$id."/old_user.json";
        if (file_exists($old_user_backup)) {
            $old_user_json_file = file_get_contents($old_user_backup);
        }
        else{
            $old_user_json_file = file_get_contents($old_user_file);
        }
        //Кошелек
        $old_wallets_backup = "./temp_user_data/backup_".$id."/old_wallets.json";
        $old_wallets_file = "./temp_user_data/".$id."/old_wallets.json";
        if (file_exists($old_wallets_backup)) {
            $old_wallets_json_file = file_get_contents($old_wallets_backup);
        }
        else{
            $old_wallets_json_file = file_get_contents($old_wallets_file);
        }
        //Запросы
        $old_requisitions_backup = "./temp_user_data/backup_".$id."/old_requisitions.json";
        $old_requisitions_file = "./temp_user_data/".$id."/old_requisitions.json";
        if (file_exists($old_requisitions_backup)) {
            $old_requisitions_json_file = file_get_contents($old_requisitions_backup);
        }
        else{
            $old_requisitions_json_file = file_get_contents($old_requisitions_file);
        }
        //Квитанции
        $old_quittances_backup = "./temp_user_data/backup_".$id."/old_quittances.json";
        $old_quittances_file = "./temp_user_data/".$id."/old_quittances.json";
        if (file_exists($old_requisitions_backup)) {
            $old_quittances_json_file = file_get_contents($old_quittances_backup);
        }
        else{
            $old_quittances_json_file = file_get_contents($old_quittances_file);
        }
        //Метки
        $old_quittances_backup = "./temp_user_data/backup_".$id."/old_wallets.json";
        $old_quittances_file = "./temp_user_data/".$id."/old_wallets.json";
        if (file_exists($old_quittances_backup)) {
            $old_badges_json_file = file_get_contents($old_quittances_backup);
        }
        else{
            $old_badges_json_file = file_get_contents($old_quittances_file);
        }

        $old_user_json = json_decode($old_user_json_file);
        $old_wallets_json = json_decode($old_wallets_json_file);
        $old_requisitions_json = json_decode($old_requisitions_json_file);
        $old_quittances_json = json_decode($old_quittances_json_file);
        $old_badges_json = json_decode($old_badges_json_file);

        //Восстанавливаем старые данные пользователя
        //Профиль
        $user_data  = User::findOrFail($id);
        $user_data->name = $old_user_json->name;
        $user_data->last_name = $old_user_json->last_name;
        $user_data->login = $old_user_json->login;
        $user_data->email = $old_user_json->email;
        $user_data->password = $old_password_json;
        $user_data->remember_token = $old_token_json;
        $user_data->birthday = $old_user_json->birthday;
        $user_data->phone = $old_user_json->phone;
        $user_data->city = $old_user_json->city;
        $user_data->photo = $old_user_json->photo;
        $user_data->country_id = $old_user_json->country_id;
        $user_data->cabinet_id = $old_user_json->cabinet_id;
        $user_data->package_id = $old_user_json->package_id;
        $user_data->status_id = $old_user_json->status_id;
        $user_data->tree_node_id = $old_user_json->tree_node_id;
        $user_data->tree_inviter_node_id = $old_user_json->tree_inviter_node_id;
        $user_data->is_blocked = $old_user_json->is_blocked;
        $user_data->is_active = $old_user_json->is_active;
        $user_data->is_qualified = $old_user_json->is_qualified;
        $user_data->is_oss_ever = $old_user_json->is_oss_ever;
        $user_data->has_activity_sib = $old_user_json->has_activity_sib;
        $user_data->has_activity_oss = $old_user_json->has_activity_oss;
        $user_data->is_wizard_activated = $old_user_json->is_wizard_activated;
        $user_data->is_female = $old_user_json->is_female;
        $user_data->activated_at = $old_user_json->activated_at;
        $user_data->oss_activated_at = $old_user_json->oss_activated_at;
        $user_data->oss_registered_at = $old_user_json->oss_registered_at;
        $user_data->sib_registered_at = $old_user_json->sib_registered_at;
        $user_data->created_at = $old_user_json->created_at;
        $user_data->updated_at = $old_user_json->updated_at;
        $user_data->save();

        //Кошелек
        foreach($old_wallets_json as $wallet){
            $user_wallet = new Wallet();
            $user_wallet->user_id = $wallet->user_id;
            $user_wallet->bonus_id = $wallet->bonus_id;
            $user_wallet->earned = $wallet->earned;
            $user_wallet->expected = $wallet->expected;
            $user_wallet->available = $wallet->available;
            $user_wallet->created_at = $wallet->created_at;
            $user_wallet->updated_at = $wallet->updated_at;
            $user_wallet->save();
        }

        //Метки
        foreach($old_badges_json as $badge){
            if(!empty($badge->menu_id)){
                $user_badges = new Badge();
                $user_badges->menu_id = $badge->menu_id;
                $user_badges->user_id = $badge->user_id;
                $user_badges->badgable_id = $badge->badgable_id;
                $user_badges->badgable_type = $badge->badgable_type;
                $user_badges->save();
            }
        }

        //Квитанция
        foreach($old_quittances_json as $quittances){
            $user_quittances = new Quittance();
            $user_quittances->user_id = $quittances->user_id;
            $user_quittances->file_id = $quittances->file_id;
            $user_quittances->created_at = $quittances->created_at;
            $user_quittances->updated_at = $quittances->updated_at;
            $user_quittances->save();
            $quit_id = $user_quittances->id;
        }


        //Запросы
        foreach($old_requisitions_json as $requisitions){
            if(!empty($requisitions->curator_id)){
                $user_quittances = new Quittance();
                $user_quittances->user_id = $id;
                $user_quittances->curator_id = $requisitions->curator_id;
                $user_quittances->product_id = $requisitions->product_id;
                $user_quittances->requisition_type_id = $requisitions->requisition_type_id;
                $user_quittances->user_quittance_id = $quit_id;
                $user_quittances->curator_quittance_id = $requisitions->curator_quittance_id;
                $user_quittances->is_confirmed = $requisitions->is_confirmed;
                $user_quittances->is_cancelled = $requisitions->is_cancelled;
                $user_quittances->confirmed_at = $requisitions->confirmed_at;
                $user_quittances->curator_confirmed_at = $requisitions->curator_confirmed_at;
                $user_quittances->cancelled_at = $requisitions->cancelled_at;
                $user_quittances->created_at = $requisitions->created_at;
                $user_quittances->updated_at = $requisitions->updated_at;
                $user_quittances->save();
            }
        }
//        print_r($old_password_json);
//        print_r($user_data);
    }
    //Форма начала слияния профилей пользователей
    public function handmerge(Request $request, $id)
    {
        return view('admin.handmerge', ['user_id' => $id]);
    }

    public function usertoptree(Request $request, $id, TreeService $tree)
    {
        $tek_package = '1';
        $package = '4';
        $date = '2021-03-04';
        //Текущий пакет пользователя дял теста 2 - Стандарт
//        $tek_package = '2';

        //Получаем данные пользователя по ID
        $user_data  = User::findOrFail($id);
        $node_id  = $user_data['tree_node_id'];

        //Получаем данные родительского пользователя
        $initiatorNode  = Node::findOrFail($node_id);

        //Получаем сетку пользователей под заданным юзером
        $node = Node_tree::with('user')->where('user_id', $id)->first();
        $tree = $tree->getTree($node);

        //Получаем данные пользоателей и строим сетку над заданным юзером
        $top_team = Top_team::with('node')->where('team_node_id', $node_id)->get();

        //Уровень начисления
        $level = 1;
        foreach($top_team as $item){
            //print_r($item);

            $parent_id = $item['node']['inviter_id'];
            $user_id = $item['node']['user_id'];
            $user_status = $item['node']['is_active'];
            $users = User::query()->where('tree_node_id', '=', $item['node_id'])->get();
            $user_package_data  = User::findOrFail($user_id);
            //Получаем активный пакет и ФИО пользователя
            if(!empty($users)){
                foreach($users as $user_data){
                    if(!empty($user_data['package_id'])){
//                        $user_package = $user_data['package_id'];
                        $user_package = 3;
                        $user_name = $user_data['name'].' '.$user_data['last_name'];
                        //break;
                    }
                    else{
                        $user_package = NULL;
                    }
                }
            }
            $user_package = $user_package_data['package_id'];
            if($user_package == 1){
                $package_name = 'Basic';
            }
            if($user_package == 2){
                $package_name = 'Standart';
            }
            if($user_package == 3){
                $package_name = 'Premium';
            }
            if($user_package == 4){
                $package_name = 'Vip';
            }

            //Получаем пакет по которому начисляем баллы по сетке
//            $package = '1'; //Для теста начисляем баллы за Basic
//            $package = '2'; //Для теста начисляем баллы за Standart
//            $package = '3'; //Для теста начисляем баллы за Premium
//            $package = '4'; //Для теста начисляем баллы за VIP

            //Вычисляем начисление баллов и реальных баллов
            //Переход с пакета BASIC
            if($tek_package == 1){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 600;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 600;
                        $pts_real = 0;
                        $pts_cut = 600;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 600;
                        $pts_real = 100;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 600;
                        $pts_real = 600;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 2000;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 2000;
                        $pts_real = 0;
                        $pts_cut = 2000;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 2000;
                        $pts_real = 100;
                        $pts_cut = 1900;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 2000;
                        $pts_real = 2000;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета STANDART
            if($tek_package == 2){
                if($package == 1){
                    //Перебираем разные варианты начислений баллов пользователям
                    $package_reward = 0;
                }
                if($package == 2){
                    $package_reward = 100;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 100;
                        $pts_real = 0;
                        $pts_cut = 100;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 100;
                        $pts_real = 100;
                        $pts_cut = 0;
                    }
                }
                if($package == 3){
                    $package_reward = 500;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 500;
                        $pts_real = 0;
                        $pts_cut = 500;
                    }
                    //Начисление для Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 500;
                        $pts_real = 500;
                        $pts_cut = 0;
                    }
                }
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }
            //Переход с пакета PREMIUM
            if($tek_package == 3){
                if($package == 4){
                    $package_reward = 1400;
                    //Перебираем разные варианты начислений баллов пользователям
                    //Начисление для Basic
                    if($user_package == 1){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart
                    if($user_package == 2){
                        $pts = 1400;
                        $pts_real = 0;
                        $pts_cut = 1400;
                    }
                    //Начисление для Standart, Premium, VIP
                    if($user_package == 3 OR $user_package == 4){
                        $pts = 1400;
                        $pts_real = 1400;
                        $pts_cut = 0;
                    }
                }
            }


            //TEAM ID
            $team_id = $item['node']['team_id'];
            if(empty($side)){
                $side = $team_id;
            }
            if($team_id == 1){
                $team_id_name = 'LEFT';
            }
            if($team_id == 2){
                $team_id_name = 'RIGHT';
            }
            $team_left = $item['node']['last_left_node_id'];
            $team_right = $item['node']['last_right_node_id'];


            //Тестовый вывод основной записи
//            echo 'USER_NAME - '.$user_name."<br>";
//            echo 'FROM USER_ID - '.$user_id."<br>";
//            echo 'PACKAGE_ID - '.$user_package."<br>";
//            echo 'PACKAGE_NAME - '.$package_name."<br>";
//            echo 'FROM BINARY - '.$node_id."<br>";//            echo 'TO BINARY - '.$item['node_id']."<br>";
//            echo 'PARENT_ID - '.$parent_id."<br>";
//            echo 'PTS - '.$pts."<br>";
//            echo 'PTS_REAL - '.$pts_real."<br>";
//            echo 'PTS_CUT - '.$pts_cut."<br>";
//            echo 'LEVEL - '.$level."<br>";
//            echo 'TEAM_ID - '.$team_id."<br>";
//            echo 'IS_REFUNDED - 0 <br>';
//            echo 'TEAM_LEFT - '.$team_left.'<br>';
//            echo 'TEAM_RIGHT - '.$team_right.'<br>';
//            echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
//            echo "<hr>";


            //Проверяем есть ли для начисления инвайтер
//                if(!empty($parent_id) && $parent_id !== $user_id){
            if(!empty($parent_id)){
                //Получаем данные
                $parent_data = BinaryTreeNode::findOrFail($parent_id);

                $parent_arr =  BinaryTreeNode::query()->where('user_id', '=', $user_id)->where('team_id', '=', $team_id)->get();
                foreach($parent_arr as $parent_line){
                    $parent_data_id = $parent_data['user_id'];
                    $parent_team_id = $parent_data['team_id'];

                    if($user_id !== $parent_data_id)
                    {
                        if($parent_team_id == 1){
                            $parent_team_id_name = 'LEFT';
                        }
                        if($parent_team_id == 2){
                            $parent_team_id_name = 'RIGHT';
                        }
                        $parent_user_data = User::findOrFail($parent_data_id);

                        //Готовим данные
                        $parent_user_name = $parent_user_data['name'].' '.$parent_user_data['last_name'];
                        // $parent_user_package = $parent_user_data['package_id'];
                        $parent_user_package = $user_package;

                        //Обработка по пакету
                        if($parent_user_package == 1){
                            $parent_package_name = 'Basic';
                        }
                        if($parent_user_package == 2){
                            $parent_package_name = 'Standart';
                        }
                        if($parent_user_package == 3){
                            $parent_package_name = 'Premium';
                        }
                        if($parent_user_package == 4){
                            $parent_package_name = 'Vip';
                        }

                        //Вычисляем начисление баллов и реальных баллов
                        //С BASIC
                        if($tek_package == 1){
                            if($package == 1){
                                //Перебираем разные варианты начислений баллов пользователям
                                $package_reward = 0;
                            }
                            if($package == 2){
                                $package_reward = 100;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 100;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 100;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 100;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 3){
                                $package_reward = 600;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 600;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 600;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 600;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 600;
                                    $parent_pts_real = 600;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 2000;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 2000;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 100;
                                    $parent_pts_cut = 1900;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 2000;
                                    $parent_pts_real = 2000;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //При переходе со STANDART
                        if($tek_package == 2){
                            if($package == 3){
                                $package_reward = 500;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 500;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 500;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 500;
                                    $parent_pts_real = 500;
                                    $parent_pts_cut = 0;
                                }
                            }
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }
                        //С Premium
                        if($tek_package == 3){
                            if($package == 4){
                                $package_reward = 1400;
                                //Перебираем разные варианты начислений баллов пользователям
                                //Начисление для Basic
                                if($parent_user_package == 1){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Standart
                                if($parent_user_package == 2){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 0;
                                    $parent_pts_cut = 1400;
                                }
                                //Начисление для Premium, VIP
                                if($parent_user_package == 3 OR $parent_user_package == 4){
                                    $parent_pts = 1400;
                                    $parent_pts_real = 1400;
                                    $parent_pts_cut = 0;
                                }
                            }
                        }

                        //Пишем здесь данные пользователя
//                            echo 'USER NAME ----- '.$user_name.'<br>';
//                            echo 'FROM ID ----- '.$id.'<br>';
//                            echo 'PACKAGE ID  ----- '.$parent_user_package.'<br>';
//                            echo 'PACKAGE NAME  ----- '.$parent_package_name.'<br>';
//                            echo 'TO ID ----- '.$user_id.'<br>';
//                            echo 'FROM BINARY ----- '.$node_id.'<br>';
//                            echo 'TO BINARY ----- '.$parent_line['id'].'<br>';
//                            echo 'PARENT_PTS - '.$parent_pts."<br>";
//                            echo 'PARENT_PTS_REAL - '.$parent_pts_real."<br>";
//                            echo 'PARENT_PTS_CUT - '.$parent_pts_cut."<br>";
//                            echo 'PARENT_IS_REFUNDED - 0 <br>';
//                            echo 'TEAM_ID - '.$team_id."<br>";
//                            echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
//                            echo 'PARENT_TEAM_ID - '.$parent_team_id."<br>";
//                            echo 'PARENT_TEAM_ID_NAME - '.$parent_team_id_name.'<br>';

                        //Обновляем количество бонусов
                        $binary_points = BinaryTreeNodeInfo::query()->where('node_id', '=', $parent_line['id'])->first();
                        $packs_left = $binary_points['packs_left'];
                        $packs_right = $binary_points['packs_right'];
                        $pts_left = $binary_points['pts_left'];
                        $pts_right = $binary_points['pts_right'];
                        $pts_left_old = $binary_points['pts_left'];
                        $pts_right_old = $binary_points['pts_right'];
                        $pts_missed_left = $binary_points['pts_missed_left'];
                        $pts_missed_right = $binary_points['pts_missed_right'];
                        $pts_missed_left_old = $binary_points['pts_missed_left'];
                        $pts_missed_right_old = $binary_points['pts_missed_right'];
                        $pts_wallet_add = 0;
                        $wallet_pts_missed = 0;
                        if($side == 1){
                            //LEFT
                            $pts_left = $pts_left + $parent_pts_real;
                            $pts_missed_left = $pts_missed_left + $parent_pts_cut;

                            //Сливаем стороны
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_left == $pts_right && $pts_left !== 0 && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_left = 0;
                                $pts_right = 0;
                            }
                            //Если слева больше
                            if($pts_left > $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_right;
                                $pts_left = $pts_left - $pts_right;
                                $pts_right = 0;
                            }
                            //Если справа больше
                            if($pts_left < $pts_right && $pts_left !== 0  && $pts_right !== 0){
                                $pts_wallet_add = $pts_left;
                                $pts_right = $pts_right - $pts_left;
                                $pts_left = 0;
                            }

                            //Сливаем стороны красных бонусов
                            //Если по обе стороны одинаковое значение - обнуляем и пишем в кошелек
                            if($pts_missed_left == $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }
                            //Если слева больше
                            if($pts_missed_left > $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_right;
                            }
                            //Если справа больше
                            if($pts_missed_left < $pts_missed_right && $pts_missed_left !== 0 && $pts_missed_right !== 0){
                                $wallet_pts_missed = $pts_missed_left;
                            }

                        }


//                            echo 'WRITE PACKS TO (ID) - '.$side.'<br>';
//                            echo 'WRITE PACKS TO SIDE - '.$side_name.'<br>';
                        if(!empty($user_name)){
                            echo 'ID пользователя ----- '.$user_id.'<br>';
                            echo 'Имя пользователя ----- '.$user_name.'<br>';
                            echo 'Пакет  ----- '.$parent_package_name.'<br>';
                            echo 'Бонусы - '.$parent_pts."<br>";
                            echo 'Начисление - '.$parent_pts_real."<br>";
                            echo 'Заморожено - '.$parent_pts_cut."<br>";
                        }
                        $user_name = NULL;
                        $parent_data_id = NULL;
                    }
                }
                $side = $team_id;
                echo "<hr>";
            }
            //Пишем данные одинарного пользователя
            else{

                echo 'FROM ID - '.$id."<br>";
                echo 'TO ID - '.$user_id."<br>";
                echo 'USER_NAME - '.$user_name."<br>";
                echo 'PACKAGE_ID - '.$user_package."<br>";
                echo 'PACKAGE_NAME - '.$package_name."<br>";
                echo 'FROM BINARY - '.$node_id."<br>";
                echo 'TO BINARY - '.$item['node_id']."<br>";
                echo 'PARENT_ID - '.$parent_id."<br>";
                echo 'PTS - '.$pts."<br>";
                echo 'PTS_REAL - '.$pts_real."<br>";
                echo 'PTS_CUT - '.$pts_cut."<br>";
                echo 'LEVEL - '.$level."<br>";
                echo 'TEAM_ID - '.$team_id."<br>";
                echo 'IS_REFUNDED - 0 <br>';
                echo 'TEAM_LEFT - '.$team_left.'<br>';
                echo 'TEAM_RIGHT - '.$team_right.'<br>';
                echo 'TEAM_ID_NAME - '.$team_id_name.'<br>';
                echo "<hr>";

            }

            //Очищаем переменные
//                $user_id = NULL;
//                $user_package = NULL;
//                $package_name = NULL;
//                $team_id = NULL;
//                $pts = NULL;
//                $pts_real = NULL;
//                $pts_cut = NULL;
//                $team_left = NULL;
//                $team_right = NULL;
            $level++;
        }
    }

    //Форма для обновления даты регистрации пользователя
    public function handupdatedate(Request $request, $id)
    {
        //Получаем данные пользователя по ID
        $user_data  = User::findOrFail($id);

        //Тянем даты регистрации и активации OSS-абонемента с данным пользователем
        $user_oss  = Subscription::query()->where('user_id', '=', $id)->orderBy('id', '=', SORT_DESC)->first();

        return view('admin.handupdatedate', [
            'user_id' => $id,
            'user_data' => $user_data,
            'user_oss' => $user_oss
        ]);
    }

    //Форма для обновления количества бонусов пользователя
    public function handupdatebonuses(Request $request, $id)
    {
        //Получаем данные пользователя по ID
        $user_data  = User::findOrFail($id);
        $node_id  = $user_data['tree_node_id'];
        $user_name = $user_data['name'].' '.$user_data['last_name'];

        //Тянем данные по бонусам
        $tek_bonuses = BinaryTreeNodeInfo::query()->where('node_id', '=', $node_id)->first();

        //Обновляем значения
        return view('admin.handupdatebonuses', [
            'user_id' => $id,
            'tek_bonuses' => $tek_bonuses,
            'user_name' => $user_name,
        ]);
    }

    //Обновляем количество бонусов пользователя
    public function handupdatebonusessuccess(Request $request)
    {
        $binary_id = $request->binary_id;
        if(isset($binary_id) && !empty($binary_id)){
            $update_binary = BinaryTreeNodeInfo::query()->where('node_id', '=', $binary_id)->first();
            $update_binary->packs_left = $request->packs_left;
            $update_binary->packs_right = $request->packs_right;
            $update_binary->pts_left = $request->pts_left;
            $update_binary->pts_right = $request->pts_right;
            $update_binary->pts_missed_left = $request->pts_missed_left;
            $update_binary->pts_missed_right = $request->pts_missed_right;
            $update_binary->save();
        }

    }

    //Обновляем даты регистрации пользователя и регистрации абонементов
    public function handupdatedatesuccess(Request $request)
    {
        $userid = $request->userid;
        $oss_id = $request->oss_id;
        $subscription_registered_at = $request->subscription_registered_at;
        $subscription_expired_at = $request->subscription_expired_at;

        //Проходимся по данным пользователя
        if(isset($userid) && !empty($userid)){
            //Обновляем данные регистрации и активации пользователя
            $user_data  = User::findOrFail($userid);
            $user_data->activated_at = $request->activated_at;
            $user_data->oss_activated_at = $request->oss_activated_at;
            $user_data->oss_registered_at = $request->oss_registered_at;
            $user_data->sib_registered_at = $request->sib_registered_at;
            $user_data->created_at = $request->created_at;
            $user_data->save();
        }
        //Проходимся по указанному пакету пользователя
        if(isset($oss_id) && !empty($oss_id) && $oss_id !== 'NULL'){
            $subscription = Subscription::findOrFail($oss_id);
            $subscription->started_at = $subscription_registered_at;
            $subscription->expired_at = $subscription_expired_at;
            $subscription->created_at = $subscription_registered_at;
            $subscription->updated_at = $subscription_registered_at;
            $subscription->save();
        }
        //Если ID отсутствует - добавляем новый (без регистрации в продажах)
        else{
            if(!empty($subscription_registered_at) && !empty($subscription_expired_at) && $subscription_registered_at !== 'NULL'){
                //Создаем новую запись
                $subscription = new Subscription();
                $subscription->user_id = $userid;
                $subscription->product_id = 5;
                $subscription->started_at = $subscription_registered_at;
                $subscription->expired_at = $subscription_expired_at;
                $subscription->created_at = $subscription_registered_at;
                $subscription->updated_at = $subscription_registered_at;
                $subscription->save();

                //Правим данные в профиле пользователя, если нужно проставить статусы
                $user_data  = User::findOrFail($userid);
                $user_data->is_active = 1;
                $user_data->is_wizard_activated = 1;
                if($request->activated_at !== 'NULL'){
                    $user_data->activated_at = $request->activated_at;
                }
                if($request->oss_activated_at !== 'NULL'){
                    $user_data->oss_activated_at = $request->oss_activated_at;
                }
                if($request->oss_registered_at !== 'NULL'){
                    $user_data->oss_registered_at = $request->oss_registered_at;
                }
                if($request->sib_registered_at !== 'NULL'){
                    $user_data->sib_registered_at = $request->sib_registered_at;
                }
                if($request->created_at !== 'NULL'){
                    $user_data->created_at = $request->created_at;
                }
                $user_data->save();
            }
        }
    }
}