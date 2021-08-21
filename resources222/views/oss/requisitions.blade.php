@extends('layouts.app')

@section('title', 'Заявки')

@section('content')
    <div class="row">
        <div class="well">
            <h2>
                @if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)
                    Online Smart System / Заявки
                @else
                    Заявки
                @endif

                <sup class="badge bg-color-grey bounceIn animated"
                     rel="tooltip"
                     data-placement="right"
                     data-original-title="<i class='text-success'></i> Заявки для подтверждения от новых Резидентов будут поступать сюда"
                     data-html="true">
                    ?
                </sup>
            </h2>

            <oss-requisitions :requisition-data="{{ json_encode($data) }}"></oss-requisitions>
        </div>
    </div>
@endsection