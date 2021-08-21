<div class="okno"></div>
@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
       <div class="well">
            <h2>Мой профиль</h2>
               <profiles :country ="{{$country}}" :item="{{ json_encode($user) }}"></profiles>
           </div>
@endsection






