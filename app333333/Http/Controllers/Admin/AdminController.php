<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
}
