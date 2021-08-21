<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        <!-- Styles -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-skins.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-custom.css') }}" rel="stylesheet">
        <link href="{{ asset('https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            html, body {
                height: 100%;
                margin: 0;
            }
            #app {
                height: 100%;
                background-image: url({{ asset('img/bg-1.jpg') }});
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                overflow-x: scroll;
            }

            a {
                color: #fff;
            }
            a:hover {
                color: #fff;
                text-decoration: underline;
            }
            .container {
                padding-left: 0;
                padding-right: 0;
            }
            .row {
                margin-left: 0;
                margin-right: 0;
                padding-left: 5px;
                padding-right: 5px;
            }
            .alert {
                margin-left: -15px;
                margin-right: -15px;
            }
            .btn-default {
                height: 31px;
                min-width: 20%;
                padding: 0 22px;
                background-color: transparent;
                background-color: rgba(0,0,0, 0.3);
                border-radius: 0px;
                border-color: #ffc107;
                color: #ffc107;
            }
            .btn-default:hover {
                color: #ffc107;
                background-color: transparent;
                background-color: rgba(0,0,0, 0.3);
                border-radius: 0px;
                border-color: #ffc107;
            }
            .checkbox small {
                color: #fff;
            }

            .auth-login-wrapper {
                position: absolute;
                top: 50%;
                -ms-transform: translateY(-50%);
                transform: translateY(-50%);
            }
            .auth-login-logo img {
                padding: 20px 0;
                margin: auto;
            }

            .auth-register-logo {
                padding: 50px 0 10px 0;
            }
            .auth-register-logo img {
                max-width: 70%;
                margin: auto;
            }
            .auth-register-wrapper .form-group-sm {
                padding: 8px 0;
            }
        </style>
    </head>
    <body>
        <div id="app">
            @yield('content')
        </div>

        @if (env('RECAPTCHA', false))
            <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
            <script>
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function (token) {
                        if (token) {
                            document.getElementById('recaptcha').value = token;
                        }
                    });
                });
            </script>
        @endif

        <!-- Scripts -->
        <script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="js-any/libs/jquery-3.2.1.min.js"><\/script>');
            }
        </script>
        <script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js') }}"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="js-any/libs/jquery-ui.min.js"><\/script>');
            }
        </script>
        <script src="{{ asset('js-any/app.config.js') }}"></script>
        <script src="{{ asset('js-any/plugin/jquery-touch/jquery.ui.touch-punch.min.js') }}"></script>
        <script src="{{ asset('js-any/bootstrap/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js-any/plugin/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
        <script src="{{ asset('js-any/plugin/msie-fix/jquery.mb.browser.min.js') }}"></script>
        <script src="{{ asset('js-any/plugin/fastclick/fastclick.min.js') }}"></script>

        <!--[if IE 8]>
        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->

        <script src="{{ asset('js-any/app.js') }}"></script>
        <script src="{{ asset('js-any/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
        <script>
            $(document).ready(pageSetUp);
        </script>
    </body>
</html>
