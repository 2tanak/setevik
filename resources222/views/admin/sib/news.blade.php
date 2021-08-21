@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-newspaper-o"></i>
                </span>
                <h2>Новости</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <a href="/admin/sib/news/0" class="btn btn-default">Добавить</a>
                    <div class="clb"></div>
                    <br>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Название</th>
                                    <th class="text-center">Активность</th>
                                    <th class="text-center">Дата изменения</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $item->id }}
                                        </td>
                                        <td>
                                            <a href="/admin/sib/news/{{ $item->id }}">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->is_active)
                                                Да
                                            @else
                                                Нет
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $item->updated_at->format('d.m.Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
