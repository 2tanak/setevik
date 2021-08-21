@extends('layouts.auth')

@section('title', 'Авторизация')

@section('content')
    <div class="container">
        <div class="auth-login-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="auth-login-block">
                        <div class="auth-login-logo">
                            <img src="{{ asset('img/logo-login.png') }}" class="img-responsive" alt="logo">
                        </div>
                        <div class="auth-login-form">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="recaptcha" id="recaptcha">

                                <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control input-lg" name="login" value="{{ old('login') }}" placeholder="Введите e-mail" required autofocus>

                                    @if ($errors->has('login'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('login') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Введите пароль" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block">
  	                                            <strong style="color: #ffffff;">{{ $errors->first('g-recaptcha-response') }}</strong>
  	                                        </span>
                                    @endif
                                </div>

                                <div class="form-group text-left">
                                    <a href="{{ route('password.request') }}">Забыли свой пароль?</a>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-default">
                                        <small>ВОЙТИ</small>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection