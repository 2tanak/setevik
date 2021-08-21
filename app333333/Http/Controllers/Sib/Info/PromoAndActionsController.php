<?php

namespace App\Http\Controllers\Sib\Info;

use App\Models\Promo;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoAndActionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner,resident');
    }

    public function index()
    {
        $data = Promo::with('announcePic')
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('sib.info.promos', compact('data'));
    }

    public function show($id)
    {
        $event = Promo::findOrFail($id);
        $event->unmarkBadgeFor(Auth::user());

        return view('sib.info.promos_detail')->with('item', $event);
    }
}
