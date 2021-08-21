@extends('layouts.app')

@section('title', 'Я и моя команда')

@section('content')
    <div class="row">
        <div class="widget-body">
            <div class="panel-group smart-accordion-default" id="binary-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a id="btnToggle" data-parent="#binary-info" href="Javascript:void(0);" aria-expanded="false" class="collapsed">
                                <i class="fa fa-lg fa-angle-down pull-right"></i>
                                <i class="fa fa-lg fa-angle-up pull-right"></i>
                                <h2>Мои данные</h2>
                            </a>
                        </h2>
                    </div>
                    <div id="info" class="panel-collapse collapse binary-info-wrapper" aria-expanded="false" style="height: 0px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered m-b-0 table-purse">
                                        <tbody>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Дата регистрации:</b> </td>
                                            <td>{{ $user->activated_at->format('d.m.Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Ф.И.О.:</b> </td>
                                            <td>{{ $user->getFullName() }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Пакет:</b> </td>
                                            <td>{{ $user->package->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Мой куратор:</b> </td>
                                            <td>{{ $curatorFullName }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Команда куратора:</b> </td>
                                            <td>{{ $team }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered m-b-0 table-purse">
                                        <tbody>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Статус:</b> </td>
                                            {{--<td>{{ $user->status->name }}</td>--}}
                                            <td>
                                                <i class="txt-color-redLight">В разработке</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>Квалификация:</b> </td>
                                            <td>{{ $user->is_qualified ? 'Квалифицирован' : 'Не квалифицирован' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>ФЦ за ФП (неделя):</b> </td>
                                            <td>
                                                <i class="txt-color-redLight">В разработке</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>ФЦ за месяц:</b> </td>
                                            <td>
                                                <i class="txt-color-redLight">В разработке</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-color-lightorange"><b>ФЦ за промо период:</b></td>
                                            <td>
                                                <i class="txt-color-redLight">В разработке</i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="well">
            <h2>Бинарная схема (схема команды)</h2>
			
            <binary-tree :tree="{{$tree}}"></binary-tree>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        $(document).ready(function() {
            $('#btnToggle').on('click', function () {
                let th = $(this);
                th.find('.fa-angle-up').toggle();
                th.find('.fa-angle-down').toggle();
                $('#info').collapse('toggle');
            })
        });
    </script>
@endsection