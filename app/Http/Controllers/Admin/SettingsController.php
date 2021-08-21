<?php

namespace App\Http\Controllers\Admin;

use App\Models\Badge;
use App\Models\BePartnerRequest;
use App\Models\BonusBinaryPoints;
use App\Models\Menu;
use App\Models\News;
use App\Models\OssNews;
use App\Models\BonusBinaryPoints as PointsUpd;
use App\Models\Trees\BinaryTreeNode;
use App\Models\Trees\BinaryTreeNode as NodeUpd;
use App\Models\Trees\BinaryTreeNodeInfo;
use App\Models\Trees\OnlineSmartSystemTreeNode as Node;
use App\Services\Oss\TreeService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Sib\BinaryTreeService;
use App\Models\Trees\BinaryTreeTeam as Top_team;

/**
 * @package App\Http\Controllers\Admin
 */
class SettingsController extends AdminController
{
    protected $tree;

    public function __construct(TreeService $tree)
    {
        $this->tree = $tree;
    }

    public function index()
    {
        return view('admin.settings');
    }

    //Удаление ссылки на раздел финансовый календарь
    public function delfincalendar()
    {
        $item = Menu::query()->where('name', '=', 'Платежный календарь')->delete();
    }

    //Удаление ссылки на раздел кошелек
    public function delwallets()
    {
        $item = Menu::query()->where('name', '=', 'Кошелек')->delete();
    }

    //перевязка пользователей OSS к новым нодам
    public function updateosstree()
    {
        //$node = Node::where('user_id', 2221)->first(); //Тянем данные по юзеру
        $node = User::where('email', 'kobelevakarina@mail.ru')->first(); //Тянем данные по юзеру
        echo $node->id;

        //Решение проблемы
        //Старый ID куратора в ОСС oss->user_id
        // - Zhantassova - 2211,
                // - Tyultebayeva - 2215,
                // - Daukkenova - 2129
                // - Ashimova - 2270,
                // - Kobeleva - 2213,
                // - Kalkenbayeva - 2197,
                // - Ismuratova - 2221,
        //$old_curator_user_id = 2213; // Старый Kobeleva oss->user_id
        $old_curator_user_id = 2213; // Старый Kobeleva oss->user_id


        //Старый ID куратора в ОСС oss->id
        // - Zhantassova - 2129,
                // - Tyultebayeva - 2133,
                // - Daukkenova - 2047
                // - Ashimova - 2188,
                // - Kobeleva - 2131,
                // - Kalkenbayeva - 2115,
                // - Ismuratova - 2139,
        //$old_oss_curator_id = 2131; //Старый ID куратора в ОСС oss->id
        $old_oss_curator_id = 2131; //Старый ID куратора в ОСС oss->id

//

        // Новый ID куратора в ОСС oss->user_id
        // - Zhantassova - 2408,
                // - Tyultebayeva - 2409,
                // - Daukkenova - 2410,
                // - Ashimova - 2411,
                // - Kobeleva - 2412,
                // - Kalkenbayeva - 2413,
                // - Ismuratova - 2414,
        //$new_curator_user_id = 2412; //Новый Kobeleva user_id oss->user_id
        $new_curator_user_id = 2412; //Новый Kobeleva user_id oss->user_id
//
        $node = Node::where('user_id', $new_curator_user_id)->first(); //Тянем данные по Daukenova
        if(isset($node) && !empty($node)){
            $new_oss_curator_id = $node->id; //новый id пользователя

            $node_list = Node::where('parent_id', $old_oss_curator_id)->get(); //Тянем данные по связанным профилям
            foreach($node_list as $upd_list_item){
                $oss_id = $upd_list_item->id;
                $node_upd = Node::where('id', $oss_id)->first();
                $node_upd->parent_id = $new_oss_curator_id;
                $node_upd->save();
            }
        }
    }

    //Данные о записях в дереве OSS
    public function osstreedata()
    {
        $user_id = 2412; //новая айдишка
        $node = Node::where('user_id', $user_id)->first();
        if(isset($node) && !empty($node)){
            echo 'USER ID - '.$user_id.' - OSS ID '.$node->id.'<hr>';

            //$node_list = Node::where('parent_id', $user_id)->get();//проверка по новому айди
            $node_list = Node::where('parent_id', $node->id)->get();//проверка по новому айди
            foreach($node_list as $nodes){
                echo $nodes->id.' --- '.$nodes->user_id.'<br>';
            }
        }
        else{
            echo 'EMPTY - '.$user_id;
        }
    }

    //Данные о записях в дереве OSS
    public function osstreedataupdate()
    {
        $old_user_id = 2213; //старый ID пользователя
        $new_user_id = 2412; //новый ID пользователя
        //удаляем запись новую
        //переписываем данные юзерам которые уже переписаны на новую айдишку

        $node = Node::where('user_id', $old_user_id)->first();
        if(isset($node) && !empty($node)){
            $node->user_id = $new_user_id;
            $node->save();
            echo 'ITEM UPDATE<br>';
        }
        else{
            echo 'EMPTY - '.$old_user_id;
        }
        //проверка обновления
        $new_node = Node::where('user_id', $new_user_id)->first();
        print_r($new_node);
    }

