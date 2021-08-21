<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bonuses')->insert([
            [
                'code'          => 'personal_recommendation',
                'name'          => 'Личная рекомендация',
                'sort'          => 100,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'even_sale',
                'name'          => 'Четная продажа',
                'sort'          => 200,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'quick_start',
                'name'          => 'Быстрый старт',
                'sort'          => 300,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'leadership_line',
                'name'          => 'Лидерский линейный',
                'sort'          => 400,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'binary',
                'name'          => 'Командный',
                'sort'          => 500,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'binary_passive',
                'name'          => 'Пассивный бинар',
                'sort'          => 600,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'residual',
                'name'          => 'Резидуальный',
                'sort'          => 700,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'oss',
                'name'          => 'Online Smart System',
                'sort'          => 800,
                'is_missiable'  => true,
            ],
            [
                'code'          => 'company_contribution',
                'name'          => 'Вклад в компанию',
                'sort'          => 900,
                'is_missiable'  => true,
            ],
        ]);
    }
}
