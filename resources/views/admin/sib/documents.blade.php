@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-file-pdf-o"></i>
                </span>
                <h2>Документация</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <a href="/admin/sib/documents/0" class="btn btn-default">Добавить</a>
                    <div class="clb"></div>
                    <br>

                    {{--                <table class="table table-striped table-forum" style="margin-bottom: auto">--}}
                    <div class="report-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Наименование</th>
                                    <th class="text-center">Дата размещения</th>
                                    <th class="text-center">Количество файлов</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td class="text-center" style="width: 40px;">
                                            {{--<i class="fa fa-book fa-2x text-muted"></i>--}}
                                            <a href="Javascript:void(0);" class="btn btn-xs btn-default" onclick="removeDocument({{ $item->id }})">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <h4>
                                                <a href="/admin/sib/documents/{{ $item->id }}">
                                                    {{ $item->title }}
                                                </a>
                                            </h4>
                                            <div class="hidden-sm hidden-md hidden-lg">
                                                <br>
                                                Дата размещения: {{ $item->created_at->format('d.m.Y') }}<br>
                                                Количество файлов: {{ $item->files->count() }}
                                            </div>
                                        </td>
                                        <td class="hidden-xs hidden-sm text-center">
                                            <small>
                                                <i>{{ $item->created_at->format('d.m.Y') }}</i>
                                            </small>
                                        </td>
                                        <td class="hidden-xs hidden-sm text-center">
                                            <small>
                                                <i>{{ $item->files->count() }}</i>
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        function removeDocument(id) {
            axios.delete('/admin/sib/documents/'+id)
                .then((response) => {
                    console.debug('remove:response', response);
                    if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                        console.error(response.data.errors);
                    } else {
                        location.reload();
                    }
                })
                .catch((error) => {
                    console.error(error);
                })
        }
    </script>
@endsection
