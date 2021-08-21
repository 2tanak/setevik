<?php

namespace App\Http\Controllers\Sib;

use App\Http\Controllers\Controller;
use App\Services\Sib\BinaryTreeService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BinaryTreeController extends Controller
{
    protected $tree;

    public function __construct(BinaryTreeService $tree)
    {
        $this->middleware('role:partner');
        $this->tree = $tree;
    }

    public function index($nodeId = null)
    {
	    $user = Auth::user();
		
		
        $flag = false;
		
		if(isset($_SERVER['HTTP_REFERER'])){
			//dd($_SERVER['HTTP_REFERER']);
		$str = substr($_SERVER['HTTP_REFERER'],0,strpos($_SERVER['HTTP_REFERER'],'?'));
		
		preg_match('/search/i',$str,$arr);
		
		if(!isset($arr[0])){
		preg_match('/classic/i',$_SERVER['HTTP_REFERER'],$arr);

		}
		if(!isset($arr[0])){
		preg_match('/personal-invited/i',$_SERVER['HTTP_REFERER'],$arr);

		}
		
		preg_match('/me-and-my-team\/\d+/i',$_SERVER['HTTP_REFERER'],$arr2);
		preg_match('/me-and-my-team/i',$_SERVER['HTTP_REFERER'],$arr3);
		preg_match('/me-and-my-team$/i',$_SERVER['REQUEST_URI'],$arr5);
        if(isset($arr[0]) || isset($arr2[0]) || isset($arr3[0]) || isset($arr5[0])){
			$flag = true;
		}
		}else{
			$flag = true;
			if($user->tree_node_id !=$nodeId){

			preg_match('/me-and-my-team\/\d+/i',$_SERVER['REQUEST_URI'],$arr3);
			 if(isset($arr3[0])){
				$flag = false;
            }
		  }
			
		}
				

		if($flag === false){
			header('Location: '.'/me-and-my-team');exit();
		}
		
        $tree = $this->tree->getArray($nodeId);
		//dd($tree);
        $team = $user->getNodePartner()->getTeamName();
        $curator = null;
        $curatorFullName = '';

        $curatorNode = $user->getCuratorNodePartner();

        if ($curatorNode) {
            $curator = $curatorNode->user;
            $curatorFullName = $curator->getFullName();

            $curatorTeam = $curatorNode->getTeamName();
        }

        return view('sib.binary_tree')
            ->with('tree', json_encode($tree))
            ->with('user', $user)
            ->with('team', $team)
            ->with('curatorFullName', $curatorFullName);
    }

    /**
     * Get link
     * @param BinaryTreeService $tree
     * @param $nodeId
     * @return mixed
     */
    public function getLink(BinaryTreeService $tree, $nodeId)
    {
        return Response::json([
            'link' => $tree->getRefLink($nodeId)
        ], 200);
    }

}
