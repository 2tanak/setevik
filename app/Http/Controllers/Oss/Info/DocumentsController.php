<?php

namespace App\Http\Controllers\Oss\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:resident,partner');
    }

    public function index() {
        return view('oss.info.documents');
    }
}
