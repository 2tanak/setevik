<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rewards')->insert([
            [
                'bonus_id'      => 5,
                'package_id'    => 1,
                'amount'        => 0,
                'started_at'    => '2017-07-01 00:00:00',
                'expired_at'    => '3333-12-31 23:59:59',
            ],
            [
                'bonus_id'      => 5,
                'package_id'    => 2,
                'amount'        => 100,
                'started_at'    => '2017-07-01 00:00:00',
                'expired_at'    => '3333-12-31 23:59:59',
            ],
            [
                'bonus_id'      => 5,
                'package_id'    => 3,
                'amount'        => 600,
                'started_at'    => '2017-07-01 00:00:00',
                'expired_at'    => '3333-12-31 23:59:59',
            ],
            [
                'bonus_id'      => 5,
                'package_id'    => 4,
                'amount'        => 2000,
                'started_at'    => '2017-07-01 00:00:00',
                'expired_at'    => '3333-12-31 23:59:59',
            ],
        ]);
    }
}
