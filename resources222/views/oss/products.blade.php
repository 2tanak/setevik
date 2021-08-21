@extends('layouts.app')

@section('title', 'Абонементы')

@section('content')
    <div class="row">
        <div class="well">
            @if (auth()->user()->cabinet->id == \App\Models\Cabinet::SIB)
                <h2>Online Smart System / Абонементы</h2>
            @else
                <h2>Абонементы</h2>
            @endif
            
            <oss-products :products="{{ $data }}" :curator="{{ $curator }}"></oss-products>
        </div>
    </div>
@endsection