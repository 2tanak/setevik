<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            [
                'code' => 'basic',
                'name' => 'Basic',
            ],
            [
                'code' => 'standard',
                'name' => 'Standart',
            ],
            [
                'code' => 'premium',
                'name' => 'Premium',
            ],
            [
                'code' => 'vip',
                'name' => 'VIP',
            ]
        ]);
    }
}
