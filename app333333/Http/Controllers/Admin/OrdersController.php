<?php

namespace App\Http\Controllers\Admin;

use App\ExpectedWallet;
use App\Models\Requisition;
use App\WithdrawOrders;
use App\WithdrawOrdersStatus;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class OrdersController extends AdminController
{
    public function index()
    {
        $id = 1;
        try{
            $order_status = WithdrawOrdersStatus::query()->where('id', '=', $id)->first();
            if(empty($order_status) OR $order_status == NULL OR !isset($order_status)){
                $order_status = new WithdrawOrdersStatus();
                $order_status->id = 1;
                $order_status->status = 0;
                $order_status->save();

                $withdraw_status = 0;
            }
            else{
                $withdraw_status = $order_status->status;
            }
        }catch(\Exception $e){
                $withdraw_status = 0;
        }

        //$orders = WithdrawOrders::orderBy('status')->get();
        $orders = WithdrawOrders::query()->join('users', 'withdraw_orders.user_id', '=', 'users.id')
            //->select('users.name', 'users.last_name', 'withdraw_orders.summ', 'withdraw_orders.card', 'withdraw_orders.bank', 'withdraw_orders.name', 'withdraw_orders.status', 'withdraw_orders.comment')
            ->select('*', 'withdraw_orders.id as order_id', 'withdraw_orders.name as order_user_name', 'withdraw_orders.created_at as order_created', 'withdraw_orders.updated_at as order_updated', 'users.name')
            ->orderBy('withdraw_orders.status')
            ->get();

        //print_r($orders);
        return view('admin.orders.index', compact('orders', 'withdraw_status'));
    }

    /**
     * Update by Ajax
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $requisition = Requisition::findOrFail($id);
            $requisition->update($request->all());

            return Response::json([
                'message' => 'done',
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * View order data
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function view(Request $request, $id)
    {
        $data = WithdrawOrders::query()->where('withdraw_orders.id', '=', $id)->join('users', 'withdraw_orders.user_id', '=', 'users.id')
            ->select('*', 'withdraw_orders.id')
            ->orderBy('withdraw_orders.status')
            ->get();
        return view('admin.orders.view', compact('data'));
    }

    /**
     * Update order status
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function statusupdate(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        //Подтверждение запроса на вывод средств
        if($status == 1){
            try {
                //Тащим ордер по ID
                $order_data = WithdrawOrders::findOrFail($id);
                $order_id= $order_data->id;
                $order_withdraw_user = $order_data->user_id;
                $order_withdraw_full_summ_position = $order_data->summ;
                $order_withdraw_position = $order_data->upd_summ;
                $order_withdraw_date = $order_data->created_at;

                //Проверяем - не изменялся ли лимит по данной заявке (не была ли списана часть средств за счет другой проводки)
                $order_withdraw_summ = $order_data->upd_summ;

                //Выбираем все доступные к выводу бонусы пользователя
                $full_order_data = ExpectedWallet::query()->where('user_id', '=', $order_withdraw_user)->where('status', '=', '1')->where('expected', '>', 'upd_expected')->orderByDesc('upd_expected')->get();
                if(!isset($full_order_data) OR empty($full_order_data)){
                    $full_order_data = ExpectedWallet::query()->where('user_id', '=', $order_withdraw_user)->where('status', '=', '1')->orderByDesc('upd_expected')->get();
                }

                //Получаем общую сумму средств
                $order_summ = 0;
                foreach($full_order_data as $order_item){
                    $order_summ = $order_summ + $order_item->expected;
                }

                //Минусуем сумму вывода
                $withdraw_summ = $order_summ - $order_withdraw_summ; //остаток = общая сумма - сумма на вывод
                if($order_withdraw_summ > 0 && $order_withdraw_summ < 70001){

                    foreach($full_order_data as $order_item){
                        $expected_item_id = $order_item->id;
                        $expected_summ_full = $order_item->expected;
                        $expected_summ_upd = $order_item->upd_expected;

                        //Определяем использовался ли лимит средств
                        if($expected_summ_upd > 0 && $expected_summ_upd < $expected_summ_full){
                            $exp_tek_summ = $expected_summ_upd;
                        }
                        else{
                            $exp_tek_summ = $expected_summ_full;
                        }

                        //Тащим ордер по ID
                        $order_data = WithdrawOrders::findOrFail($id);
                        $order_id= $order_data->id;
                        $order_withdraw_user = $order_data->user_id;
                        $order_withdraw_full_summ_position = $order_data->summ;
                        $order_withdraw_position = $order_data->upd_summ;
                        $order_withdraw_date = $order_data->created_at;

                        //Проверяем - не изменялся ли лимит по данной заявке (не была ли списана часть средств за счет другой проводки)
                        $order_withdraw_summ = $order_data->upd_summ;

                        //Если лимит запроса на вывод не заполнен
                        if($order_withdraw_summ > 0){
                            //Если сумма запрошенная на вывод больше суммы проводки
                            if($order_withdraw_summ > $exp_tek_summ){
                                //Проганяем по циклу пока не закроем весь лимит
                                while($order_withdraw_summ > 0){
                                    //Получаем операцию с открытым лимитом
                                    $full_order_data_update = ExpectedWallet::query()->where('user_id', '=', $order_withdraw_user)->where('status', '=', '1')->orderByDesc('upd_expected')->first();
                                    // если больше
                                    if($order_withdraw_summ > $full_order_data_update->upd_expected){
                                        $order_withdraw_summ_upd = $order_withdraw_summ - $full_order_data_update->upd_expected;
                                        $res_exp = 0;
                                        $status = 2;
                                    }
                                    // если меньше
                                    if($order_withdraw_summ < $full_order_data_update->upd_expected){
                                        $res_exp = $full_order_data_update->upd_expected - $order_withdraw_summ;
                                        $order_withdraw_summ_upd = 0;
                                        $status = 1;
                                    }
                                    // если равно
                                    if($order_withdraw_summ == $full_order_data_update->upd_expected){
                                        $order_withdraw_summ_upd = 0;
                                        $res_exp = 0;
                                        $status = 2;
                                    }


                                    //Обновляем данные по нашей проводке в expected_wallets
                                    $full_order_data_update->upd_expected = $res_exp;
                                    $full_order_data_update->status = $status;
                                    $full_order_data_update->save();

                                    //Обновляем данные по нашему ордеру в orders
                                    $order_data->upd_summ = $order_withdraw_summ_upd;
                                    if($order_withdraw_summ_upd == 0){
                                        $order_data->status = 1;
                                    }
                                    else{
                                        $order_data->status = 0;
                                    }

                                    if(!empty($order_data->comment)){
                                        $comm = $order_data->comment;
                                    }
                                    else{
                                        $comm = '';
                                    }

                                    //$order_data->comment = $comm.' | '.$order_withdraw_summ_upd;
                                    $order_data->save();
                                    $res_exp = NULL;
                                    //Обновляем данные в переменных для отслеживания перемешения по циклу
                                    $order_data = WithdrawOrders::findOrFail($id);
                                    $order_withdraw_summ = $order_data->upd_summ;
                                    if($order_withdraw_summ == 0){
                                        exit;
                                    }
                                }
                            }
                            //Если сумма запрошенная на вывод ниже суммы проводки
                            if($order_withdraw_summ < $exp_tek_summ){
                                //для отчетности - дублируем значение суммы
                                $copy_summ = $order_withdraw_summ;

                                $order_expected = $exp_tek_summ - $order_withdraw_summ;
                                $order_withdraw_summ = 0;

                                //Обновляем запись
                                $update_expected_data = ExpectedWallet::query()->where('id', '=', $expected_item_id)->where('status', '=', '1')->first();
                                $update_expected_data->upd_expected = $order_expected;
                                if($order_expected == 0){
                                    $update_expected_data->status = 2;
                                }
                                else{
                                    $update_expected_data->status = 1;
                                }

                                $update_expected_data->save();

                                //Обновляем данные ордера
                                $update_data = WithdrawOrders::findOrFail($id);
                                $update_data->status = 1;
                                //Если есть значение - обновляем, если нету - пишем
                                $update_data->upd_summ = $update_data->upd_summ - $copy_summ;
                                $update_data->save();

                                //проверяем состояние ордера после обновления
                                $update_data = WithdrawOrders::findOrFail($id);
                                if( $update_data->upd_summ == 0){
                                    break;
                                }
                            }
                            //Если сумма запрошенная на вывод равна сумме проводки
                            if($order_withdraw_summ == $exp_tek_summ){
                                //для отчетности - дублируем значение суммы
                                $copy_summ = $order_withdraw_summ;

                                $order_expected = $exp_tek_summ - $order_withdraw_summ;
                                $order_withdraw_summ = 0;

                                //Обновляем запись
                                $update_expected_data = ExpectedWallet::query()->where('id', '=', $expected_item_id)->where('status', '=', '1')->first();
                                $update_expected_data->upd_expected = $order_expected;
                                if($order_expected == 0 ){
                                    $update_expected_data->status = 2;
                                }
                                else{
                                    $update_expected_data->status = 1;
                                }
                                $update_expected_data->save();

                                //Обновляем данные ордера
                                $update_data = WithdrawOrders::findOrFail($id);
                                $update_data->status = 1;
                                //Если есть значение - обновляем, если нету - пишем
                                $update_data->upd_summ = $update_data->upd_summ - $copy_summ;
                                $update_data->save();

                                //проверяем состояние ордера после обновления
                                $update_data = WithdrawOrders::findOrFail($id);
                                if( $update_data->upd_summ == 0){
                                    break;
                                }
                            }
                            
                            return Response::json([
                                'message' => 'done',
                            ], 200);
                        }
                        else{
                            return Response::json([
                                'message' => 'done',
                            ], 200);
                        }
                    }
                }
                else{
                    //В случае неверного значения общей суммы доступных средств,
                    return Response::json([
                        'message' => 'error',
                    ], 200);
                }


            } catch (\Exception $e) {
                return Response::json([
                    'error' => $e->getMessage(),
                ], 200);
            }
        }
        //Отмена запроса на вывод средств
        if($status == 2){
            try {
                $order = WithdrawOrders::findOrFail($id);
                $order->status = 2;
                $order->save();

                return Response::json([
                    'message' => 'done',
                ], 200);

            } catch (\Exception $e) {
                return Response::json([
                    'error' => $e->getMessage(),
                ], 200);
            }
        }
    }

    /**
     * Update by admin
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function orderrewrite(Request $request)
    {
        $id = $request->id;
        $summ = $request->summ;
        $card = $request->card;
        $bank = $request->bank;
        $name = $request->holder;
        $comment = $request->comment;

        try {
            $order = WithdrawOrders::findOrFail($id);
            $order->summ = $summ;
            $order->card = $card;
            $order->bank = $bank;
            $order->name = $name;
            $order->comment = $comment;
            $order->save();

            return Response::json([
                'message' => 'done',
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Update by admin
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function withdrawstatus(Request $request)
    {
        $id = 1;
        try {
            $order_status = WithdrawOrdersStatus::query()->where('id', '=', $id)->first();
            if(empty($order_status) OR $order_status == NULL OR !isset($order_status)){
                $order_status = new WithdrawOrdersStatus();
                $order_status->id = 1;
                $order_status->status = $request->status;
                $order_status->save();
            }
            else{
                $order_status->status = $request->status;
                $order_status->save();
            }
            return Response::json([
                'message' => 'done',
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
