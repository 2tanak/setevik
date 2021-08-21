<?php

namespace App\Console\Commands;

use App\ExpectedWallet;
use App\Models\Sale;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class WalletsUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    protected $signature = 'walletsupdater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление данных в кошельках пользователей - перенос обновлений с кошелька OSS в наш. Запуск несколько раз в сутки.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Обновление списка операций с OSS
        $users = User::query()->where('is_active', '=', 1)->get();
        foreach($users as $user_info){
            $seller_id = $user_info->id;//Пользователь по которому обновляем данные
            $product_id = 5; //По пакету 5 обновление

            if(isset($seller_id) && !empty($seller_id)){
                //Проверяем количество продаж пользователя
                $user_sales = Sale::query()->where('seller_id', '=', $seller_id)->get();
                $sales_count = $user_sales->count();

                //Проверяем количество проводок OSS пользователя
                $user_expected = ExpectedWallet::query()->where('user_id', '=', $seller_id)->where('bonus_id', '=', 8)->get();
                $expected_count = $user_expected->count();

                //Если проводок в нашей базе - меньше чем продаж - обновляем список
                if($sales_count > $expected_count){
                    //Если у нас в списке есть проводки - получаем последнюю (для OSS)
                    $operation_expected = ExpectedWallet::query()->where('user_id', '=', $seller_id)->where('bonus_id', '=', 8)->orderByDesc('id')->first();

                    if(isset($operation_expected) && !empty($operation_expected)){

                        //Тащим с expected_wallets->description --- ID последней проводки
                        $start_id = $operation_expected->description;
                        //Если данное поле не пустое - выбираем все новые записи с продаж (и все айдишки выше указанной переносим к нам)
                        if(isset($start_id) && !empty($start_id) && $start_id !== NULL){
                            $upd_sales = Sale::query()->where('seller_id', '=', $seller_id)->where('id', '>', $start_id)->get();
                            //Если есть результаты - пишем полученные данные в базу
                            if(isset($upd_sales) && !empty($upd_sales)){
                                foreach($upd_sales as $sale_line){
                                    $upd_expected = new ExpectedWallet();
                                    $upd_expected->user_id = $seller_id;
                                    $upd_expected->bonus_id = 8;
                                    $upd_expected->expected = $sale_line->price;
                                    $upd_expected->upd_expected = $sale_line->price;
                                    $upd_expected->status = 0; //Начисляем но оставляем в списке на обработку через 14 дней
                                    $upd_expected->customer_id = $sale_line->customer_id;
                                    $upd_expected->product_id = 5;
                                    $upd_expected->description = $sale_line->id;
                                    $upd_expected->created_at = $sale_line->created_at;
                                    $upd_expected->save();
                                }
                            }
                        }
                    }
                    else{
                        //Если проводок нету - просто переносим весь список
                        $upd_sales = Sale::query()->where('seller_id', '=', $seller_id)->get();
                        //Если есть результаты - пишем полученные данные в базу
                        if(isset($upd_sales) && !empty($upd_sales)){
                            foreach($upd_sales as $sale_line){
                                $upd_expected = new ExpectedWallet();
                                $upd_expected->user_id = $seller_id;
                                $upd_expected->bonus_id = 8;
                                $upd_expected->expected = $sale_line->price;
                                $upd_expected->upd_expected = $sale_line->price;
                                $upd_expected->status = 0; //Начисляем но оставляем в списке на обработку через 14 дней
                                $upd_expected->customer_id = $sale_line->customer_id;
                                $upd_expected->product_id = 5;
                                $upd_expected->description = $sale_line->id;
                                $upd_expected->created_at = $sale_line->created_at;
                                $upd_expected->save();
                            }
                        }
                    }
                }
                echo 'User ID - '.$seller_id."\n";
                echo 'Sales count - '.$sales_count."\n";
                echo 'Expected count - '.$expected_count."\n";
            }
            else{
                die();
            }
        }
    }
}
