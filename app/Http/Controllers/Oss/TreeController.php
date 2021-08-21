<?php

namespace App\Http\Controllers\Oss;

use App\Services\Oss\TreeService;
use App\Models\Trees\OnlineSmartSystemTreeNode as Node;

use Illuminate\Support\Facades\Auth;

class TreeController extends OssController
{
    public function index(TreeService $tree)
    {
        $node = Node::with('user')->where('user_id', Auth::id())->first();
        $tree = $tree->getTree($node);

        return view('oss.tree')->with('data', collect($tree));
    }
}
