<?php

namespace App\Http\Controllers\Sib\Finance;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PaymentCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner');
    }

    public function index()
    {
        return view('sib.finance.payment_calendar');
    }
}
