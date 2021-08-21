<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinancialAnalyticsController extends ReportsController
{
    public function index()
    {
        return view('admin.reports.financial_analytics');
    }
}
