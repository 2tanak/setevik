@extends('layouts.app')

@section('title', 'Команда')

@section('content')
    <div class="row">
        <div class="well">
            <h2>
                @if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)
                    Online Smart System / Команда
                @else
                    Команда
                @endif

                <sup class="badge bg-color-grey bounceIn animated"
                     rel="tooltip"
                     data-placement="right"
                     data-original-title="<i class='text-success'></i>Ваша структура Резидентов OSS"
                     data-html="true">
                    ?
                </sup>
            </h2>
			
            <oss-tree :tree-data="{{ $data }}" data-time="{{\Carbon\Carbon::now()}}"></oss-tree>
        </div>
    </div>
@endsection