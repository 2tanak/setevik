<?php

use App\User;
use App\Models\News;
use App\Models\OssNews;
use App\Traits\Badgable;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    use Badgable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return;
        // clear all data
        News::truncate();

        $faker = \Faker\Factory::create();
        $user = User::find(1);

        for ($i = 0; $i < 3; $i++) {
            $news = News::create([
                'title'         => $faker->sentence,
                'announce'      => $faker->paragraph,
                'detail'        => $faker->paragraph,
                'is_active'     => ($i % 2 == 0) ? true : false,
                'created_at'    => Carbon::createFromTimeString('2020-01-01 21:30:00')->addDays($i),
            ]);

            // bind badge
            if ($news->is_active) {
                $news->attachBadgeFor($user);
            }
        }


        for ($i = 0; $i < 3; $i++) {
            OssNews::create([
                'title'         => $faker->sentence,
                'announce'      => $faker->paragraph,
                'detail'        => $faker->paragraph,
                'is_active'     => ($i % 2 == 0) ? true : false,
                'created_at'    => Carbon::createFromTimeString('2020-01-04 22:30:00')->addDays($i),
            ]);
        }
    }
}
