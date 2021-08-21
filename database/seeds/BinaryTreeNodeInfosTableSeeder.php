<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinaryTreeNodeInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 15; $i++) {
            $data[] = ['node_id' => $i];
        }

        DB::table('binary_tree_node_infos')->insert($data);
    }
}
