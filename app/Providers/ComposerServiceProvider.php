<?php

namespace App\Providers;

use App\Http\ViewComposers\AppComposer;
use App\Http\ViewComposers\BreadcrumbComposer;
use App\Http\ViewComposers\RegistrationFormComposer;
use App\Http\ViewComposers\OnlineSmartSystem\ProductComposer;
use App\Http\ViewComposers\OnlineSmartSystem\ActivationPartnerComposer;
use App\Http\ViewComposers\OnlineSmartSystem\ActivationComposer as OssActivationComposer;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // main
        View::composer('layouts.app', AppComposer::class);

        // breadcrumb
        View::composer('layouts.breadcrumb', BreadcrumbComposer::class);

        // countries in registration form
        View::composer('auth.register', RegistrationFormComposer::class);
        View::composer('auth.register_oss', RegistrationFormComposer::class);

        // curator on activation page
        View::composer('oss.info.activation', OssActivationComposer::class);

        // products
        View::composer('oss.products', ProductComposer::class);

        // активация партнерки
        View::composer('oss.be_partner_request', ActivationPartnerComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
