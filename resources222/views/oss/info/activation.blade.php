
@extends('layouts.app')

@section('title', 'Активация')

@section('content')

    @if (auth()->user()->is_wizard_activated)
        <oss-activation-wizard :activation-data="{{ $data }}"></oss-activation-wizard>
    @else
        <oss-activation :activation-data="{{ $data }}"></oss-activation>
    @endif
@endsection
