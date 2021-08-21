<?php

namespace App\Http\ViewComposers\OnlineSmartSystem;

use App\Models\Requisition;
use App\Services\Oss\TreeService;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductComposer
{
    protected $tree;

    public function __construct(TreeService $tree)
    {
        $this->tree = $tree;
    }

    public function compose(View $view)
    {
        $user = Auth::user();
        $requisitions = Requisition::where('user_id', $user->id)
            ->where('is_confirmed', false)
            ->where('is_cancelled', false);
        
        if ($requisitions->count()) {
            $curator = $requisitions->first()->curator;
			
        } else {

/*
        $products = $user::with([
                'subscriptions' => function ($query) use ($user) {
                    $query
                        ->where('user_id', $user->id)
                        ->where('expired_at', '>', Carbon::now());
                    },
                
            ])->get();
			*/
			
			//dd($user->subscriptions->where('expired_at',Carbon::now()));
            $curator = $this->tree->getActiveCurator($user);
        }

        $view->with('curator', json_encode($curator));
    }
}