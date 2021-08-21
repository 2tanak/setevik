@extends('layouts.app')

@section('title', 'Журнал')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-newspaper-o"></i>
                </span>
                <h2>Журнал</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                    <div class="report-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    {{--<th>&#8470;</th>--}}
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Дата</th>
                                    <th class="text-center">Действие</th>
                                    <th class="text-center">Данные</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $item->id }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->created_at->format('d.m.Y H:i:s') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->eventType->name }}
                                        </td>
                                        <td class="text-center">
                                            @if (isset($item->data['name']))
                                                {{ $item->data['name'] }} {{ $item->data['last_name'] }}
                                            @else
                                                {{ \App\User::find($item->data['user_id'])->getFullName() }}
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{--                <div class="text-center">--}}
                {{--                    {{ $data->links() }}--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
