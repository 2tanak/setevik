@extends('layouts.app')

@section('title', 'WakeUpEra')

@section('content')
    <div class="row">
        <div class="well" style="padding: 0 19px 0 19px">
            @if(auth()->user()->isActivated())

                @if($hasActiveSubscription)
                    <h2 style="line-height: 1">
                        <b>ССЫЛКА НА ПРЯМОЙ ЭФИР

                        @if (\Carbon\Carbon::now()->subHours(5)->subSeconds(1)->isToday())
                            @if (\Carbon\Carbon::now()->isDayOfWeek(5))
                                {{ \Carbon\Carbon::now()->addDays(3)->format('d.m.Y') }} г.:
                            @elseif(\Carbon\Carbon::now()->isDayOfWeek(6))
                                {{ \Carbon\Carbon::now()->addDays(2)->format('d.m.Y') }} г.:
                            @else
                                {{ \Carbon\Carbon::tomorrow()->format('d.m.Y') }} г.:
                            @endif
                        @else
                            {{ \Carbon\Carbon::now()->format('d.m.Y') }} г.:
                        @endif
                        </b>
                        <br>
                        <small style="color: grey;">
                            <i>В назначенное время в этом разделе появится кнопка, нажав на которую Вы попадёте на прямой эфир #WakeUpERA в приложении ZOOM</i>
                        </small>
                    </h2>
                    <div>
                        @if ($broadcast)
                            <a href="{{ $broadcast->link }}" target="_blank" class="btn btn-success">Перейти в эфир</a>
                        @else
                            <p class="txt-color-green">
                                <b><i>ЗДЕСЬ БУДЕТ КНОПКА</i></b>
                            </p>
                        @endif
                    </div>
                    <br>
                    <h4>ПОДРОБНАЯ ИНСТРУКЦИЯ ДЛЯ ПОДКЛЮЧЕНИЯ К ЭФИРУ:</h4>
                    <p>
                        ⠀⠀1. Скачать приложение ZOOM с AppStore или PlayMarket<br>

                        ⠀⠀2. Нажать на кнопку выше <b>«Перейти в эфир»</b> <i>(появится над этим блоком в 3-30 утра по московскому времени)</i> <br>

                        ⠀⠀3. В поле «Имя экрана» ввести Имя, Фамилию и в скобках город проживания (обязательно!!!). Например: Иван Петров (Москва)<br>

                        ⠀⠀4. Установить флажки в положение ВКЛ и нажать «Войти»<br>

                        ⠀⠀5. В случае запроса приложением доступа к микрофону нажать кнопку «Разрешить»<br>

                        ⠀⠀6.1. Для включения камеры (своего видео) нажать иконку в левом нижнем углу в виде видеокамеры<br>

                        ⠀⠀6.2. В случае запроса приложением доступа к камере нажать кнопку «Разрешить»<br>

                        ⠀⠀7.1. Если Вы не слышите звук, то нужно нажать на иконку в левом нижнем углу в виде наушников<br>

                        ⠀⠀7.2. Далее нажать на кнопку «Вызов с использованием звука через Интернет»<br>
                    </p>
                    <br>
                @else
                    <h2 style="line-height: 1">ССЫЛКА НА ПРЯМОЙ ЭФИР: <br>
                        <small style="color: grey;">
                            <i>В назначенное время в этом разделе появится кнопка, нажав на которую Вы попадёте на прямой эфир #WakeUpERA в приложении ZOOM</i>
                        </small>
                    </h2>
                    <p style="color: #FF5252"><i>Данный раздел доступен только при активном абонементе на Платформе Online Smart System</i></p>
                @endif
            @else
                <h2 style="line-height: 1">ССЫЛКА НА ПРЯМОЙ ЭФИР: <br>
                    <small style="color: grey;">
                        <i>В назначенное время в этом разделе появится кнопка, нажав на которую Вы попадёте на прямой эфир #WakeUpERA в приложении ZOOM</i>
                    </small>
                </h2>
                <p style="color: #FF5252"><i>Данный раздел доступен только после активациии абонемента на Платформе Online Smart System</i></p>
            @endif
        </div>
    </div>

    <div class="row wakeupera-wrapper" style="margin-left: -25px; margin-right: -25px">
        <!-- #RIGHT -->
        <div class="col-sm-9">

            <div class="well">
                <h2><b>ЗАПИСЬ ТЕКУЩЕГО ЭФИРА:</b> <br>
                    <small style="color: grey;">
                        <i>Запись последнего прошедшего эфира #WakeUpERA (доступна до 6 утра по Мск следующего дня)</i>
                    </small>
                </h2>

                @if(auth()->user()->isActivated())

                    @if($hasActiveSubscription)
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                @if ($video)
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="text-center">
                                                <video-player :link="'/oss/wake-up-era/broadcast-videos/{{ $video->id }}/source'"
                                                              :poster="'/img/courses/wakeupera-wide.png'"
                                                              :watched-api="'/oss/wake-up-era/{{ $video->id }}/watched'">
                                                </video-player>
                                                <ul class="list-inline padding-10 text-center">
                                                    <li>
                                                        <i class="fa fa-eye"></i>
                                                        {{ $video->watches()->count() }} Просмотров
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-md-8">
                                            <h4>
                                                <b>Тема эфира: </b><i>{{ $video->title }}</i> <br>
                                                <b>Описание:</b> <i>{{ $video->description }}</i><br>
                                                <b>Спикер(ы):</b> <i>{{ $video->speaker }}</i><br>
                                                <b>Дата эфира:</b> <i>{{ $video->date->format('d.m.Y') }}</i>
                                            </h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <p style="color: #FF5252"><i>Данный раздел доступен только при активном абонементе на Платформе Online Smart System</i></p>
                    @endif
                @else
                    <p style="color: #FF5252"><i>Данный раздел доступен только после активациии абонемента на Платформе Online Smart System</i></p>
                @endif
            </div>

            <div class="well">
                <h2><b>ЗАПИСИ ЭФИРОВ В ПОСТОЯННОМ ДОСТУПЕ:</b> <br>
                    <small style="color: grey;">
                        <i>Полезные эфиры, оставленные в доступе для Резидентов Платформы OSS (с активным абонементом) на постоянной основе</i>
                    </small>
                </h2>
                @if(auth()->user()->isActivated())
                    @if($hasActiveSubscription)
                        <br>
                        <div class="row">
                            @if($videos)
                                @foreach ($videos as $key  => $item)
                                    <div class="col-xs-12">
                                        @if($key > 0)
                                            <hr>
                                        @endif
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4">
                                                <div class="text-center">
                                                    <video-player :link="'/oss/wake-up-era/broadcast-videos/{{ $item->id }}/source'"
                                                                  :poster="'/img/courses/wakeupera-wide.png'"
                                                                  :watched-api="'/oss/wake-up-era/{{ $item->id }}/watched'">
                                                    </video-player>
                                                    <ul class="list-inline padding-10 text-center">
                                                        <li>
                                                            <i class="fa fa-eye"></i>
                                                            {{ $item->watches()->count() }} Просмотров
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <h4>
                                                    <b>Тема эфира:</b> <i>{{ $item->title }}</i><br>
                                                    <b>Описание:</b> <i>{{ $item->description }}</i><br>
                                                    <b>Спикер(ы):</b> <i>{{ $item->speaker }}</i><br>
                                                    {{--<b>Дата эфира:</b> <i>{{ $item->date->format('d.m.Y') }}</i>--}}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @else
                        <p style="color: #FF5252"><i>Данный раздел доступен только при активном абонементе на Платформе Online Smart System</i></p>
                    @endif
                @else
                    <p style="color: #FF5252"><i>Данный раздел доступен только после активациии абонемента на Платформе Online Smart System</i></p>
                @endif
            </div>

            <div class="well">
                <h2><b>ЗАПИСИ ЭФИРОВ В ОТКРЫТОМ ДОСТУПЕ:</b> <br>
                    <small style="color: grey;">
                        <i>Эфиры открытые для просмотра всеми желающими (даже пользователями, не имеющими активного абонемента)</i>
                    </small>
                </h2>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/1%20%D0%B8%D0%B7%202.%20%D0%A0%D0%BE%D0%B7%D1%8B%D0%B3%D1%80%D1%8B%D1%88%20%2415%20%D0%BC%D0%BB%D0%BD%20%D1%81%D1%80%D0%B5%D0%B4%D0%B8%20%D0%A0%D0%B5%D0%B7%D0%B8%D0%B4%D0%B5%D0%BD%D1%82%D0%BE%D0%B2%20OSS.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Челлендж «Выиграй миллион долларов с OSS». Часть 1 из 2.</i><br>
                                    <b>Описание:</b> <i>Эфир от 16 сентября 2020 г. При регистрации на Платформе каждый действующий резидент Online Smart System получает билет, который становится участником денежных розыгрышей командного челленджа «Выграй миллион долларов с OSS».
                                        <br>⠀⠀В рамках этого челленджа на Платформе ежемесячно проводится розыгрыш денег с общим призовым фондом $15,322,500. Сумма выгрышей постоянно повышается, вплоть до розыгрыша нескольких главных призов размером $1,000,000. Несколько резидентов Платформы станут реальными долларовыми миллионерами. Одним из них можешь стать Ты.
                                        <br>⠀⠀В этом видео Ты узнаешь общие условия челленджа. Включай скорее.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/2%20%D0%B8%D0%B7%202.%20%D0%A0%D0%BE%D0%B7%D1%8B%D0%B3%D1%80%D1%8B%D1%88%20%2415%20%D0%BC%D0%BB%D0%BD%20%D1%81%D1%80%D0%B5%D0%B4%D0%B8%20%D0%A0%D0%B5%D0%B7%D0%B8%D0%B4%D0%B5%D0%BD%D1%82%D0%BE%D0%B2%20OSS.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Челлендж «Выиграй миллион долларов с OSS». Часть 2 из 2.</i><br>
                                    <b>Описание:</b> <i>Эфир от 16 сентября 2020 г. В этом видео Ты узнаешь механику (как именно?) будет достигаться амбициозная цель один миллион Резидентов на Платформе OSS. А также узнаешь, как можно повысить свои шансы на выигрыш главного приза, который несомненно изменит Твою жизнь.
                                        <br>⠀⠀Главное помни: большие результаты делаются ТОЛЬКО КОМАНДОЙ! Поэтому присоединяйся к нашей дружной команде и давай вместе менять мир к лучшему.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/%D0%A7%D0%B5%D0%BB%D0%BB%D0%B5%D0%BD%D0%B4%D0%B6%20%C2%AB%D0%92%D1%8B%D0%B8%D0%B3%D1%80%D0%B0%D0%B9%20%D0%BC%D0%B8%D0%BB%D0%BB%D0%B8%D0%BE%D0%BD%20%D0%B4%D0%BE%D0%BB%D0%BB%D0%B0%D1%80%D0%BE%D0%B2%C2%BB.%20%D0%94%D0%B5%D0%BD%D0%B5%D0%B6%D0%BD%D1%8B%D0%B9%20%D1%80%D0%BE%D0%B7%D1%8B%D0%B3%D1%80%D1%8B%D1%88%20%D0%BE%D1%82%2004.10.20%20%D0%B3.%20%28%D0%B2%D1%81%29.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Первый исторический розыгрыш Челленджа «Выиграй миллион долларов с OSS».</i><br>
                                    <b>Описание:</b> <i>Эфир от 4 октября 2020 г. В этом видео Ты узнаешь механику (как именно?) будет достигаться амбициозная цель один миллион Резидентов на Платформе OSS. А также узнаешь, как можно повысить свои шансы на выигрыш главного приза, который несомненно изменит Твою жизнь.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/%D0%93%D0%BB%D0%B0%D0%B2%D0%BD%D0%B0%D1%8F%20%D0%BF%D0%BE%D1%82%D1%80%D0%B5%D0%B1%D0%BD%D0%BE%D1%81%D1%82%D1%8C%20%D0%BA%D0%B0%D0%B6%D0%B4%D0%BE%D0%B3%D0%BE%20%D1%87%D0%B5%D0%BB%D0%BE%D0%B2%D0%B5%D0%BA%D0%B0%20%28%D1%871%29%20.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Главная потребность каждого человека. Часть 1.</i><br>
                                    <b>Описание:</b> <i>Эфир #WakeUpERA от 6 мая 2020 г. У каждого человека есть базовые потребности, которые ему нужно удовлетворять, независимо от пола, возраста, цвета кожи, нации, расы.
                                        <br>⠀⠀Когда речь заходит об этих потребностях, то чаще всего имеются ввиду физиологические потребности, такие как потребность в воздухе, еде, питье, жилье, сексе и т.п. Но есть одна, самая главная, фундаментальная потребность каждого существа, о которой мало кто задумывается и пытается разумно управлять этим процессом. И это не потребность в счастье. О ней и говорится в этом эфире, являющемся только первой частью рассуждений на эту тему.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/%D0%9A%D0%B0%D0%BA%20%D0%BF%D0%BE%D0%BB%D1%8E%D0%B1%D0%B8%D1%82%D1%8C%20%D1%81%D0%B5%D0%B1%D1%8F%201%20-%2015.05.20.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Как полюбить себя? Часть 1.</i><br>
                                    <b>Описание:</b> <i>Эфир #WakeUpERA от 15 мая 2020 г. Душетрепещущий эфир о том, откуда у большинства людей проблемы с личной жизнью, в отношениях с окужающими, со здоровьем. Как человек из счастливого, беззаботного, раскрепощённого ребёнка превращается в закрытого, держащего всё в себе и желающего угодить всем взрослого.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="text-center">
                                    <video-player :public-link="'https://project.object.pscloud.io/wakeupera-free/%D0%9A%D0%B0%D0%BA%20%D1%80%D0%B5%D1%88%D0%B0%D1%82%D1%8C%20%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D0%BC%D1%8B%20%D1%87%D0%B0%D1%81%D1%82%D1%8C%201.mp4'"
                                                  :poster="'/img/courses/wakeupera-wide.png'">
                                    </video-player>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <h4>
                                    <b>Тема эфира:</b> <i>Как решать проблемы? Часть 1 из 3.</i><br>
                                    <b>Описание:</b> <i>Эфир от 8 окт. 2020 г. Разбор фундаментальных, базовых причин возникновения проблем в жизни человека, объяснение природы их происхождения. После этого эфира Ты точно поймёшь, почему в жизни людей бывает так много проблем. Ну и перед этим конечно зажигательные танцы)
                                        <br>⠀⠀Во 2 и 3 части этой темы разбирается универсальный 100% рабочий алгоритм решения любых проблем. Он состоит из понимания его работы и четырёх простых шагов, использование которых приведёт Тебя к состоянию, в котором жизнь без проблем это норма!
                                        <br>⠀⠀Данные эфиры доступны к изучению для резидентов Платформы.</i><br>
                                    <b>Спикер(ы):</b> <i>Ерлан Ахметов</i><br>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LEFT -->
        <div class="col-sm-3">
            <div class="well">
                <h2><b>ПРОМО-РОЛИКИ:</b> <br></h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="margin-top-10">
                            <video-player :public-link="'https://project.object.pscloud.io/wakeupera-promo/IMG_6753.mp4'"
                                          :poster="'/img/courses/wakeupera-wide.png'">
                            </video-player>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="margin-top-10">
                            <video-player :public-link="'https://project.object.pscloud.io/wakeupera-promo/IMG_6754.mp4'"
                                          :poster="'/img/courses/wakeupera-wide.png'">
                            </video-player>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="margin-top-10">
                            <video-player :public-link="'https://project.object.pscloud.io/wakeupera-promo/WakeUpERA.mp4'"
                                          :poster="'/img/courses/wakeupera-wide.png'">
                            </video-player>
                        </div>
                    </div>
                    {{--<div class="col-lg-12">--}}
                        {{--<div class="margin-top-10 text-center media-embed">--}}
                            {{--<iframe--}}
                                    {{--src="https://www.youtube.com/embed/7qaElC_sFAM"--}}
                                    {{--frameborder="0"--}}
                                    {{--allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"--}}
                                    {{--allowfullscreen>--}}
                            {{--</iframe>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection