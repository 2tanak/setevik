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
                            <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input id="email" type="email" class="form-control input-lg" name="email" value="{{ $email or old('email') }}" placeholder="Введите e-mail" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Пароль" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input id="password-confirm" type="password" class="form-control input-lg" name="password_confirmation" placeholder="Подтверждение пароля" required>

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-default">
                                        <small>СБРОСИТЬ ПАРОЛЬ</small>
                                    </button>
                                </div>

                                <div class="form-group text-right">
                                    <a href="{{ route('login') }}">Авторизация</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
