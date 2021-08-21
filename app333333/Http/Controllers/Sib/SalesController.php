<?php

namespace App\Http\Controllers\Sib;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner');
    }
}
