<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BinaryPointsController extends ReportsController
{
    public function index()
    {
        return view('admin.reports.binary_points');
    }
}