    public function osstreedatainfo()
    {
        $old_user_id = 2213; //старый ID пользователя
        $new_user_id = 2412; //новый ID пользователя

        //проверяем есть ли старая запись
        $check_node_v1 = Node::where('user_id', $old_user_id)->first();

        //проверяем есть ли новая запись
        $check_node_v2 = Node::where('user_id', $new_user_id)->first();

        //проверяем есть ли дочерние записи для старой айдишки
        if(isset($check_node_v1) && !empty($check_node_v1)){
            $check_node_v3 = Node::where('parent_id', $check_node_v1->id)->get();
            $id_1 = $check_node_v1->id;
        }
        else{
            $check_node_v3 = '';
            $id_1 = '';
        }

        //проверяем есть ли дочерние записи для новой айдишки
        if(isset($check_node_v2) && !empty($check_node_v2)){
            $check_node_v4 = Node::where('parent_id', $check_node_v2->id)->get();
            $id_2 = $check_node_v2->id;
        }
        else{
            $check_node_v4 = '';
            $id_2 = '';
        }


        echo '<hr> старая запись - 1 - <br>';
        echo $id_1;
        echo '<hr> новая запись - 2 - <br>';
        echo $id_2;
        echo '<hr> дочерние элементы для старой записи - 3 - <br>';
        print_r($check_node_v3);
        echo '<hr>дочерние элементы для новой записи - 4 - <br>';
        print_r($check_node_v4);

    }

    public function osstreedataempty()
    {

        //  ID---            OLD     NEW
        // - Zhantassova --- 2211 - 2408, (2129 - старая нода)

        // - Tyultebayeva --- 2215 - 2409, (2133 - старая нода)
        // - Daukkenova - --- 2129 - 2410, (2047 - старая нода)
        // - Ashimova - --- 2270 - 2411, (2188 - старая нода)
        // - Kobeleva - --- 2213 - 2412, (2131 - старая нода)
        // - Kalkenbayeva - --- 2197 - 2413, (2115 - старая нода)
        // - Ismuratova - --- 2221 - 2414, (2139 - старая нода)


        $old_user_id = 2213; //старый ID пользователя
        $new_user_id = 2412; //новый ID пользователя

        $tree_node_id = 2131;
        $parent_id = 2074;
        $invited_cnt = 0;
        $total_cnt = 0;
        $is_active = 1;
        $created_at = '2021-03-08 18:55:41';
        $updated_at = '2021-03-08 18:55:41';

        $check_node_v2 = Node::where('user_id', $new_user_id)->first();
        //если есть новые данные - обрабатываем их
        if(isset($check_node_v2) && !empty($check_node_v2)){
            //проходимся по списку дочерних пользователей нового пользователя - обновляем parent_id
            $new_v2_id = $check_node_v2->id;
            $child_nodes_list = Node::where('parent_id', $new_v2_id)->get();
            foreach($child_nodes_list as $child_node){
                $id_for_upd = $child_node->id;
                $update_node_data = Node::where('id', $id_for_upd)->first();
                $update_node_data->parent_id = $tree_node_id;
                $update_node_data->save();
            }

            //удаляем новый профиль
            $oss_new_delete_v1 = Node::where('user_id', $new_user_id)->delete();
        }

        //проверяем есть ли старая запись
        $check_node_v1 = Node::where('user_id', $old_user_id)->first();

            if(!isset($check_node_v1) || empty($check_node_v1)){
                //если нету - пишем заново
                $new_tree_data = new Node();
                $new_tree_data->id = $tree_node_id;
                $new_tree_data->parent_id = $parent_id;
                $new_tree_data->user_id = $new_user_id;
                $new_tree_data->invited_cnt = $invited_cnt;
                $new_tree_data->total_cnt = $total_cnt;
                $new_tree_data->is_active = $is_active;
                $new_tree_data->created_at = $created_at;
                $new_tree_data->updated_at = $updated_at;
                $new_tree_data->save();
            }
            else{
                //если есть - обновляем до требуемого уровня
                $check_node_v1->user_id = $new_user_id;
                $check_node_v1->save();
            }

    }

    public function ossfixrequest()
    {
        $user_id = 2366;
        $data_line = BePartnerRequest::where('user_id', $user_id)->first();
        print_r($data_line);
        echo '<hr>';
        $data_user = User::where('id', $user_id)->first();
        print_r($data_user);
        echo '<hr>'; //проверка начислений бонусов по бинару вверх от 2366
        $data_user = BonusBinaryPoints::where('from_user_id', $user_id)->get();
        print_r($data_user);
    }

    //Обработка зависшего запроса
    //Шаг 1 - убираем бейдж
    public function badgeremove()
    {
        // 1 - badges - убираем бейдж be_partner_request
        Badge::where('menu_id', 3)->where('user_id', 1)->where('badgable_type', 'be_partner_requests')->delete();
    }

