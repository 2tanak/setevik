@extends('layouts.app')

@section('title', 'Обучение')

@section('content')
    <div class="row">

        <admin-oss-attestation :grid="{{ $attestations }}" :types="{{ $types }}"></admin-oss-attestation>

    </div>
@endsection