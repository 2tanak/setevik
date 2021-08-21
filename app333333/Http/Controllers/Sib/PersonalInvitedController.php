<?php

namespace App\Http\Controllers\Sib;

use App\Models\Trees\BinaryTreeTeam;
use App\Models\Trees\BinaryTreeNode;
use App\Services\Sib\BinaryTreeService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalInvitedController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner');
    }

    public function index()
    {
		
        $node       = BinaryTreeNode::where('user_id', Auth::id())->first();
        $invited    = BinaryTreeNode::where('inviter_id', $node->id)->get();
        $teamsLeft  = collect();
        $teamsRight = collect();

        foreach ($invited as $item) {
            $team = BinaryTreeTeam::where('node_id', $node->id)
                ->where('team_node_id', $item->id)
                ->where('is_active', true)
                ->first();

            if ($team) {
                if ($team->team_id == BinaryTreeService::TEAM_LEFT) {
                    $teamsLeft->push($item);
                } else {
                    $teamsRight->push($item);
                }
            }
        }

        return view('sib.personal_invited')
            ->with('team_left', $teamsLeft)
            ->with('team_right', $teamsRight);
    }
}
