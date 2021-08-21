<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinaryTreeNodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('binary_tree_nodes')->insert([
            [
                'parent_id'         => null, //1
                'team_id'           => 1,
                'root_node_id'      => 1,
                'user_id'           => 1,
                'is_active'         => true,
                'is_blocked'        => false,
                'is_free'           => false,
                'is_blocked_global' => false,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 1, // 2
                'team_id'           => 1,
                'root_node_id'      => 1,
                'user_id'           => 1,
                'is_active'         => true,
                'is_blocked'        => false,
                'is_free'           => false,
                'is_blocked_global' => false,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 1, // 3
                'team_id'           => 2,
                'root_node_id'      => 1,
                'user_id'           => 1,
                'is_active'         => true,
                'is_blocked'        => false,
                'is_free'           => false,
                'is_blocked_global' => false,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 2, // 4
                'team_id'           => 1,
                'root_node_id'      => 4,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => false,
                'is_free'           => true,
                'is_blocked_global' => false,
                'is_free_global'    => true,
            ],
            [
                'parent_id'         => 2, // 5
                'team_id'           => 2,
                'root_node_id'      => 5,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 3, // 6
                'team_id'           => 1,
                'root_node_id'      => 6,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 3, // 7
                'team_id'           => 2,
                'root_node_id'      => 7,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 4, // 8
                'team_id'           => 1,
                'root_node_id'      => 4,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 4, // 9
                'team_id'           => 2,
                'root_node_id'      => 4,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 5, // 10
                'team_id'           => 1,
                'root_node_id'      => 5,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 5, // 11
                'team_id'           => 2,
                'root_node_id'      => 5,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 6, // 12
                'team_id'           => 1,
                'root_node_id'      => 6,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 6, // 13
                'team_id'           => 2,
                'root_node_id'      => 6,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 7, // 14
                'team_id'           => 1,
                'root_node_id'      => 7,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
            [
                'parent_id'         => 7, // 15
                'team_id'           => 2,
                'root_node_id'      => 7,
                'user_id'           => null,
                'is_active'         => false,
                'is_blocked'        => true,
                'is_free'           => false,
                'is_blocked_global' => true,
                'is_free_global'    => false,
            ],
        ]);
    }
}
