<?php

namespace App\Http\Controllers\Sib\Finance;

use App\Models\Bonus;
use App\Models\Package;
use App\Models\Product;
use App\Models\Statement;
use App\Models\Wallet;
use App\ExpectedWallet;
use App\Http\Controllers\Controller;

use App\WithdrawOrders;
use App\WithdrawOrdersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Sib\ClassicTreeService;

class WalletController extends Controller
{
    protected $treeService;

    public function __construct(ClassicTreeService $treeService)
    {
        $this->middleware('role:partner');
        $this->treeService = $treeService;
    }

    public function index()
    {
        //статус подачи запросов на вывод средств
        $withdraw_status_id = 1;
        try{
            $order_status = WithdrawOrdersStatus::query()->where('id', '=', $withdraw_status_id)->first();
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

        $old_wallets = Wallet::with('bonus')
            ->where('user_id', Auth::id())
            ->get()
            ->sortBy('bonus.sort');

        //Средства в ожидании обработки для перехода в зону вывода
        $expected_wallets = ExpectedWallet::where('user_id', Auth::id())->orderByDesc('created_at')
            ->get();

        //Средства в ожидании обработки для перехода в зону вывода
        $expected_wallets_all = ExpectedWallet::where('user_id', Auth::id())->orderByDesc('created_at')
            ->get();

        $zero_wallets = ExpectedWallet::where('user_id', Auth::id())->where('expected', '==', 0)->orderByDesc('created_at')
            ->get();

        $stat_count = Statement::get();

        //Если нету проводок - перестраховываемся и прописываем ноли в кошелек
        if($expected_wallets->count() == 0){
            foreach($old_wallets as $zero_wallets){
                $wallets_line_id = $zero_wallets->id;
                $z_wallets = Wallet::findOrFail($wallets_line_id);
                $z_wallets->earned = 0;
                $z_wallets->expected = 0;
                $z_wallets->available = 0;
                $z_wallets->save();
            }
        }
        else{
            //echo 'No zero';
        }

        //Обновление значения суммы в ожидании выплаты
        foreach($old_wallets as $wallet){
            $summ = 0;
            foreach($expected_wallets as $expected){
                //Если тип бонуса соответствует активному
                if($wallet->bonus->id == $expected->bonus_id){
                    //Если бонус еще не выведен
                    if($expected->status == 0){
                        //$summ = $summ + $expected->expected;
                        $summ = $summ + $expected->upd_expected;
                    }
                    else{
                        $summ = $summ + 0;
                    }
                }
                else{
                    $summ = $summ + 0;
                }

                //Обновляем запись о доступных к выводу средствах в кошельке пользователя
                $wallets_write = Wallet::where('user_id', Auth::id())
                    ->where('bonus_id', $wallet->bonus->id)
                    ->first();
                $wallets_write->expected = $summ;
                $wallets_write->save();
            }
        }

        //Обновление значения суммы доступной к выводу
        foreach($old_wallets as $wallet){
            $summ = 0;
            foreach($expected_wallets as $expected){
                //Если тип бонуса соответствует активному
                if($wallet->bonus->id == $expected->bonus_id){
                    //Если бонус еще не выведен
                    if($expected->status == 1){
                        $summ = $summ + $expected->upd_expected;
                    }
                    else{
                        $summ = $summ + 0;
                    }
                }
                else{
                    $summ = $summ + 0;
                }

                //Обновляем запись о доступных к выводу средствах в кошельке пользователя
                $wallets_write = Wallet::where('user_id', Auth::id())
                    ->where('bonus_id', $wallet->bonus->id)
                    ->first();
                $wallets_write->available = $summ;
                $wallets_write->save();
            }
        }

        //Обновление значения суммы доступной к выводу
        foreach($old_wallets as $wallet){
            $summ = 0;
            foreach($expected_wallets as $expected){
                //Если тип бонуса соответствует активному
                if($wallet->bonus->id == $expected->bonus_id){
                    //Если бонус еще не выведен
                    $summ = $summ + $expected->expected;
                }
                else{
                    $summ = $summ + 0;
                }

                //Обновляем запись о доступных к выводу средствах в кошельке пользователя
                $wallets_write = Wallet::where('user_id', Auth::id())
                    ->where('bonus_id', $wallet->bonus->id)
                    ->first();
                $wallets_write->earned = $summ;
                $wallets_write->save();
            }
        }

        //Получаем список финансовых операций пользователя
        $user_orders = WithdrawOrders::where('user_id', Auth::id())
            ->get();

        //Общая сумма ордеров на вывод
            $new_summ = 0;
            if(!empty($user_orders)){
                foreach($user_orders as $summ){
                    if($summ->status == 0){
                        $new_summ = $new_summ + $summ->summ;
                    }
                }
            }

        //Общая сумма выполненных на вывод ордеров
        $success_summ = 0;
            if(!empty($user_orders)){
                foreach($user_orders as $summ){
                    if($summ->status == 1){
                        $success_summ = $success_summ + $summ->summ;
                    }
                }
            }

        //Получаем новые значения с данными о средствах пользователя
        $wallets = Wallet::with('bonus')
            ->where('user_id', Auth::id())
            ->get()
            ->sortBy('bonus.sort');

        //Если у данного пользователя нету кошелька
        if($wallets->count() == 0){
            $earned = 0;
            $available = 0;
            $expected = 0;

            $i = 1;
            while($i < 10){
                $new_wallet = new Wallet();
                $new_wallet->user_id = Auth::id();
                $new_wallet->bonus_id = $i;
                $new_wallet->earned = $earned;
                $new_wallet->available = $available;
                $new_wallet->expected = $expected;
                $new_wallet->save();
                $i++;
            }
            //Получаем новые значения с данными о средствах пользователя
            $wallets = Wallet::with('bonus')
                ->where('user_id', Auth::id())
                ->get()
                ->sortBy('bonus.sort');
        }

        $statements = Statement::with([
            'initiator',
            'bonus',
            'package',
        ])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(20);

        $active_user_id = Auth::id();

        //Список продуктов OSS
        $products = Product::query()->get();

        //Список пакетов SIB
        $packages = Package::query()->get();

        //Дерево пользователей
        $data_tree = $this->treeService->getTreeArray(Auth::user());

        return view('sib.finance.wallet', compact('wallets', 'statements', 'expected_wallets', 'expected_wallets_all', 'new_summ', 'success_summ', 'active_user_id', 'withdraw_status', 'products', 'packages', 'data_tree'));
    }

    //Создание заявки на вывод средств
    public function order(Request $request)
    {
        //Обрабатываем запрос
        $csrf = $request->csrf;
        $order_bank = $request->order_bank;
        $order_card = $request->order_card;
        $order_summ = $request->order_summ;
        $order_user = $request->order_user;
        $order_limit = $request->lim;
        $user_id = $request->cr_id;

        //Узнаем доступный лимит на вывод для выбранного пользователя
        if(!empty($user_id)){
            //Получаем доступный баланс
            $wallets = Wallet::where('user_id', Auth::id())->get();
            $full_user_summ = 0;
            foreach($wallets as $user_summ){
                $full_user_summ = $full_user_summ + $user_summ->available;
            }

            //Получаем сумму уже существующих ордеров на вывод средств
            $full_limit_summ = 0;
            $orders_summ = Wallet::where('user_id', Auth::id())->get();
            foreach($orders_summ as $item){
                if($item->status == 0){
                    //Плюсуем все невыполненные ордера
                    $full_limit_summ = $full_limit_summ + $item->summ;
                }
            }
            //Получаем актуальный лимит для подачи заявки на вывод средств
            if($full_user_summ > $full_limit_summ || $full_user_summ == $full_limit_summ){
                $stat_limit = $full_user_summ - $full_limit_summ;
            }
            else{
                $stat_limit = 0;
            }
        }
        else{
            $stat_limit = 0;
            return json_encode('ORDER ERROR');
        }

        if(!empty($csrf)){
            //Проверка лимита
            if($order_summ < $stat_limit || $order_summ == $stat_limit && $order_summ > 0 && $stat_limit > 0){
                //Проверка введенных данных
                $card_length = strlen($order_card);
                $bank_length = strlen($order_bank);
                $user_length = strlen($order_user);

                //Банковская карта
                if($card_length == 16){
                    //Наименование банка
                    if($bank_length >= 3){
                        //Владелец карты
                        if($user_length >= 5){
                            //Запись заявки в базу
                            $create_order = new WithdrawOrders();
                            $create_order->user_id = Auth::id();
                            $create_order->summ = $order_summ;
                            $create_order->card = $order_card;
                            $create_order->bank = $order_bank;
                            $create_order->name = $order_user;
                            $create_order->status = 0;
                            $create_order->upd_summ = $order_summ;
                            $create_order->save();

                            //Средства из раздела "доступно к выводу" -> автоматом минусуются за счет добавленной заявки

                            //Отправка уведомления администратору системы о создании новой заявки
                            //Отправка уведомления пользователю системы о создании заявки на вывод

                            //Возвращаем ответ
                            return json_encode('ORDER SAVED');
                        }
                        else{
                            return json_encode('ФИО владельца карты не может быть короче 5 символов');
                        }
                    }
                    else{
                        return json_encode('Название банка не может быть короче 3 символов');
                    }
                }
                else{
                    return json_encode('Введите верный номер банковской карты');
                }
            }
            else{
                //Если сумма выше доступного лимита средств
                return json_encode('Превышен доступный лимит на вывод средств');
            }
        }
        else{
            return json_encode('ORDER ERROR');
        }
    }
}
