<?php

namespace App\Http\Controllers\Sib\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:resident,resident-na,partner');
    }

    public function index()
    {
		
        return view('sib.info.contacts');
    }
}