@extends('layouts.app')

@section('title', 'Online Smart System - Новости')

@section('content')
    <div class="row">
        <div class="jarviswidget" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-file-pdf-o"></i>
                </span>
                @if ($item->id)
                    <h2>Документация - {{ $item->title }}</h2>
                @else
                    <h2>Документация</h2>
                @endif
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                    <div class="row">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <p>{{ $error }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <documents-detail :item="{{ json_encode($item) }}"></documents-detail>
                </div>
            </div>
        </div>
    </div>
@endsection