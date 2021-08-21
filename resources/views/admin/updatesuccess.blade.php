@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-users"></i>
                </span>
                <h2>Ручное начисление баллов пользователям </h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body text-center">
                    <h1> Баллы успешно начислены </h1>
                    <a href="/admin/users" class="btn txt-color-white bg-color-green btn-sm">Перейти к пользователям</a>
                </div>
            </div>
        </div>
    </div>
@endsection





