@extends('layouts.app')

@section('title', 'Аттестация')

@section('content')
    <div class="row">
        <div class="well">
            <h2>Обучение / {{ $video->type->name }} / {{ $video->title }}</h2>

            <oss-attestation-detail :video="{{ $video }}"
                                    :poster="'/img/courses/wakeupera-wide.png'"
                                    :watched-api="'/oss/attestation/{{ $video->id }}/watched'">
            </oss-attestation-detail>

            <br>
            <br>
            <br>
            <a class="btn btn-primary" href="/oss/attestation">Возврат к списку</a>
        </div>
    </div>
@endsection