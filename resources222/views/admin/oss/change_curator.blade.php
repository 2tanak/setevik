@extends('layouts.app')

@section('title', 'Смена куратора')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-refresh"></i>
                </span>
                <h2>Смена куратора</h2>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <!-- #MODAL CHANGE CURATOR -->
                    <div class="modal fade" id="modal-change-curator">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title text-left">Смена куратора</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-horizontal">
                                        <div class="wrapper-form-input">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="select-type">Куратор</label>
                                                <div class="col-md-10">
                                                    <select name="curator" class="form-control" id="select-curator">
                                                        <option value="0" disabled="disabled">Выберите куратора</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-default" data-request-id="0" onclick="changeCurator(this)">
                                                    Сохранить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <form action="/admin/oss/change-curator" method="get">
                            <div class="input-group">
                                <input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="Найти резидента...">
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Поиск
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>

                    <div class="users-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="avatar text-center">Pic</th>
                                        <th class="text-center">Резидент</th>
                                        <th class="text-center">Куратор</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $item->id }}
                                            </td>
                                            <td class="text-center">
                                                <img src="{{ $item->photo }}" alt="photo" width="20">
                                            </td>
                                            <td class="text-center">
                                                <a href="/admin/users/{{ $item->id }}">
                                                    {{ $item->name }} {{ $item->last_name }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <?php $curator = (new \App\Services\Oss\TreeService())->getCurator($item)?>
                                                @if ($curator)
                                                    <a href="/admin/users/{{ $curator->id }}">
                                                        {{ $curator->name }} {{ $curator->last_name }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($curator)
                                                    <a href="Javascript:void(0);" data-user-id="{{ $item->id }}" data-curator-id="{{ $curator->id }}" onclick="showModalChangeCurator(this)">Сменить</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {{ $data->links() }}
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
        function showModalChangeCurator(element) {
            let m = $('#modal-change-curator'),
                el = $(element),
                userId = el.attr('data-user-id'),
                curatorId = el.attr('data-curator-id'),
                btn = m.find('button'),
                select = m.find('select');

            select.html('');
            btn.attr('data-user-id', userId);
            btn.attr('data-curator-id', curatorId);

            axios.get('/admin/oss/change-curator/'+userId+'/curators')
                .then((response) => {
                    var options = '<option value="0" disabled="disabled" selected>Выберите куратора</option>';
                    response.data.forEach(function (item) {
                        options += '<option value="'+item.id+'">'+item.name+' ' +item.last_name+ '</option>';
                    });
                    select.html(options);
                })
                .catch((error) => { console.error(error) });

            m.modal('show');
        }

        //
        function changeCurator(el) {
            let m = $('#modal-change-curator'),
                btn = $(el),
                userId = btn.attr('data-user-id'),
                curatorId = m.find('option:selected').val();

            axios.put('/admin/oss/change-curator/'+userId+'/change', { curator_id: curatorId })
                .then((response) => {
                    location.reload();
                })
                .catch((error) => { console.error(error); })
        }
    </script>
@endsection