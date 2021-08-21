<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabinetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cabinets')->insert([
            [
                'code'      => 'admin',
                'name'      => 'Admin Panel',
                'is_active' => true,
            ],
            [
                'code'      => 'sib',
                'name'      => 'Smart International Business',
                'is_active' => true,
            ],
            [
                'code'      => 'oss',
                'name'      => 'Online Smart System',
                'is_active' => true,
            ],
        ]);
    }
}
