@extends('layouts.app')

@section('title', 'Обучение')

{{-- @section('content')
    <div class="row attestation-wrapper">

        @foreach ($types as $k => $type)

            <div class="widget-body">
                <div class="panel-group smart-accordion-default">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">
                                <a class="collapsed btnToggles" href="Javascript:void(0);" data-block-id="block-{{ $k }}" aria-expanded="true">
                                    @if($k == 0)
                                        <i class="fa fa-lg fa-angle-up pull-right"></i>
                                        <i class="fa fa-lg fa-angle-down pull-right"></i>
                                    @else
                                        <i class="fa fa-lg fa-angle-down pull-right"></i>
                                        <i class="fa fa-lg fa-angle-up pull-right"></i>
                                    @endif
                                    <h2 style="line-height: 1">{{ $type->name }} ({{ $type->videos->count() }} видео)</h2>
                                </a>
                            </h2>
                        </div>
                        <div id="block-{{ $k }}" class="panel-collapse collapse binary-info-wrapper" aria-expanded="true" style="height: 0px;">
                            <br>
                            <div class="row" style="padding: 0 19px 10px 0">
                                @foreach ($type->videos as $key => $item)
                                    @if ($item->hasAccess)
                                        <div class="col-xs-12">
                                            @if($key > 0)
                                                <hr>
                                            @endif
                                            <div class="row">
                                                <div class="col-xs-12 col-md-3">
                                                    <div class="text-center">
                                                        <video-player-fake
                                                                :link="'/oss/attestation/{{ $item->id }}'"
                                                                :poster="'/img/courses/wakeupera-wide.png'">
                                                        </video-player-fake>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-9">
                                                    <h4>
                                                        @if(!$item->confirms->contains('user_id', auth()->id()))
                                                            <span rel="tooltip" class="badge bg-color-red bounceIn animated" data-placement="right" data-html="true">New</span><br>
                                                        @endif
                                                        <b>Тема эфира:</b> <i>{{ $item->title }}</i><br>
                                                        <b>Описание:</b> <i>{{ $item->description }}</i><br>
                                                        <b>Спикер(ы):</b> <i>{{ $item->speaker }}</i><br>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endforeach

    </div>
@endsection --}}

@section('content')
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
        <div class="lockscreen animated flipInY">
            <div class="row">
                <div class="padding-20 text-center">
                    <p>Обучающие материалы доступны на платформе <strong>GetCourse</strong>.
                        <br />Для авторизации и доступа Вам необходимо написать Специалисту по заботе о Резидентах OSS - Оксане в Telegram по нику <a href="https://t.me/era_school_02" target="_blank" rel="noreferrer nofollow">@era_school_02</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        $(document).ready(function() {
            $('.btnToggles').on('click', function () {
                $('.btnToggles').each(function (k, v) {
                    let block = $('#'+$(v).attr('data-block-id'));
                    if (block.hasClass('in')) {
                        toogle(v);
                    }
                });
                toogle(this);
            });

            function toogle(el) {
                let th = $(el);
                th.find('.fa-angle-up').toggle();
                th.find('.fa-angle-down').toggle();
                $('#'+th.attr('data-block-id')).collapse('toggle');
            }

            function update() {
                let hasNew = false;

                $('.btnToggles').each(function (k, v) {
                    let th = $(v);

                    $('#'+th.attr('data-block-id')).find('h4').each(function (key, value) {
                        if ($(value).find('span').length) {
                            th.click();
                            hasNew = true;
                            return;
                        }
                    });
                });

                if (!hasNew) {
                    $('.btnToggles').first().click();
                }
            }

            update();
        });
    </script>
@endsection