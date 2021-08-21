<?php

namespace App\Http\Controllers\Sib\Education;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PartnersController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner');
    }

    public function index()
    {
        return view('sib.education.partners');
    }
}
