<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckRatingController extends ReportsController
{
    public function index()
    {
        return view('admin.reports.check_ratings');
    }
}
