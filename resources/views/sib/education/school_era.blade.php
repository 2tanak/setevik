@extends('layouts.app')

@section('title', 'Школа ERA')

@section('content')
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
        <div class="lockscreen animated flipInY">
            <div class="padding-20 text-center">
                <img src="/img/maintenance.png" class="img-responsive" style="margin: 0 auto;" alt="log">
            </div>
            <br>
            <div class="row">

                <div class="padding-20 text-center">
                    <p>Упс) Мы ещё здесь работаем) <br>
                        Страница находится в разработке и уже скоро будет доступна.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@section('content')--}}
    {{--<div class="row">--}}
        {{--<div class="well" style="padding: 0 19px 0 19px">--}}
            {{--<h2 style="line-height: 1">--}}
                {{--ССЫЛКА НА ПРЯМОЙ ЭФИР ЗАНЯТИЯ, ПЯТНИЦА--}}

                {{--@if (\Carbon\Carbon::now()->isDayOfWeek(5))--}}
                    {{--{{ \Carbon\Carbon::now()->format('d.m.Y') }} г.:--}}
                {{--@else--}}
                    {{--{{ \Carbon\Carbon::now()->next(5)->format('d.m.Y') }} г.:--}}
                {{--@endif--}}

                {{--<br>--}}
                {{--<small style="color: grey;">--}}
                    {{--<i>В назначенное время в этом разделе появится кнопка, нажав на которую Вы попадёте на прямой эфир занятия курса «Я — личность» в приложении ZOOM</i>--}}
                {{--</small>--}}
            {{--</h2>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="row" style="margin-left: -25px; margin-right: -25px">--}}
        {{--<!-- #RIGHT -->--}}
        {{--<div class="col-sm-9">--}}

            {{--<div class="well" style="padding: 0 19px 0 19px">--}}
                {{--<h2 style="line-height: 1">ЗАПИСЬ ПОСЛЕДНЕГО ЗАНЯТИЯ: <br>--}}
                    {{--<small style="color: grey;">--}}
                        {{--<i>Будет доступна в этом разделе до следующего занятия</i>--}}
                    {{--</small>--}}
                {{--</h2>--}}
            {{--</div>--}}

            {{--<div class="well" style="padding: 0 19px 0 19px">--}}
                {{--<h2 style="line-height: 1">ЗАПИСИ ПРЕДЫДУЩИХ ЗАНЯТИЙ: <br>--}}
                    {{--<small style="color: grey;">--}}
                        {{--<i>В этом разделе сохраняются записи всех занятий с момента старта обучения на курсе «Я — личность»</i>--}}
                    {{--</small>--}}
                {{--</h2>--}}

            {{--</div>--}}

            {{--<div class="well" style="padding: 0 19px 0 19px">--}}
                {{--<h2 style="line-height: 1">СТАРТОВОЕ ЗАНЯТИЕ: <br>--}}
                    {{--<small style="color: grey;">--}}
                        {{--<i>Эфиры открытые для просмотра всеми желающими (даже пользователями, не имеющими активного абонемента)</i>--}}
                    {{--</small>--}}
                {{--</h2>--}}
                {{--<br>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-12 col-md-4">--}}
                                {{--<div class="text-center">--}}
                                    {{--<video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/IMG_6753.mp4'"--}}
                                                  {{--:poster="'/img/courses/wakeupera-wide.png'">--}}
                                    {{--</video-player>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-12 col-md-8">--}}
                                {{--<h4>--}}
                                    {{--<b>Тема эфира:</b> <i></i><br>--}}
                                    {{--<b>Описание:</b> <i></i><br>--}}
                                    {{--<b>Спикер(ы):</b> <i></i><br>--}}
                                    {{--<b>Дата эфира:</b> <i></i>--}}
                                {{--</h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<hr>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-12 col-md-4">--}}
                                {{--<div class="text-center">--}}
                                    {{--<video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/IMG_6753.mp4'"--}}
                                                  {{--:poster="'/img/courses/wakeupera-wide.png'">--}}
                                    {{--</video-player>--}}
                                    {{--<ul class="list-inline padding-10 text-center" style="visibility: hidden">--}}
                                        {{--<li>--}}
                                            {{--<i class="fa fa-eye"></i>--}}
                                            {{--0 Просмотров--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-12 col-md-8">--}}
                                {{--<h4>--}}
                                    {{--<b>Тема эфира:</b> <i></i><br>--}}
                                    {{--<b>Описание:</b> <i></i><br>--}}
                                    {{--<b>Спикер(ы):</b> <i></i><br>--}}
                                    {{--<b>Дата эфира:</b> <i></i>--}}
                                {{--</h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<!-- LEFT -->--}}
        {{--<div class="col-sm-3">--}}
            {{--<div class="well">--}}
                {{--<h2>ПРОМО-РОЛИКИ: <br>--}}
                {{--</h2>--}}
                {{--<h5 class="margin-top-0"><i class="fa fa-video-camera"></i> ПРОМО-РОЛИКИ:</h5>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-lg-12">--}}
                        {{--<div class="margin-top-10">--}}
                            {{--<video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/IMG_6753.mp4'"--}}
                                          {{--:poster="'/img/courses/wakeupera-wide.png'">--}}
                            {{--</video-player>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-lg-12">--}}
                        {{--<div class="margin-top-10">--}}
                            {{--<video-player :public-link="'https://sib.object.pscloud.io/wakeapuera-free/IMG_6754.mp4'"--}}
                                          {{--:poster="'/img/courses/wakeupera-wide.png'">--}}
                            {{--</video-player>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-lg-12">--}}
                        {{--<div class="margin-top-10">--}}
                            {{--<video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/WakeUpERA.mp4'"--}}
                                          {{--:poster="'/img/courses/wakeupera-wide.png'">--}}
                            {{--</video-player>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}