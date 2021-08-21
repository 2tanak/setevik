<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FcPerPeriodController extends ReportsController
{
    public function index()
    {
        return view('admin.reports.fc_per_period');
    }
}
