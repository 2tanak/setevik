<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            [
                'slug'  => 'rules',
                'name'  => 'Правила',
            ],
            [
                'slug'  => 'study',
                'name'  => 'Обучение',
            ],
            [
                'slug'  => 'money',
                'name'  => 'Деньги',
            ],
        ]);
    }
}
