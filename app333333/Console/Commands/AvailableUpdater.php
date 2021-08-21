<?php

namespace App\Console\Commands;

use App\ExpectedWallet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AvailableUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    protected $signature = 'availableupdater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление статуса средств в кошельке. Перевод средств с имеющихся в наличии в доступные к выводу. Через 14 дней после поступления на баланс. Запуск 1-2 раз в сутки.';

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
        //
        //текущая дата
        $now_date = Carbon::now();

        //обрабатываем текущую дату
        $now_year = stristr($now_date, '-', true);
        $now_month_s = stristr($now_date, '-');
        $now_month = mb_strimwidth($now_month_s, 1, 2);

        $now_replace_i = '-'.$now_month.'-';
        $now_replace_s = [$now_replace_i];
        $now_replace_e = [''];
        $now_day_s = str_replace($now_replace_s, $now_replace_e, $now_month_s);
        $now_day = mb_strimwidth($now_day_s, 0, 2);

        //прогоняем в цикле
        $expected = ExpectedWallet::query()->get();
        foreach($expected as $item){
            //Тащим дату с базы данных
           // $start_time = '2021-06-28 11:11:11';
            $start_time = $item->updated_at;
            //проверяем - не минусовая ли сделка
            if($item->expected > 0){
                //обрабатываем данные по времени запуска задачи
                $year = stristr($start_time, '-', true);
                $month_s = stristr($start_time, '-');
                $month = mb_strimwidth($month_s, 1, 2);

                $replace_i = '-'.$month.'-';
                $replace_s = [$replace_i];
                $replace_e = [''];
                $day_s = str_replace($replace_s, $replace_e, $month_s);
                $day = mb_strimwidth($day_s, 0, 2);

                //узнаем разницу дней между датами
                $dtDb = Carbon::createFromDate($year, $month, $day, 'America/Vancouver');  //Дата создания проводки
                $dtNow = Carbon::createFromDate($now_year, $now_month, $now_day, 'America/Vancouver'); //Текущая дата
                $hours_diff = $dtNow->diffInHours($dtDb); //количество часов разницы между датами
                $upd_days_diff = $hours_diff / 24; //переводим часы разницы в дни

                //если есть разница в 15 дней - обновляем статус
                if($upd_days_diff == 15){
                    echo " Обновляем статус - \n";
                    echo $upd_days_diff."\n";

                    $upd_item_id = $item->id;
                    //Обновляем указанную позицию - переводим проводку в область доступных к выводу средств
                    $upd_line = ExpectedWallet::findOrFail($upd_item_id);
                    $upd_line->status = 1;
                    $upd_line->save();
                }
                else{
                    echo "Не обновляем данные\n";
                    echo "ID - ".$item->id."\n";
                    echo "Создано - ".$item->created_at."\n";
                    echo "Обновлено - ".$item->updated_at." \n";
                    echo $upd_days_diff."\n";
                }
            }
           else{
               echo "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| \n";
           }
        }
    }
}
