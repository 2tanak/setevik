<?php

namespace App\Http\Controllers\Oss\Info;

use App\Models\OssNews;
use App\Traits\Badgable;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    use Badgable;

    public function __construct()
    {
        $this->middleware('role:resident,partner');
    }

    public function index()
    {
		
        $user = Auth::user();
        $activatedAt = null;

        if ($user->isPartner()) {
			
            $activatedAt = $user->activated_at;
        } elseif ($user->isResident()) {
            $activatedAt = $user->oss_activated_at;
        }

        if ($user->hasRole('partner')) {
			
            $data = OssNews::where('is_active', true)
                ->orderByDesc('id')
                ->paginate(5);
				
        } else {
            $ossNewsLast = OssNews::where('is_active', true)
                ->where('created_at', '<=', $activatedAt)
                ->get()
                ->last();

            if ($ossNewsLast) {
                $data = OssNews::where('is_active', true)
                    ->where('created_at', '>=', $ossNewsLast->created_at)
                    ->orderByDesc('id')
                    ->paginate(5);
            } else {
                $data = OssNews::where('is_active', true)
                    ->orderByDesc('id')
                    ->paginate(5);
            }
        }
        //dd($data[0]->badges->where('user_id',$user->id));
        return view('oss.info.news', compact('data'));
    }

    public function show($id)
    {
		
        $item = OssNews::findOrFail($id);

        // remove badge (if exists)
        $item->unmarkBadgeFor(Auth::user());

        return view('oss.info.news_detail', compact('item'));
    }
}
