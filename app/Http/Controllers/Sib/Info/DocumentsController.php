<?php

namespace App\Http\Controllers\Sib\Info;

use App\Models\Documents;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner,resident');
    }

    public function index()
    {
        $data = Documents::where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('sib.info.documents')->with('data', $data);
    }

    public function show($id)
    {
        $item = Documents::findOrFail($id);
        $item->unmarkBadgeFor(Auth::user());

        return view('sib.info.documents_detail', compact('item'));
    }
}
