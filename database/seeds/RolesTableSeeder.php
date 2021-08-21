<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name'          => 'Администратор',
                'slug'          => 'admin',
                'description'   => 'Администратор системы',
            ],
            [
                'name'          => 'Контент Менеджер',
                'slug'          => 'content-manager',
                'description'   => 'Работает с контентом',
            ],
            [
                'name'          => 'Контент Менеджер (OSS)',
                'slug'          => 'content-manager-oss',
                'description'   => 'Работает с контентом в системе OSS',
            ],
            [
                'name'          => 'Менеджер по финансам',
                'slug'          => 'finance-manager',
                'description'   => 'Подтвержает запросы на вывод',
            ],
            [
                'name'          => 'Партнер (не оплативший за пакет)',
                'slug'          => 'partner-na',
                'description'   => 'Партнер в системе SIB не оплативший за пакет',
            ],
            [
                'name'          => 'Партнер',
                'slug'          => 'partner',
                'description'   => 'Партнер в системе SIB (оплативший за пакет)',
            ],
            [
                'name'          => 'Резидент (не оплативший за пакет)',
                'slug'          => 'resident-na',
                'description'   => 'Резидент в системе Online Smart System (не оплативший за пакет)',
            ],
            [
                'name'          => 'Резидент',
                'slug'          => 'resident',
                'description'   => 'Резидент в системе Online Smart System (оплативший за пакет)',
            ],
        ]);
    }
}
