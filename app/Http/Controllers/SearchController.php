<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Services\Oss\TreeService;
use App\Services\Sib\BinaryTreeService;

class SearchController extends Controller
{
	protected $tree;

    public function __construct(BinaryTreeService $tree)
    {
        $this->tree = $tree;
        $this->middleware('auth');
    }

    public function index()
    {
		
        $keyword = Input::get('q');
        $data = collect();
		$user = Auth::user();
		if (strlen($keyword)) {
           $data= $this->tree->getUserVetka($user->tree_node_id,$keyword);
		  //dd($data);
		 }
		//dump($data);exit();
		/*
        if (strlen($keyword)) {
            $data = User::query()
                ->with('package')
                ->with('status')
                ->with('cabinet')
				->with('subscriptions')
                ->where('id', '>', 1)
                ->where('is_active', 1)
                ->where(function ($query) use ($keyword) {
                    $query
                        ->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%");
                })
                ->paginate(20)
                ->appends(request()->query())
            ;
        } else {
//            $data = User::with('package')
//                ->with('status')
//                ->with('cabinet')
//                ->where('id', '>', 1)
//                ->where('is_active', 1)
//                ->orderBy('id', 'desc')
//                ->paginate(20);
        }
*/

        return view('search')
            ->with('data', $data)
            ->with('q', $keyword);
    }
}
