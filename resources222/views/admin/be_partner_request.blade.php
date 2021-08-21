@extends('layouts.app')

@section('title', 'Запросы SIB')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-check-square-o"></i>
                </span>
                <h2>Запросы SIB</h2>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                    <!-- #MODAL QUITTANCE -->
                    <div class="modal fade" id="modal-quittance">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title text-left">Квитанция</h4>
                                </div>
                                <div class="modal-body">
                                    <br>
                                    <div>
                                        <div class="text-center">
                                            <img src="" class="img img-responsive" alt="quittance">
                                        </div>
                                        <hr/>
                                        <span class="pull-right">
                                            <a href="Javascript:void(0);" target="_blank">Скачать</a>
                                        </span>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- #MODAL CONFIRM -->
                    <div class="modal fade" id="modal-confirm">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title text-left">Подтверждение</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-horizontal">
                                        <div class="wrapper-form-input">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="select-type">Пакет</label>
                                                <div class="col-md-10">
                                                    <select name="package" class="form-control" id="select-package">
                                                        @foreach ($packages as $package)
                                                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-default" data-request-id="0" onclick="confirmRequest(this)">
                                                    Подтвердить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center v-align-m">ID</th>
                                        <th class="text-center v-align-m">Дата</th>
                                        <th class="text-center v-align-m">Резидент</th>
                                        <th class="text-center v-align-m">Куратор (SIB)</th>
                                        <th class="text-center v-align-m">Пакет</th>
                                        <th class="text-center v-align-m">Квитанция</th>
                                        <th class="text-center v-align-m">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        @if (strlen($item->link) > 0)
                                            <tr>
                                                <td class="text-center v-align-m">
                                                    {{ $item->id }}
                                                </td>
                                                <td class="text-center v-align-m">
                                                    {{ $item->created_at }}
                                                </td>
                                                <td class="text-center v-align-m">
                                                    <a href="/admin/users/{{ $item->user->id }}">{{ $item->user->name }} {{ $item->user->last_name }}</a>
                                                </td>
                                                <td class="text-center v-align-m">
                                                    <a href="/admin/users/{{ $item->curator->id }}">{{ $item->curator->name }} {{ $item->curator->last_name }}</a>
                                                </td>
                                                <td class="text-center v-align-m">
                                                    {{ $item->package->name }}
                                                </td>
                                                <td class="text-center v-align-m">
                                                    @if($item->quittance)
                                                        <a href="Javascript:void(0);" onclick="showQuittance({{ $item->quittance->id }})">Посмотреть</a>
                                                    @endif
                                                </td>
                                                <td class="text-center v-align-m">
                                                    @if ($item->is_confirmed)
                                                        Подтвержден
                                                    @else
                                                        <a href="Javascript:void(0);" data-package-id="{{ $item->package_id }}" data-request-id="{{ $item->id }}" onclick="showModalConfrim(this)">Подтвердить</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
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
        function showQuittance(quittanceId) {
            let m = $('#modal-quittance'),
                img = m.find('img'),
                link = m.find('a');

            img.attr('src', '');
            img.attr('data-quittance-id', quittanceId);
            link.attr('href', '/quittance/'+quittanceId+'/download');

            axios.get('/quittance/'+quittanceId+'/image')
                .then((response) => {
                    img.attr('src', response.data);
                });
            m.modal('show');
        }

        function showModalConfrim(element) {
            let m = $('#modal-confirm'),
                el = $(element),
                requestId = el.attr('data-request-id'),
                packageId = el.attr('data-package-id'),
                btn = m.find('button');

            btn.attr('data-request-id', requestId);
            m.find('option').removeAttr('selected').filter('[value='+packageId+']').attr('selected', true);
            m.modal('show');
        }

        //
        function confirmRequest(el) {
            let m = $('#modal-confirm'),
                btn = $(el),
                id = btn.attr('data-request-id'),
                packageId = m.find('option:selected').val();

            axios.put('/admin/be-partner-requests/'+id, { is_confirmed: true, package_id: packageId })
                .then((response) => {
                    if (response.data.is_confirmed == true) {
                        // var p = btn.parent();
                        // btn.remove();
                        // p.text('Подтвержден');
                        location.reload();
                    }
                })
                .catch((error) => { console.error(error); })
        }
    </script>
@endsection
