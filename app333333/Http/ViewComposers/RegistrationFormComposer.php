<?php

namespace App\Http\ViewComposers;

use App\Models\Country;

use Illuminate\View\View;

class RegistrationFormComposer
{
    public function compose(View $view)
    {
        $countries = Country::all();

        $view->with(['countries' => $countries]);
    }
}