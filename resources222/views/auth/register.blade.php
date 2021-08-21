@extends('layouts.auth')

@section('title', 'Регистрация')

@section('content')
    @if (\App::environment('local'))
        <?php $userCount = \App\User::count();?>
        <div class="container auth-register-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="auth-register-logo">
                        <img src="{{ asset('img/logo-login.png') }}" class="img-responsive" alt="logo">
                    </div>
                    <div class="register-form">

                        @if ($errors->has('ref'))
                            <div class="alert alert-danger fade in">
                                <button class="close" data-dismiss="alert">×</button>
                                <i class="fa-fw fa fa-exclamation-triangle"></i>
                                {{ $errors->first('ref') }}
                            </div>
                        @endif

                        <form class="form-horizontal" id="register" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="form-group-sm{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="email" value="user{{ $userCount + 1 }}@example.com" placeholder="E-mail*" autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('email') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" name="password" value="123456" placeholder="Пароль* (не менее 6 символов длиной)">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('password') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm">
                                <input type="password" class="form-control" name="password_confirmation" value="123456" placeholder="Подтверждение пароля*">
                            </div>

                            <div class="form-group-sm{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="name" value="User{{ $userCount + 1 }}" placeholder="Имя*">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('name') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="last_name" value="Test" placeholder="Фамилия*">

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('last_name') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('is_female') ? ' has-error' : '' }}">
                                <select class="form-control" name="is_female">
                                    <option value="0" selected>Мужчина</option>
                                    <option value="1">Женщина</option>
                                </select>

                                @if ($errors->has('is_female'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('is_female') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                <input type="text" class="form-control datepicker" name="birthday" value="01.01.1980" placeholder="Дата рождения*">

                                @if ($errors->has('birthday'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('birthday') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="phone" value="+7" placeholder="Мобильный*">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('phone') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('country') ? ' has-error' : '' }}">
                                <select class="form-control" name="country">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('country'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('country') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('city') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="city" value="Moscow" placeholder="Населенный пункт*">

                                @if ($errors->has('city'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('city') }}</small>
                                </span>
                                @endif
                            </div>

                            <p class="text-right note">
                                <small>
                                    &nbsp&nbsp<i style="color: lightgrey">* Поля, обязательные для заполнения</i>
                                </small>
                            </p>

                            <div class="form-group-sm{{ $errors->has('contract') ? ' has-error' : '' }}">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="contract" checked>
                                        <small>
                                            Ознакомился и полностью принимаю и согласен с условиями
                                            <a href="/storage/agreements/agreement-oss.pdf" target="_blank"><span>Договора оферты</span></a>
                                        </small>
                                    </label>
                                </div>
                                @if ($errors->has('contract'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('contract') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm text-right">
                                <button type="submit" class="btn btn-default">
                                    <small>Регистрация</small>
                                </button>
                            </div>

                            <div class="form-group-sm text-right">
                                <a href="{{ route('login') }}">Авторизация</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container auth-register-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="auth-register-logo">
                        <img src="{{ asset('img/logo-login.png') }}" class="img-responsive" alt="logo">
                    </div>
                    <div class="register-form">

                        @if ($errors->has('ref'))
                            <div class="alert alert-danger fade in">
                                <button class="close" data-dismiss="alert">×</button>
                                <i class="fa-fw fa fa-exclamation-triangle"></i>
                                {{ $errors->first('ref') }}
                            </div>
                        @endif

                        <form class="form-horizontal" id="register" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="form-group-sm{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail*" autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('email') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" name="password" placeholder="Пароль* (не менее 6 символов длиной)">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('password') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Подтверждение пароля*">
                            </div>

                            <div class="form-group-sm{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Имя*">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('name') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Фамилия*">

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('last_name') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('is_female') ? ' has-error' : '' }}">
                                <select class="form-control" name="is_female">
                                    <option value="0">Мужчина</option>
                                    <option value="1">Женщина</option>
                                </select>

                                @if ($errors->has('is_female'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('is_female') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                <input type="text" class="form-control datepicker" name="birthday" value="{{ old('birthday') }}" placeholder="Дата рождения*">

                                @if ($errors->has('birthday'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('birthday') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Мобильный*">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('phone') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('country') ? ' has-error' : '' }}">
                                <select class="form-control" name="country">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('country'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('country') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm{{ $errors->has('city') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}" placeholder="Населенный пункт*">

                                @if ($errors->has('city'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('city') }}</small>
                                </span>
                                @endif
                            </div>

                            <p class="text-right note">
                                <small>
                                    &nbsp&nbsp<i style="color: lightgrey">* Поля, обязательные для заполнения</i>
                                </small>
                            </p>

                            <div class="form-group-sm{{ $errors->has('contract') ? ' has-error' : '' }}">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="contract">
                                        <small>
                                            Ознакомился и полностью принимаю и согласен с условиями
                                            <a href="/storage/agreements/agreement-oss.pdf" target="_blank"><span>Договора оферты</span></a>
                                        </small>
                                    </label>
                                </div>
                                @if ($errors->has('contract'))
                                    <span class="help-block">
                                    <small>{{ $errors->first('contract') }}</small>
                                </span>
                                @endif
                            </div>

                            <div class="form-group-sm text-right">
                                <button type="submit" class="btn btn-default">
                                    <small>Регистрация</small>
                                </button>
                            </div>

                            <div class="form-group-sm text-right">
                                <a href="{{ route('login') }}">Авторизация</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        ((w, d) => {
            let input = d.createElement('input');
            input.name = 'ref';
            input.hidden = true;
            input.setAttribute('value', new URL(w.location.href).searchParams.get('ref'));
            d.getElementById('register').append(input);
        })(window, document)
    </script>
@endsection