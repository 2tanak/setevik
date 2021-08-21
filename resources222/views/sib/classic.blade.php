@extends('layouts.app')

@section('title', 'Классическая схема')

@section('content')
    <div class="row classic-schema-wrapper">
        <div class="well">
            <h2>Классическая схема ({{ $totalCount }})</h2>

            @if ($totalCount == 0)
                <p class="txt-color-red">
                    <i>Пока в Вашей классической структуре нет цепочки приглашений</i>
                </p>
            @endif

            <div class="panel-collapse collapse in" aria-expanded="true">
                <div class="panel-body">
                    <ul class="nav nav-tabs" role="tablist">
                        @for($level = 1; $level <= env('CLASSIC_TREE_MAX_DEPTH', 3); $level++)
                            <li role="presentation" class="<?=($level == 1) ? 'active' : ''?>">
                                @if(isset($data[$level]))
                                    <a href="#level-{{ $level }}" aria-controls="level-{{ $level }}" role="tab" data-toggle="tab">{{ $level }} уровень ({{ count($data[$level]) }})</a>
                                @else
                                    <a href="#level-{{ $level }}" aria-controls="level-{{ $level }}" role="tab" data-toggle="tab">{{ $level }} уровень (0)</a>
                                @endif
                            </li>
                        @endfor
                    </ul>
                    <div class="tab-content">
                        @for($level = 1; $level <= env('CLASSIC_TREE_MAX_DEPTH', 3); $level++)
                            @if(isset($data[$level]))
                                <div style="padding:25px;" role="tabpanel" class="tab-pane <?=($level == 1) ? 'active' : ''?>" id="level-{{ $level }}">

                                    @foreach($data[$level] as $user)
                                        <div class="media">
                                            <div class="media-left">
                                                <a href="#myModalBox-{{ $user->id }}">
                                                    <img class="media-object {{ $user->has_activity_sib ? 'active' : '' }}"
                                                         data-toggle="modal"
                                                         height=50px
                                                         src="{{ File::exists('./'.$user->photo) ? $user->photo :
														'/img/avatars/no-photo.jpg'
														 }}">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                {{ $user->getFullName() }}<br>
                                                ID: <a href="#myModalBox-{{ $user->id }}" data-toggle="modal">{{ $user->getPublicId() }}</a><br>
                                                Пакет: {{ $user->package->name }}
                                            </div>
                                        </div>
                                        <div id="myModalBox-{{ $user->id }}" class="modal fade">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Информация о пользователе</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>
                                                            <div class="media-body">
                                                                <b>Ф.И.О.:</b> {{ $user->getFullName() }}<br>
                                                                <b>ID:</b> {{ $user->getPublicId() }}<br>

                                                                @if ($level == 1)
                                                                    <b>E-mail:</b> {{ $user->email }}<br>
                                                                    <b>Телефон:</b> {{ $user->phone }}<br>
                                                                @endif
                                                                <b>Куратор:</b> {{ ($user->getCuratorNodePartner()) ? $user->getCuratorPartner()->getFullName() : '' }}<br>
                                                                <b>Пакет:</b> {{ $user->package->name }}<br>
                                                                <b>Дата регистрации:</b> {{ $user->activated_at->format('d.m.Y') }}<br>
                                                                {{--<b>Статус:</b> {{ $user->status->name }}<br>--}}
                                                                <b>Квалификация:</b>  {{ $user->is_qualified ? 'Есть' : 'Нет' }}<br>
                                                                <hr>
                                                                <a href="/me-and-my-team/{{ $user->tree_node_id }}">Посмотреть в бинаре</a> <br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach

                                </div>
                            @else
                                <div style="padding:25px;" role="tabpanel" class="tab-pane <?=($level == 1) ? 'active' : ''?>" id="level-{{ $level }}"></div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection