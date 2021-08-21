<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearnVideoTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('learn_video_types')->insert([
            [
                'slug' => 'type-1',
                'name' => 'Общее обучение по Платформе OSS (вводное видео)',
            ],
            [
                'slug' => 'type-2',
                'name' => 'Обучение по ведению Instagram',
            ],
            [
                'slug' => 'type-3',
                'name' => 'Обучение Taplink/Hipolink',
            ],
            [
                'slug' => 'type-4',
                'name' => 'Обучение по настройке чат-ботов',
            ],
            [
                'slug' => 'type-5',
                'name' => 'Обучение по настройке и анализу рекламной кампании',
            ],
        ]);
    }
}
