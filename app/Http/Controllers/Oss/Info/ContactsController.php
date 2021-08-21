<?php

namespace App\Http\Controllers\Oss\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:resident,resident-na,partner');
    }

    public function index()
    {
		$user = Auth::user();
		
        return view('oss.info.contacts');
    }
}
