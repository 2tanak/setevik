@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="auth-login-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="login-block">
                        <div class="auth-login-logo">
                            <img src="{{ asset('img/logo-login.png') }}" class="img-responsive" alt="logo">
                        </div>
                        <div class="login-form">

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @else
                                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}

                                    <div class="alert alert-info">
                                        <i class="fa-fw fa fa-info"></i>
                                        Если вы забыли пароль, введите логин или E-Mail.<br>
                                        Ссылка для сброса пароля будет выслана вам по E-Mail.
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="Введите e-mail" required>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-default">
                                            <small>ВЫСЛАТЬ</small>
                                        </button>
                                    </div>
                                </form>
                            @endif

                            <div class="form-group text-right">
                                <a href="{{ route('login') }}">Авторизация</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
