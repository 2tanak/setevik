@extends('layouts.app')

@section('title', 'Запросы на вывод средств')

@section('content')
    <div class="row">
        <div class="well" id="wallet">
            <h2>Финансы / Запросы на вывод средств</h2>
            <!-- #BONUSES -->
            <div>
                <div class="table-responsive">
                    <div class="pull-right text-center">
                        <div class="orders_status_bar">
                            <?php
                                if(!empty($withdraw_status)){
                                    if($withdraw_status == 1){
                                        $withdraw_text = 'Принимаются';
                                    }
                                    else{
                                        $withdraw_text = 'Не принимаются';
                                    }
                                }
                                else{
                                    $withdraw_status = 0;
                                    $withdraw_text = 'Не принимаются';
                                }
                            ?>
                            <h4>В текущий момент запросы на вывод средств: <br><?php echo $withdraw_text; ?></h4>
                            <br>
                                <?php  if($withdraw_status == 1){ ?>
                                    <a href="javascript:void(0);" data-target="#modalWithdrawStart" onclick="requestedWithdrawStop()" class="orders_status_btn orders_delete">
                                        <i class="fa fa-stop"></i> ОТКЛ
                                    </a>
                                <?php } else{?>
                                    <a href="javascript:void(0);" data-target="#modalWithdrawStop" onclick="requestedWithdrawStart()" class="orders_status_btn orders_success">
                                        <i class="fa fa-play"></i> ВКЛ
                                    </a>
                                <?php }?>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr class="no-skin">
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Банк</th>
                                <th>Дата создания</th>
                                <th>Статус</th>
                                <th>Операции</th>
                            </tr>
                            <?php
                            if(!empty($orders)){
                                foreach($orders as $order){
                                    //Статус запроса
                                    $status_code = $order['status'];
                                    if($status_code == 0){
                                        $status = 'Новый запрос';
                                        $status_class = 'status_label_new';
                                    }
                                    if($status_code == 1){
                                        $status = 'Выполнен';
                                        $status_class = 'status_label_success';
                                    }
                                    if($status_code == 2){
                                        $status = 'Отклонен';
                                        $status_class = 'status_label_cancel';
                                    }
                                ?>
                                    <tr>
                                        <th>
                                            <?php echo $order['order_id'];?>
                                        </th>
                                        <td>
                                            <?php echo $order['name'].' '.$order['last_name'];?>
                                        </td>
                                        <td>
                                            <?php echo $order['summ'];?>$
                                        </td>
                                        <td>
                                            <?php echo $order['bank'];?>
                                        </td>
                                        <th>
                                            <?php echo $order['order_created'];?>
                                        </th>
                                        <td>
                                            <label class="status_label <?php echo $status_class;?>"><?php echo $status;?></label>
                                        </td>
                                        <td>
                                            <?php if($status_code == 0){?>
                                            <a href="javascript:void(0);" data-target="#modalOrderView" onclick="requestedOrderView(<?php echo $order['order_id'];?>)" data-toggle="modal" class="orders_btn orders_view">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-target="#modalOrderSuccess"  onclick="requestedOrderSuccess(<?php echo $order['order_id'];?>)" data-toggle="modal"class="orders_btn orders_success">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-target="#modalOrderDelete"  onclick="requestedOrderDelete(<?php echo $order['order_id'];?>)" data-toggle="modal" class="orders_btn orders_delete">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                            <a href="javascript:void(0);"data-target="#modalOrderUpdate" onclick="requestedOrderUpdate(<?php echo $order['order_id'];?>)" data-toggle="modal" class="orders_btn orders_update">
                                                <i class="fa fa-cog"></i>
                                            </a>
                                            <?php }
                                                else{?>
                                                <a href="javascript:void(0);" data-target="#modalOrderView"  onclick="requestedOrderView(<?php echo $order['order_id'];?>)" data-toggle="modal" class="orders_btn orders_view">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                            else{?>
                                <h2>В текущий момент - запросов нету</h2>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clb"></div>
        </div>
    </div>
@endsection

<!-- Модалка с данными о запросе на вывод -->
<?php  foreach($orders as $order){
    //Статус запроса
    $status_code = $order['status'];
    if($status_code == 0){
        $status = 'Новый запрос';
        $status_class = 'status_label_new';
    }
    if($status_code == 1){
        $status = 'Выполнен';
        $status_class = 'status_label_success';
    }
    if($status_code == 2){
        $status = 'Отклонен';
        $status_class = 'status_label_cancel';
    }
?>
<div id="modalview_<?php echo $order['order_id'];?>" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 30px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Датели запроса №<?php echo $order['order_id'];?></h4>
            </div>
            <div class="modal-body text-center" id="success_form">
                <table class="table table-striped table-hover">
                    <tbody>
                        <tr class="no-skin">
{{--                            <th>Тип данных</th>--}}
{{--                            <th>Значение</th>--}}
                        </tr>
                        <tr>
                            <td>
                                Имя пользователя:
                            </td>
                            <td>
                                <?php echo $order['name'].' '.$order['last_name'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Запрашиваемая сумма:
                            </td>
                            <td>
                                <?php echo $order['summ'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Номер карты:
                            </td>
                            <td>
                                <?php echo $order['card'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Банк получателя:
                            </td>
                            <td>
                                <?php echo $order['bank'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Имя владельца карты:
                            </td>
                            <td>
                                <?php echo $order['order_user_name'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Статус запроса:
                            </td>
                            <td>
                                <label class="status_label <?php echo $status_class;?>"><?php echo $status;?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Комментарий:
                            </td>
                            <td>
                                <?php echo $order['comment'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Запрос создан:
                            </td>
                            <td>
                                <?php echo $order['order_created'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Запрос обновлен:
                            </td>
                            <td>
                                <?php echo $order['order_updated'];?>
                            </td>
                        </tr>
                    </tbody>
                </table>
{{--                Если запрос новый - даем доступ к управлению данным запросом --}}
                <?php if($status_code == 0){?>
                    <a href="javascript:void(0);" data-target="#modalUserWithdraw" onclick="requestedOrderUpdateCancel(<?php echo $order['order_id'];?>)" data-toggle="modal" class="btn btn-warning">Отменить вывод</a>
                    <a href="javascript:void(0);" data-target="#modalUserWithdraw" onclick="requestedOrderUpdateSuccess(<?php echo $order['order_id'];?>)" data-toggle="modal" class="btn btn-success">Подтвердить вывод</a>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<!-- Модалка с редактированием данных ордера -->
<div id="modalupdate_<?php echo $order['order_id'];?>" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 30px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Обновление запроса №<?php echo $order['order_id'];?></h4>
            </div>
            <div class="modal-body text-center" id="success_form">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr class="no-skin">
                        {{--                            <th>Тип данных</th>--}}
                        {{--                            <th>Значение</th>--}}
                    </tr>
                    <tr>
                        <td>
                            Имя пользователя:
                        </td>
                        <td>
                            <?php echo $order['name'].' '.$order['last_name'];?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Запрашиваемая сумма:
                        </td>
                        <td>
                            <input type="text" class="form-control" id="order_<?php echo $order['order_id'];?>_summ" value="<?php echo $order['summ'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Номер карты:
                        </td>
                        <td>
                             <input type="text" class="form-control" id="order_<?php echo $order['order_id'];?>_card" value="<?php echo $order['card'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Банк получателя:
                        </td>
                        <td>
                            <input type="text" class="form-control" id="order_<?php echo $order['order_id'];?>_bank" value="<?php echo $order['bank'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Имя владельца карты:
                        </td>
                        <td>
                            <input type="text" class="form-control" id="order_<?php echo $order['order_id'];?>_holder" value="<?php echo $order['order_user_name'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Комментарий:
                        </td>
                        <td>
                            <textarea rows="3" class="form-control" style="width: 200px;" id="order_<?php echo $order['order_id'];?>_comment"><?php echo $order['comment'];?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Запрос создан:
                        </td>
                        <td>
                            <?php echo $order['order_created'];?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Запрос обновлен:
                        </td>
                        <td>
                            <?php echo $order['order_updated'];?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                {{--                Если запрос новый - даем доступ к управлению данным запросом --}}
                <?php if($status_code == 0){?>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" onclick="requestedOrderRewrite(<?php echo $order['order_id'];?>)" data-toggle="modal" class="btn btn_update_save">Сохранить данные</a>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<!-- Модалка с подтверждением запроса на вывод -->
<div id="modalsuccess_<?php echo $order['order_id'];?>" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 30px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Подтверждение запроса на вывод</h4>
            </div>
            <div class="modal-body text-center" id="success_form">
                <h3>Вы уверены что хотите подтвердить данный запрос на вывод средств?</h3>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" data-dismiss="modal" class="btn btn-warning">Нет</a>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" onclick="requestedOrderUpdateSuccess(<?php echo $order['order_id'];?>)" data-toggle="modal" class="btn btn-success">Да</a>
            </div>
        </div>
    </div>
</div>

<!-- Модалка с удалением запроса на вывод -->
<div id="modaldelete_<?php echo $order['order_id'];?>" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 30px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Отклонение запроса на вывод</h4>
            </div>
            <div class="modal-body text-center" id="success_cancel_form_<?php echo $order['order_id'];?>">
                <h3>Вы уверены что хотите отклонить данный запрос на вывод средств?</h3>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" data-dismiss="modal" class="btn btn-warning">Нет</a>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" onclick="requestedOrderUpdateCancel(<?php echo $order['order_id'];?>)" data-toggle="modal" class="btn btn-danger">Да</a>
            </div>
            <div class="modal-body text-center" id="success_cancel_message_<?php echo $order['order_id'];?>" style="display:none;">
                <h3>Ваша заявка успешно отправлена в обработку</h3>
                <a href="javascript:void(0);" data-target="#modalUserWithdraw" data-dismiss="modal" class="btn btn-warning">Закрыть окно</a>
            </div>
        </div>
    </div>
</div>
<?php }?>

<style>
    .status_label{
        color: #ffffff;
        font-size: 12px;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 10px;
        padding-right: 10px;
        border-radius: 3px;
    }
    .status_label_new{
        background-color: #9a0325;
    }
    .status_label_in_process{
        background-color: #b9662b;
    }
    .status_label_cancel{
        background-color: #0d0f12;
    }
    .status_label_success{
        background-color: #0aa66e;
    }
    .orders_btn{
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_btn:hover{
        background-color: #000000;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_status_btn{
        color: #ffffff;
        margin-right: 15px;
        padding-left: 15px!important;
        padding-right: 15px!important;
        padding-top: 7px!important;
        padding-bottom: 7px!important;
        border-radius: 3px;
    }
    .orders_status_btn:hover{
        background-color: #000000;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_view{
        background-color: #0c7cd5;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_update{
        background-color: #b9662b;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_success{
        background-color: #0aa66e;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .orders_delete{
        background-color: #9a0325;
        color: #ffffff;
        padding: 5px;
        border-radius: 3px;
    }
    .btn_update_save{
        background-color: #0d0f12;
        color: #ffffff;
    }
    .btn_update_save:hover{
        background-color: #2b363c;
        color: #ffffff!important;
    }
</style>
