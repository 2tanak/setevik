@extends('layouts.app')

@section('title', 'Заявки OSS')

@section('content')
    <div class="row">
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
            <header>
                <span class="widget-icon">
                    <i class="fa fa-fw fa-check-square-o"></i>
                </span>
                <h2>Заявки OSS</h2>
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

                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center v-align-m">ID</th>
                                        <th class="text-center v-align-m">Дата создания</th>
                                        <th class="text-center v-align-m">Дата подтверждения<br> куратором</th>
                                        <th class="text-center v-align-m">Дата подтверждения<br> администратором</th>
                                        <th class="text-center v-align-m">Продукт</th>
                                        <th class="text-center v-align-m">Вид заявки</th>
                                        <th class="text-center v-align-m">Инициатор</th>
                                        <th class="text-center v-align-m">Куратор</th>
                                        <th class="text-center v-align-m">Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td class="text-center v-align-m">
                                                {{ $item->id }}
                                            </td>
                                            <td class="text-center v-align-m">
                                                {{ $item->created_at->format('d.m.Y H:i:s') }}
                                            </td>
                                            <td class="text-center v-align-m">
                                                @if ($item->curator_confirmed_at)
                                                    {{ $item->curator_confirmed_at->format('d.m.Y H:i:s') }}
                                                @endif
                                            </td>
                                            <td class="text-center v-align-m">
                                                @if ($item->confirmed_at)
                                                    {{ $item->confirmed_at->format('d.m.Y H:i:s') }}
                                                @endif
                                            </td>
                                            <td class="text-center v-align-m">
                                                {{ $item->product->name }}
                                            </td>
                                            <td class="text-center v-align-m">
                                                {{ $item->requisitionType->name }}
                                            </td>
                                            <td class="text-center v-align-m">
                                                <a href="/admin/users/{{ $item->user->id }}">{{ $item->user->name }} {{ $item->user->last_name }}</a>
                                                @if ($item->user_quittance_id)
                                                    <br>
                                                    (<a href="Javascript:void(0);" onclick="showQuittance({{ $item->user_quittance_id }})">квитанция</a>)
                                                @endif
                                            </td>
                                            <td class="text-center v-align-m">
                                                <a href="/admin/users/{{ $item->curator->id }}">{{ $item->curator->name }} {{ $item->curator->last_name }}</a>
                                                @if ($item->curator_quittance_id)
                                                    <br>
                                                    (<a href="Javascript:void(0);" onclick="showQuittance({{ $item->curator_quittance_id }})">квитанция</a>)
                                                @endif
                                            </td>
                                            <td class="text-center v-align-m">
                                                @if ($item->is_confirmed)
                                                    Подтвержден
                                                @else
                                                    <a href="Javascript:void(0);" data-request-id="{{ $item->id }}" onclick="confirmRequisition(this)">Подтвердить</a>
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

        //
        function confirmRequisition(el) {
            let btn = $(el),
                id = btn.attr('data-request-id');

            if (confirm('Подтвердить?')) {
                axios.post('/admin/requisition/'+id, { is_confirmed: true })
                    .then((response) => {
                        location.reload();
                    })
                    .catch((error) => { console.error(error); })
            }
        }
    </script>
@endsection
