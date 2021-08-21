@extends('layouts.app')

@section('title', 'Тестирование')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-cogs"></i>
                </span>
                <h2>Тестирование</h2>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <form action="/admin/test" method="post" class="form-inline">
                        {{ csrf_field() }}
                        {{--{{ method_field('PUT') }}--}}
                        <fieldset>
                            <div class="form-group">
                                <label for="time">Время:</label>
                                <input type="text" name="time" id="time" class="form-control" value="{{ $time }}" placeholder="yyyy-mm-dd hh:mm:ss">
                            </div>
                            <button type="submit" class="btn btn-default">Сохранить</button>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

