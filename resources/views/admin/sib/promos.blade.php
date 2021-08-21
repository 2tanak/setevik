@extends('layouts.app')

@section('title', 'Промо и акции')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
             data-widget-editbutton="false"
             data-widget-fullscreenbutton="true">

            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-gift"></i>
                </span>
                <h2>Промо и акции</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <a href="/admin/sib/promos/0" class="btn btn-default">Добавить</a>
                    <div class="clb"></div>
                    <br>
                    <div class="report-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Название</th>
                                    <th class="text-center">Активность</th>
                                    <th class="text-center">Дата изменения</th>
                                    <th class="text-center">Настройки</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $item->id }}
                                        </td>
                                        <td>
                                            <a href="/admin/sib/promos/{{ $item->id }}">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($item->is_active))
                                                Да
                                            @else
                                                Нет
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $item->updated_at->format('d.m.Y H:i:s') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="Javascript:void(0);" data-deleted-id="{{ $item->id }}"
                                               onclick="deletedItem(this)">Удалить</a>
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
        //
        function deletedItem(el) {
            let btn = $(el),
                id = btn.attr('data-deleted-id');

            if (confirm('Удалить?')) {
                axios.post('/admin/sib/promos/delete/' + id)
                    .then((response) => {
                        location.reload();
                    })
                    .catch((error) => {
                        console.error(error);
                    })
            }
        }
    </script>
@endsection