    //Шаг 2 - убираем и обновляем данные запроса -- по надобности
    public function requestremove()
    {
        $user_id = 2366; //ID пользователя, чья заявка зависла

        // 1 - убираем начисленные баллы FROM binary_id = 6292 -> delete
//        $bonus_data = BonusBinaryPoints::where('from_user_id', $user_id)->first();
//        $node_update = $bonus_data->to_node_id;
//        BonusBinaryPoints::where('from_user_id', $user_id)->delete();

        // 2 - pts_left, pts_missed_left = 0 -> binary_infos
//        $upd_node_infos = BinaryTreeNodeInfo::where('node_id', $node_update)->first();
//        $upd_node_infos->pts_left = 0;
//        $upd_node_infos->pts_missed_left = 0;
//        $upd_node_infos->save();

        // 3 - be_partner_request - обновляем заявку - проставляем статус и дату активации
//        $user_be_partner_request = BePartnerRequest::where('user_id', $user_id)->first();
//        $user_be_partner_request->is_confirmed = 1;
//        $user_be_partner_request->save();
    }

    //Шаг 3 - убираем лишний пакет по всей сетке вверх
    public function packagesrestore(Request $request, $id, TreeService $tree)
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
        $initiatorNode  = Node::where('user_id', $id)->first();
        $initiatorNode  = BinaryTreeNode::where('id', $node_id)->first();

        //Получаем сетку пользователей под заданным юзером
        $node = Node::with('user')->where('user_id', $id)->first();
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


                        //Пишем здесь данные пользователя
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
                            $packs_left = $packs_left - 1;

                            //Обновляем запись
                            $binary_points->packs_left = $packs_left;
                            $binary_points->save();
                        }
                        if($side == 2){
                            //RIGHT
                            $packs_right = $packs_right - 1;


                            //Обновляем запись
                            $binary_points->packs_right = $packs_right;
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
                            }
                        }
                        if($side == 1){
                            $side_name = 'LEFT';
                        }
                        if($side == 2){
                            $side_name = 'RIGHT';
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
                    $packs_left = $packs_left - 1;

                    //Обновляем запись
                    $binary_points->packs_left = $packs_left;
                    $binary_points->save();
                }
                if($team_id == 2){
                    //RIGHT
                    //$packs_right = $packs_right + 1;
                    $packs_right = $packs_right - 1;

                    //Обновляем запись
                    $binary_points->packs_right = $packs_right;
                    $binary_points->save();
                }
            }
            $level++;
        }
    }

    //Шаг 4 - добавляем бейджи о новостях
    public function addbadges()
    {
        $user_id = 2372; //ID пользователя, чья заявка зависла

        $badges_1 = new Badge();
        $badges_1->menu_id = 47;
        $badges_1->user_id = $user_id;
        $badges_1->badgable_id = 4;
        $badges_1->badgable_type = 'news';
        $badges_1->save();

        $badges_2 = new Badge();
        $badges_2->menu_id = 50;
        $badges_2->user_id = $user_id;
        $badges_2->badgable_id = 5;
        $badges_2->badgable_type = 'documents';
        $badges_2->save();

        $badges_3 = new Badge();
        $badges_3->menu_id = 48;
        $badges_3->user_id = $user_id;
        $badges_3->badgable_id = 6;
        $badges_3->badgable_type = 'events';
        $badges_3->save();

        $badges_4 = new Badge();
        $badges_4->menu_id = 49;
        $badges_4->user_id = $user_id;
        $badges_4->badgable_id = 2;
        $badges_4->badgable_type = 'promos';
        $badges_4->save();
    }

        //Добавление ссылки на раздел ордера
    public function addorders()
    {
        $orders = new Menu();
        $orders->parent_id = 1;
        $orders->cabinet_id = 1;
        $orders->link = '/admin/orders';
        $orders->name = 'Запросы на вывод';
        $orders->icon = 'fa fa-lg fa-fw fa-check-square-o';
        $orders->save();
    }

    //Добавление статусов о наличии меток для старых новостей
    public function newsbadgable()
    {
        //Обрабатываем новости SIB
        $sib_news = News::query()->get();
        foreach($sib_news as $sib_item){
            $sib_id = $sib_item->id;
            $sib_news = News::query()->where('id', '=', $sib_id)->first();
            $sib_news->is_badgable = 2;
            $sib_news->save();
        }

        //Обрабатываем новости OSS
        $oss_news = OssNews::query()->get();
        foreach($oss_news as $oss_item){
            $oss_id = $oss_item->id;
            $oss_news = OssNews::query()->where('id', '=', $oss_id)->first();
            $oss_news->is_badgable = 2;
            $oss_news->save();
        }

        //тест вывод после обновления
        echo 'SIB NEWS';
        echo '<br>';
        print_r($sib_news);
        echo '<hr>';
        echo 'OSS NEWS';
        echo '<br>';
        print_r($oss_news);
    }
}
