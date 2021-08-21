@extends('layouts.app')

@section('title', 'Пользователь')

@section('content')
    <user :user="{{ json_encode($user) }}" :countries="{{ $countries }}" :statuses="{{ $statuses }}"></user>
@endsection
