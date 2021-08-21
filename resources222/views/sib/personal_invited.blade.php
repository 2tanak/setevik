@extends('layouts.app')

@section('title', 'Лично приглашенные')

@section('content')
    <div class="row">
        <div class="well text-center" style="padding-top: 10px">
            <h2>Лично приглашенные ({{ $team_left->count() + $team_right->count() }})</h2>

            @if (($team_left->count() + $team_right->count()) == 0)
                <p class="txt-color-red">
                    <i>Пока у Вас нет лично приглашённых Партнёров</i>
                </p>
            @endif
        </div>
    </div>

    <div class="row personal-invited-wrapper">
        <div class="col-md-6" style="padding-left: 0; padding-right: 5px">
            <h2 style="text-align:center; padding: 10px;">Левая команда ({{ $team_left->count() }})</h2>

            @if ($team_left->count())
                @foreach($team_left as $item)
                    <div class="well">
                        <div class="media">
                            <div class="media-left">
                                <a href="#myModalBox-{{ $item->user->id }}" data-toggle="modal">
                                    <div class="">
                                        <img class="media-object {{ $item->user->has_activity_sib ? 'active' : '' }}" height=50px src="{{ File::exists('./'.$item->user->photo) ? $item->user->photo :
										'/img/avatars/no-photo.jpg'}}">
                                    </div>
                                </a>
                            </div>
                            <div class="media-body">
                                {{ $item->user->getFullName() }}<br>
                                ID: <a href="#myModalBox-{{ $item->user->id }}" data-toggle="modal">{{ $item->user->getPublicId() }}</a><br>
                                Пакет: {{ $item->user->package->name }}
                            </div>
                        </div>
                        <div id="myModalBox-{{ $item->user->id }}" class="modal fade">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Информация о пользователе</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <div class="media-body">
                                                <b>Ф.И.О.:</b> {{ $item->user->getFullName() }}<br>
                                                <b>ID:</b> {{ $item->user->getPublicId() }}<br>
                                                <b>E-mail:</b> {{ $item->user->email }}<br>
                                                <b>Телефон:</b> {{ $item->user->phone }}<br>
                                                <b>Куратор:</b> {{ ($item->user->getCuratorPartner()) ? $item->user->getCuratorPartner()->getFullName() : '' }}<br>
                                                <b>Пакет:</b> {{ $item->user->package->name }}<br>
                                                <b>Дата регистрации:</b> {{ $item->user->activated_at->format('d.m.Y') }}<br>
                                                {{--<b>Статус:</b> {{ $item->user->status->name }}<br>--}}
                                                <b>Квалификация:</b>  {{ $item->user->is_qualified ? 'Есть' : 'Нет' }}<br>
                                                <hr>
                                                <a href="/me-and-my-team/{{ $item->id }}">Посмотреть в бинаре</a> <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="col-md-6" style="padding-right: 0; padding-left: 5px">
            <h2 style="text-align:center; padding: 10px;">Правая команда ({{ $team_right->count() }}) </h2>

            @if ($team_right->count())
                @foreach($team_right as $item)
                    <div class="well">
                        <div class="media">
                            <div class="media-left">
                                <a href="#myModalBox-{{ $item->user->id }}" data-toggle="modal">
                                    <div class="">
                                        <img class="media-object {{ $item->user->has_activity_sib ? 'active' : '' }}" height=50px src="{{ File::exists('./'.$item->user->photo) ? $item->user->photo :
									    '/img/avatars/no-photo.jpg' }}">
                                    </div>
                                </a>
                            </div>
                            <div class="media-body">
                                {{ $item->user->getFullName() }}<br>
                                ID: <a href="#myModalBox-{{ $item->user->id }}" data-toggle="modal">{{ $item->user->getPublicId() }}</a><br>
                                Пакет: {{ $item->user->package->name }}
                            </div>
                        </div>
                        <div id="myModalBox-{{ $item->user->id }}" class="modal fade">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Информация о пользователе</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <div class="media-body">
                                                <b>Ф.И.О.:</b> {{ $item->user->getFullName() }}<br>
                                                <b>ID:</b> {{ $item->user->getPublicId() }}<br>
                                                <b>E-mail:</b> {{ $item->user->email }}<br>
                                                <b>Телефон:</b> {{ $item->user->phone }}<br>
                                                <b>Куратор:</b> {{ ($item->user->getCuratorPartner()) ? $item->user->getCuratorPartner()->getFullName() : '' }}<br>
                                                <b>Пакет:</b> {{ $item->user->package->name }}<br>
                                                <b>Дата регистрации:</b> {{ $item->user->activated_at->format('d.m.Y') }}<br>
                                                {{--<b>Статус:</b> {{ $item->user->status->name }}<br>--}}
                                                <b>Квалификация:</b>  {{ $item->user->is_qualified ? 'Есть' : 'Нет' }}<br>
                                                <hr>
                                                <a href="/me-and-my-team/{{ $item->id }}">Посмотреть в бинаре</a> <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection