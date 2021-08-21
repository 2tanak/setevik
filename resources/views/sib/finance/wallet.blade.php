@extends('layouts.app')

@section('title', 'Кошелёк')
<?php
use Illuminate\Support\Facades\Auth;
?>
{{--@section('content')--}}
{{--    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">--}}
{{--        <div class="lockscreen animated flipInY">--}}
{{--            <div class="padding-20 text-center">--}}
{{--                <img src="/img/maintenance.png" class="img-responsive" style="margin: 0 auto;" alt="log">--}}
{{--            </div>--}}
{{--            <br>--}}
{{--            <div class="row">--}}

{{--                <div class="padding-20 text-center">--}}
{{--                    <p>123 Упс) Мы ещё здесь работаем) <br>--}}
{{--                        Страница находится в разработке и уже скоро будет доступна.</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@section('content')
    <div class="row">
        <div class="well" id="wallet">
            <h2>Финансы / Кошелёк</h2>
            <!-- #BONUSES -->
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr class="no-skin">
                            <th></th>
                            <th>Заработано в системе</th>
                            <th>Ожидается к выплате</th>
                            <th>Доступно к выводу</th>
                        </tr>
                        <?php
                        //Считаем всю сумму по столбцу
                        $full_summ_earned = 0; //Заработано всего
                        $full_summ_expected = 0; //Ожидается к выплате
                        $full_summ_available = 0; //Доступно к выводу

                        foreach($wallets as $wallet){?>
                        <tr>
                            <th>
                                <?php
                                    echo $wallet->bonus->name;
                                ?>
                            </th>
                            <td>
                                <?php

                                    //красная зона
                                    $all_red_bonuses = 0;
                                    if($wallet->bonus->id == 5){
                                        foreach($expected_wallets_all as $red_bonus){
                                            if($red_bonus->bonus_id == $wallet->bonus->id){
                                                $all_red_bonuses = $all_red_bonuses + $red_bonus->red_bonus_expected;
                                            }
                                        }
                                        echo $wallet->earned;
                                        echo ' / ';
                                        ?>
                                            <span style="color: darkred; font-size: 12px;"><?php echo $all_red_bonuses;?></span>
                                        <?php
                                    }
                                    else{
                                        echo $wallet->earned;
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                //Считаем что у нас висит в ожидании к выплате
                                //Текущая сумма средств ожидающих выплаты через 14 дней
                                echo $wallet->expected;

                                //Суммируем бонусы ожидающие выплаты через 14 дней
                                $full_summ_expected = $full_summ_expected + $wallet->expected;
                                ?>
                            </td>
                            <td>
                                <?php echo $wallet->available;?>
                            </td>
                        </tr>
                        <?php
                        //Суммируем остальные значения - заработано в системе и доступно к выводу
                        $full_summ_earned = $full_summ_earned +  $wallet->earned;
                        $full_summ_available = $full_summ_available + $wallet->available;
                        if(empty($full_summ_earned)){
                            $full_summ_earned = 0;
                        }
                        if(empty($full_summ_expected)){
                            $full_summ_expected = 0;
                        }
                        if(empty($full_summ_available)){
                            $full_summ_available = 0;
                        }
                        }?>
                        <tr>
                            <th>ИТОГО</th>
                            <td>
                                <?php echo $full_summ_earned; ?> (выведено: <span id="withdraw_success"><?php echo $success_summ;?></span>)
                            </td>
                            <td>
                                <?php echo $full_summ_expected;  ?>
                            </td>
                            <td id="withdraw_info" data-full="<?php echo $full_summ_available; ?>" order-limit="<?php echo $full_summ_available - $new_summ; ?>">
                                <?php
                                    if(isset($new_summ) && !empty($new_summ)){
                                        $end_full_summ = $full_summ_available - $new_summ;
                                    }
                                    else{
                                        $end_full_summ = $full_summ_available;
                                    }

                                    echo $end_full_summ;
                                ?> (запрошено: <span id="withdraw_waiting"><?php echo $new_summ;?></span>)
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="javascript:void(0);" data-target="#modalUserWithdraw" id="requestedWithdraw" onclick="requestedWithdraw()" data-toggle="modal" class="btn btn-success pull-right">Вывести средства</a>
            <div class="clb"></div>
        </div>

        <!-- #STATEMENTS -->
        <div class="well">
            <h2>История операций</h2>
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered statement">
                        <tbody>
                        <tr>
                            <th class="text-center">Дата</th>
                            <th class="text-center">Сальдо на начало</th>
                            <th class="text-center">Сумма операции</th>
                            <th class="text-center">Сальдо на конец</th>
                            <th class="text-center">Вид операции</th>
                            <th class="text-center">Инициатор</th>
                            <th class="text-center">Пакет</th>
                            <th class="text-center">Уровень</th>
                        </tr>

                        <?php
                            //Получаем значение сальдо за весь период
                            $max_saldo = 0;
                            $step = 1;
                            foreach($expected_wallets as $end_saldo){
                                $max_saldo = $max_saldo + $end_saldo->expected;
                            }

                            //Сальдо на начало и на конец месяца
                            $saldo_start = $max_saldo;
                            $saldo_end = $max_saldo;
                            foreach($expected_wallets as $expected){
                                //Получаем сальдо
                                if($expected->expected > 0){
                                    $saldo_stat = $saldo_end - $expected->expected;
                                    $class = 'profit';
                                }
                                else{
                                    $saldo_stat = $saldo_end - $expected->expected;
                                    $class = 'loss';
                                }

                                //Получаем название бонуса
                                    if(isset($expected->bonus->name) && !empty($expected->bonus->name)){
                                        $bonus_name = $expected->bonus->name;
                                    }
                                    else{
                                        $bonus_name = '---';
                                    }
                                //Инициатор
                                    if(isset($expected->customer->id) && !empty($expected->customer->id)){
                                        $customer_id = $expected->customer->id;
                                        $customer_name = $expected->customer->name.' '.$expected->customer->last_name;
                                    }
                                    else{
                                        $customer_id = '';
                                        $customer_name = '';
                                    }
                                //Продукт OSS
                                    if($expected->bonus_id == 8){
                                        if(!empty($expected->product_id)){
                                            foreach($products as $product){
                                                if($product->id == $expected->product_id){
                                                    $product_name = $product->name;
                                                    break;
                                                }
                                                else{
                                                    $product_name = '---';
                                                }
                                            }
                                        }
                                    }

                                //Продукт SIB
                                    if($expected->bonus_id == 5){
                                        if(!empty($expected->product_id)){
                                            foreach($packages as $package){
                                                if($package->id == $expected->product_id){
                                                    $product_name = $package->name;
                                                    break;
                                                }
                                                else{
                                                    $product_name = '---';
                                                }
                                            }
                                        }
                                    }

                                    //Определяем уровень пользователя
                                    //Если в проводке не указан уровень пользователя
                                        if(empty($expected->product_id) || $expected->product_id == 0 || $expected->product_id == NULL)
                                        {
                                            if(isset($customer_id) && !empty($customer_id) && $customer_id !== NULL){
                                                $level = 1; //Стартовый уровень
                                                while($level <= env('CLASSIC_TREE_MAX_DEPTH', 15))
                                                {
                                                    //проверяем существует ли такой уровень
                                                    if(isset($data_tree[$level])){
                                                        //Проверяем - есть ли на данном уровне требуемое значение
                                                        foreach($data_tree[$level] as $tree){
                                                            if(isset($tree->id) && !empty($tree->id) && $tree->id == $customer_id){
                                                                $user_level = $level;
                                                                $level = env('CLASSIC_TREE_MAX_DEPTH', 15);
                                                                break;
                                                            }
                                                            else{
                                                                $user_level = '---';
                                                            }
                                                        }
                                                    }
                                                    $level++;
                                                }
                                            }
                                            else{
                                                $user_level = '---';
                                            }
                                        }
                                        else{
                                            if($expected->level > 0){
                                                $user_level = $expected->level;
                                            }
                                            else{
                                                $user_level = '---';
                                            }
                                        }

                                    if($expected->level > 0){
                                        $user_level = $expected->level;
                                    }
                                    else{
                                        $user_level = '---';
                                    }

                                    if($expected->expected < 0 OR $expected->expected > 0){
                            ?>
                            <tr class="<?php echo $class;?>">
                                <td>
                                    <?php echo $expected->created_at; ?>
                                </td>
                                <td>
                                    <?php echo $saldo_stat; ?>
                                </td>
                                <td>
                                    <?php echo $expected->expected; ?>
                                </td>
                                <td>
                                    <?php echo $saldo_end; ?>
                                </td>
                                <td>
                                    <?php echo $bonus_name;?>
                                </td>
                                <td>
                                    <?php
                                        if(isset($customer_name) && !empty($customer_name)){
                                            echo $customer_name;
                                        }
                                        else{
                                            echo '---';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if(isset($product_name) && !empty($product_name)){
                                        echo $product_name;
                                    } else{
                                        echo '---';
                                    }?>
                                </td>
                                <td>
                                    <?php echo $user_level; ?>
                                </td>
                            </tr>
                        <?php
                            }
                            //Обновляем сальдо
                             $saldo_start = $saldo_end;
                             $saldo_end = $saldo_start - $expected->expected;
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<div id="modalorder" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding-bottom: 70px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Запрос на вывод денежных средств</h4>
            </div>

            <?php if($withdraw_status == 1){
            if($full_summ_available > 0 && $new_summ < $full_summ_available){?>
                <label id="cr_id" data-id="<?php echo Auth::id();?>" style="display:none;"></label>
                <div class="modal-body" id="create_form">
                    <div class="form-group">
                        <label class="col-md-2 control-label text-right">Сумма:</label>
                        <div class="col-md-10">
                            <input type="number" id="order_summ" min="1" max="70000" maxlength="5" autocomplete="off" placeholder="Введите сумму" class="form-control" required>
                            <hint><?php echo "Доступно к выводу - ".($full_summ_available - $new_summ); ?></hint>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label text-right">Номер карты:</label>
                        <div class="col-md-10">
                            <input type="number" id="order_card" min="1" minlength="16" maxlength="16" autocomplete="off" placeholder="Введите номер карты" class="form-control" required>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label text-right">Наименование банка:</label>
                        <div class="col-md-10">
                            <input type="text" id="order_bank" minlength="3" maxlength="50" autocomplete="off" placeholder="Введите название банка" class="form-control" required>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label text-right">Владелец карты:</label>
                        <div class="col-md-10">
                            <input type="text" id="order_user" minlength="3" maxlength="50" autocomplete="off" placeholder="Введите имя владельца карты" class="form-control" required>
                            <hint>Указывать на латыни, как указано на карте</hint>
                        </div>
                        <br>
                        <br>
                    </div>
                    <br>
                    <a href="javascript:void(0);" id="createOrder" onclick="createOrder()" data-toggle="modal" class="btn btn-success pull-right">Создать заявку</a>
                </div>
            <?php }
            else{ ?>
                <div class="modal-body text-center" style="margin-top: 20px;">
                    <h1>Подача заявок на вывод будет доступна, когда на Вашем счету будет достаточно средств</h1>
                </div>
            <?php
                }
            }else{?>
            <div class="modal-body text-center" style="margin-top: 20px;">
                <h1>Внимание!</h1>
                <p>Подача заявок на вывод временно отключена по техническим причинам. <br>Мы работаем над тем, чтобы как можно быстрее восстановить все. <br>Приносим извинения за временные неудобства!</p>
            </div>
            <?php }?>
            <div class="modal-body text-center" id="success_form" style="display: none;">
                <h1>Заявка на вывод средств успешно создана!</h1>
                <p>После ее рассмотрения - средства будут переведены на карту, номер которой был указан в заявке</p>
            </div>
        </div>
    </div>
</div>
<style>
    .loss{
        background-color: #a65858!important;
        color: #ffffff!important;
    }
    .loss:hover{
        background-color: #a65858!important;
        color: #030303!important;
    }
</style>