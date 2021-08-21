<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_types')->insert([

            // партнеры
            [
                'slug' => 'partner-registered',
                'name' => 'Регистрация партнера',
            ],
            [
                'slug' => 'partner-activated',
                'name' => 'Активация партнера',
            ],
            [
                'slug' => 'partner-blocked',
                'name' => 'Блокирование партнера',
            ],
            [
                'slug' => 'partner-deactivated',
                'name' => 'Деактивация партнера',
            ],
            [
                'slug' => 'partner-upgraded',
                'name' => 'Апгрейд партнера',
            ],

            // резиденты
            [
                'slug' => 'resident-registered',
                'name' => 'Регистрация резидента',
            ],
            [
                'slug' => 'resident-activated',
                'name' => 'Активация резидента',
            ],
            [
                'slug' => 'resident-blocked',
                'name' => 'Блокирование резидента',
            ],
            [
                'slug' => 'resident-transition-to-partner',
                'name' => 'Переход резидента в партнерку',
            ],
            [
                'slug' => 'resident-be-partner-request',
                'name' => 'Запрос на переход в партнерку',
            ],
            [
                'slug' => 'resident-curator-changed',
                'name' => 'Смена куратора резидента',
            ],

            // заявки
            [
                'slug' => 'requisition-registered',
                'name' => 'Регистрация новой заявки на продукт OSS',
            ],
            [
                'slug' => 'requisition-owner-confirm',
                'name' => 'Подтверждение заявки на продукт OSS (пользователь)',
            ],
            [
                'slug' => 'requisition-curator-confirm',
                'name' => 'Подтверждение заявки на продукт OSS (куратор)',
            ],
            [
                'slug' => 'requisition-admin-confirm',
                'name' => 'Подтверждение заявки на продукт OSS (администратор)',
            ],

            // кошелек
            [
                'slug' => 'request-withdraw',
                'name' => 'Запрос на вывод средств',
            ],
            [
                'slug' => 'request-withdraw-submit',
                'name' => 'Подтверждение запроса на вывод средств',
            ],

            // бонусы
            [
                'slug' => 'enrol-qsb',
                'name' => 'Начисление ББ',
            ],

        ]);
    }
}
