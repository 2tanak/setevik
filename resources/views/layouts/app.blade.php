<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        <!-- Styles -->
        {{--<link href="{{ asset('css/app.css') }}?v=1.8" rel="stylesheet">--}}

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}?v=1.8" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-production.min.css') }}?v=1.8" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-skins.min.css') }}?v=1.8" rel="stylesheet">
        <link href="{{ asset('https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700') }}" rel="stylesheet">

        <link href="{{ asset('css/smartadmin-custom.css') }}?v=1.8" rel="stylesheet">
        <link href="{{ asset('css/bin-tree-v1.0.2.css') }}?v=1.8" rel="stylesheet">
    </head>
    <body class="smart-style-7 fixed-header fixed-navigation fixed-page-footer desktop-detected pace-done {{ App::environment(['local', 'test']) ? 'test-mode' : '' }}">
<div class="okno"></div>
        @if ($is_admin_section)
            @include('layouts.app_admin')
        @else
            @if ($user->hasRole('partner', 'partner-na'))
                @include('layouts.app_sib')
            @elseif($user->hasRole('resident', 'resident-na'))
                @include('layouts.app_oss')
            @else
                <p>There is no cabinet</p>
            @endif
        @endif

    </body>
</html>
