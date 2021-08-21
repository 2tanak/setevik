<?php

namespace App\Services\Sib;

use App\User;
use App\Models\Trees\BinaryTreeNode as Node;
use App\Models\Trees\BinaryTreeTeam as Team;
use App\Models\Trees\BinaryTreeNodeInfo as NodeInfo;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class ClassicTreeService
{
    /**
     * Building array to view
     *
     * @param Node $node
     * @param array $context
     * @param int $level
     * @return array
     */
    protected function build(Node $node, &$context = [], $level = 1)
    {
        $children = Node::where('inviter_id', $node->id);

        foreach ($children->get() as $child) {
            $user = $child->user;

            if ($user->is_active && $user->activated_at) {
                $context[$level][] = $user;
            }

            if ($level < env('CLASSIC_TREE_MAX_DEPTH', 3)) {
                $this->build($child, $context, $level + 1);
            }
        }

        return $context;
    }

    /**
     * Build binary tree array
     * @param User $user
     * @return array
     */
    public function getTreeArray(User $user)
    {
        $node = Node::where('user_id', $user->id)->first();
        $tree = $this->build($node);

        for ($i = 1; $i <= count($tree); $i++) {
            if (isset($tree[$i])) {
                usort($tree[$i], function($a, $b) {
                    return $a->activated_at->getTimestamp() - $b->activated_at->getTimestamp();
                });
            }
        }

        return $tree;
    }

}