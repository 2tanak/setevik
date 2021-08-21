<?php

namespace App\Http\Controllers\Sib\Info;

use App\Models\News;
use App\Traits\Badgable;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
		
        $news = News::where('is_active', true)
            ->orderByDesc('id')
            ->paginate(5);

        return view('sib.info.news')->with('data', $news);
    }

    public function show($id)
    {
        $item = News::findOrFail($id);

        // remove badge (if exists)
        $item->unmarkBadgeFor(Auth::user());

        return view('sib.info.news_detail', compact('item'));
    }
}
