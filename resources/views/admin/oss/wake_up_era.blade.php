@extends('layouts.app')

@section('title', 'WakeUpEra')

@section('content')
    <div class="row">

        <admin-oss-wakeupera-broadcast :broadcast-data="{{ $broadcasts }}"></admin-oss-wakeupera-broadcast>

        <admin-oss-wakeupera-broadcast-video :grid="{{ $videos }}"></admin-oss-wakeupera-broadcast-video>

    </div>
@endsection