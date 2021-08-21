<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequisitionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('requisition_types')->insert([
            [
                'code' => 'subscription',
                'name' => 'Новая активация',
            ],
            [
                'code' => 'prolongation',
                'name' => 'Продление',
            ],
            [
                'code' => 'prolongation-2',
                'name' => 'Продление после перерыва',
            ],
            [
                'code' => 'subscription-2',
                'name' => 'Активация из SIB',
            ],
        ]);
    }
}
