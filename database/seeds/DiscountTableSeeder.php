<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discounts')->insert([
            [
                'product_id'    => 5,
                'percent'       => 15,
                'package_id'    => 1,
            ],
            [
                'product_id'    => 5,
                'percent'       => 30,
                'package_id'    => 2,
            ],
            [
                'product_id'    => 5,
                'percent'       => 50,
                'package_id'    => 3,
            ],
            [
                'product_id'    => 5,
                'percent'       => 50,
                'package_id'    => 4,
            ],
        ]);
    }
}
