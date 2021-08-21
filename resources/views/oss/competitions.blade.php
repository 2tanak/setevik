@extends('layouts.app')

@section('title', 'Соревнования')

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
           {{--@if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)--}}
               {{--<h2>Online Smart System / Соревнования</h2>--}}
           {{--@else--}}
               {{--<h2>Соревнования</h2>--}}
           {{--@endif--}}

           {{--<div class="row">--}}
               {{--<div class="col-sm-12">--}}
                   {{--<div class="col-sm-6">--}}
                       {{--<table class="highchart table table-hover table-bordered" data-graph-container=".. .. .highchart-container2" data-graph-type="column">--}}
                           {{--<caption>Ваши достижения</caption>--}}
                           {{--<thead>--}}
                           {{--<tr>--}}
                               {{--<th>Month</th>--}}
                               {{--<th class="">Sales</th>--}}
                               {{--<th class="">Benefits</th>--}}
                               {{--<th class="">Expenses</th>--}}
                               {{--<th class="">Prediction</th>--}}
                           {{--</tr>--}}
                           {{--</thead>--}}
                           {{--<tbody>--}}
                           {{--<tr>--}}
                               {{--<td>January</td>--}}
                               {{--<td class="">8000</td>--}}
                               {{--<td class="">2000</td>--}}
                               {{--<td class="">1000</td>--}}
                               {{--<td class="">9000</td>--}}
                           {{--</tr>--}}
                           {{--<tr>--}}
                               {{--<td>February</td>--}}
                               {{--<td class="">12000</td>--}}
                               {{--<td class="">3000</td>--}}
                               {{--<td class="">1300</td>--}}
                               {{--<td class="">10000</td>--}}
                           {{--</tr>--}}
                           {{--<tr>--}}
                               {{--<td>March</td>--}}
                               {{--<td class="">18000</td>--}}
                               {{--<td class="">4000</td>--}}
                               {{--<td class="">1240</td>--}}
                               {{--<td class="">11000</td>--}}
                           {{--</tr>--}}
                           {{--<tr>--}}
                               {{--<td>April</td>--}}
                               {{--<td class="">2000</td>--}}
                               {{--<td class="">-1000</td>--}}
                               {{--<td class="">-150</td>--}}
                               {{--<td class="">13000</td>--}}
                           {{--</tr>--}}
                           {{--<tr>--}}
                               {{--<td>May</td>--}}
                               {{--<td class="">500</td>--}}
                               {{--<td class="">-2500</td>--}}
                               {{--<td class="">1000</td>--}}
                               {{--<td class="">14000</td>--}}
                           {{--</tr>--}}
                           {{--<tr>--}}
                               {{--<td>June</td>--}}
                               {{--<td class="">600</td>--}}
                               {{--<td class="">-500</td>--}}
                               {{--<td class="">-500</td>--}}
                               {{--<td class="">15000</td>--}}
                           {{--</tr>--}}
                           {{--</tbody>--}}
                       {{--</table>--}}
                   {{--</div>--}}
                   {{--<div class="col-sm-6">--}}
                       {{--<div class="highchart-container2"></div>--}}
                   {{--</div>--}}
               {{--</div>--}}
               {{--<script>--}}
                   {{--$('table.highchart').highchartTable();--}}
               {{--</script>--}}
           {{--</div>--}}
       {{--</div>--}}
   {{--</div>--}}
{{--@endsection--}}