@extends('layouts.app')

@section('title', 'Активация')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-sm-offset-4 lock-wrapper">
            <form class="lockscreen animated flipInY" action="">
                <div class="logo">
                    <h1 class="semi-bold text-center">
                        <small><i class="fa fa-lock text-muted"></i> &nbsp;Заблокировано</small>
                    </h1>
                </div>
                <div class="text-center">
                    <img src="/img/clock-icon-vector.jpg" alt="" width="150" height="150" />
                    <div>
                        <h1>Smart International Business</h1>

                        @if (auth()->user()->activated_at)
                            <p>
                                <b>Дорогой(ая) {{ auth()->user()->getFullName() }}</b>, поздравляем!
                                Ваша оплата принята, теперь осталась самая малость.
                                <br>
                                Для завершения процесса активации пройдите по ссылке в письме,
                                отправленном на электронную почту, указанную при регистрации.
                                Тема письма: «Smart International Business. Активация».</p>
                        @else
                            <p>
                                <b>Дорогой(ая) {{ auth()->user()->getFullName() }}</b>, для активации личного кабинета Вам необходимо произвести оплату пакета и уведомить об этом администратора системы,
                                отправив подтверждение об оплате в течение 2-х часов с момента регистрации.
                                Квитанцию об оплате отправьте в чат ватсап, пройдя по ссылке: <a href="https://goo.gl/GGcF26">https://goo.gl/GGcF26</a></p>

                            <p>
                                <b>ВАЖНАЯ ИНФОРМАЦИЯ</b><br>
                                Только после получения администратором Вашего подтверждения об оплате личный кабинет будет активирован и будет занято место в системе бинара.<br>
                                В случае превышения указанного времени личный кабинет будет аннулирован и потребуется повторная регистрация.
                            </p>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
