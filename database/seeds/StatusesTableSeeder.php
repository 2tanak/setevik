<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            [
                'code'          => 'pk',
                'name'          => 'Представитель компании',
                'short_name'    => 'ПК',
            ],
            [
                'code'          => 'op',
                'name'          => 'Официальный представитель компании',
                'short_name'    => 'ОП',
            ],
            [
                'code'          => 'mp',
                'name'          => 'Менеджер по продажам',
                'short_name'    => 'МП',
            ],
            [
                'code'          => 'sm',
                'name'          => 'Старший менеджер по продажам',
                'short_name'    => 'СМ',
            ],
            [
                'code'          => 'sv',
                'name'          => 'Супервайзер',
                'short_name'    => 'СВ',
            ],
            [
                'code'          => 'np',
                'name'          => 'Начальник отдела продаж',
                'short_name'    => 'НП',
            ],
            [
                'code'          => 'dp',
                'name'          => 'Директор по продажам 1-го уровня',
                'short_name'    => 'ДП',
            ],
            [
                'code'          => 'dp2',
                'name'          => 'Директор по продажам 2-го уровня',
                'short_name'    => 'ДП2',
            ],
            [
                'code'          => 'dp3',
                'name'          => 'Директор по продажам 3-го уровня',
                'short_name'    => 'ДП3',
            ],
            [
                'code'          => 'dr',
                'name'          => 'Директор по развитию',
                'short_name'    => 'ДР',
            ],
            [
                'code'          => 'rd',
                'name'          => 'Директор по региональному развитию (региональный директор)',
                'short_name'    => 'РД',
            ],
            [
                'code'          => 'md',
                'name'          => 'Директор по международному развитию (международный директор)',
                'short_name'    => 'МД',
            ],
            [
                'code'          => 'up',
                'name'          => 'Управляющий партнер компании',
                'short_name'    => 'УП',
            ],
        ]);
    }
}
