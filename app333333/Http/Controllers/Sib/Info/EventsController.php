<?php

namespace App\Http\Controllers\Sib\Info;

use App\Models\Event;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function __construct()
    {
	   $this->middleware('role:resident,partner');

        //$this->middleware('role:partner,partner-na');
    }

    public function index()
    {
        $data = Event::with('announcePic')
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('sib.info.events', compact('data'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $event->unmarkBadgeFor(Auth::user());

        return view('sib.info.events_detail')->with('item', $event);
    }

    public function getList()
    {
        return Event::where('is_active', true)->paginate(5);
    }
}
