<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $view->with(['breadcrumb' => '']);
    }
}