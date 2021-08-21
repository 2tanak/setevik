@extends('layouts.app')

@section('title', 'Активация')

@section('content')

    {{--
        0. Приветсвие (окно с видео)
        1. Wizard 1. Прикрепление чека
        2. Wizard 2. Выбор пакета и вставка реф.ссылки
        3. Wizard 3. Ожидания подтверждения администратора системы

    --}}

    @if (auth()->user()->bePartnerRequest)
        <oss-be-partner-request :activation-data="{{ json_encode($data) }}"></oss-be-partner-request>
    @else
        <oss-activation-partner :activation-data="{{ json_encode($data) }}"></oss-activation-partner>
    @endif
@endsection
