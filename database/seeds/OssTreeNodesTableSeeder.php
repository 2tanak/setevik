<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OssTreeNodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oss_tree_nodes')->insert([
            [
                'parent_id' => null,
                'user_id'   => 1,
                'is_active' => true,
            ],
        ]);
    }
}
