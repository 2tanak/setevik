@extends('layouts.app')

@section('title', 'Активность')

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
        {{--<div class="well">--}}
            {{--<h2>Финансы / Активность</h2>--}}
            {{--<div class="row">--}}
                {{--<div class="col-sm-6">--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered m-b-0 table-purse">--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                                {{--<td class="bg-color-lightorange">--}}
                                    {{--<b>Дата окончания текущей активности:</b>--}}
                                {{--</td>--}}
                                {{--<td class="text-center">21.02.2021 15:30:30</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td class="bg-color-lightorange">--}}
                                    {{--<b>Сумма продаж текущего периода активности, $:</b>--}}
                                {{--</td>--}}
                                {{--<td class="text-center">0.00</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-6">--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered m-b-0 table-purse">--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                                {{--<td class="bg-color-lightorange">--}}
                                    {{--<b>Дата окончания следующей активности:</b>--}}
                                {{--</td>--}}
                                {{--<td class="text-center">21.02.2021 15:30:30</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td class="bg-color-lightorange">--}}
                                    {{--<b>Сумма продаж следующего периода активности:</b>--}}
                                {{--</td>--}}
                                {{--<td class="text-center">0.00</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="well">--}}
            {{--<h2>Мои продажи</h2>--}}
            {{--<div class="table-responsive">--}}
                {{--<table class="table table-striped table-hover table-bordered">--}}
                    {{--<tbody>--}}
                        {{--<tr>--}}
                            {{--<th class="text-center">Дата</th>--}}
                            {{--<th class="text-center" style="width: 60%">Продукт</th>--}}
                            {{--<th class="text-center">Сумма, $</th>--}}
                            {{--<th class="text-center">Инициатор, ID</th>--}}
                            {{--<th class="text-center">Система</th>--}}
                        {{--</tr>--}}

                        {{--@foreach ($sales as $sale)--}}
                            {{--<tr>--}}
                                {{--<td class="text-center">{{ $sale->created_at->format('d.m.Y H:i:s') }}</td>--}}
                                {{--<td class="text-center">{{ $sale->product->name }}</td>--}}
                                {{--<td class="text-right">{{ $sale->price }}</td>--}}
                                {{--<td class="text-center">--}}
                                    {{--<a href="Javascript:void(0);">--}}
                                        {{--{{ $sale->customer->getPublicId() }}--}}
                                    {{--</a>--}}
                                {{--</td>--}}
                                {{--<td></td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}

                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}
