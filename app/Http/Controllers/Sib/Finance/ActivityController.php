<?php

namespace App\Http\Controllers\Sib\Finance;

use App\Models\Sale;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner');
    }

    public function index()
    {
        $sales = Sale::with('product')
            ->with('customer')
            ->where('seller_id', Auth::id())
            ->get();

        return view('sib.finance.activity', compact('sales'));
    }
}
