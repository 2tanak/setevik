@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-users"></i>
                </span>
                <h2>Пользователи</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <div>
                        <form action="/admin/users" method="get">
                            <div class="input-group">
                                <input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="Найти пользователя...">
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Поиск
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>

                    <div class="users-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Дата активации (SIB)</th>
                                    <th class="text-center">Дата активации (OSS)</th>
                                    <th class="avatar text-center">Pic</th>
                                    <th class="text-center">Логин</th>
                                    <th class="text-center">Имя</th>
                                    <th class="text-center">Фамилия</th>
                                    <th class="text-center">Город</th>
                                    <th class="text-center">Мобильный телефон</th>
                                    <th class="text-center">Статус</th>
                                    <th class="text-center">Пакет</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr class="{{ $item->isPartner() ?  ($item->activated_at ? '' : 'new-user') : ($item->oss_activated_at ? '' : 'new-user') }}">
                                        <td class="text-center">
                                            {{ $item->id }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->activated_at)
                                                {{ $item->activated_at->format('d.m.Y H:i:s') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->oss_activated_at)
                                                {{ $item->oss_activated_at->format('d.m.Y H:i:s') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ $item->photo }}" alt="photo" width="20">
                                        </td>
                                        <td class="text-center">
                                            <a href="/admin/users/{{ $item->id }}">
                                                {{ $item->login }}
                                            </a>
                                        </td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->last_name }}</td>
                                        <td class="text-center">{{ $item->city }}</td>
                                        <td class="text-center">{{ $item->phone }}</td>
                                        <td class="text-center">
                                            @if ($item->status->id)
                                                {{ $item->status->short_name }}
                                            @else
                                                Нет
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->package)
                                                <span class="label label-{{ $item->package->code }}">
                                                {{ $item->package->name }}
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
