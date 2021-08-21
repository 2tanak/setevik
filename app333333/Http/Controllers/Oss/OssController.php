<?php

namespace App\Http\Controllers\Oss;

use App\Http\Controllers\Controller;

class OssController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:resident,partner');
    }
}
