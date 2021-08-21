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
                                        <div style="padding-left: 45%;">
                                            <img id="img_loader" src="../img/loader.gif" alt="loader" style="width: 70px; margin-bottom: 10px;"/>
                                            <img id="img_success" src="../img/success.png" alt="loader" style="width: 70px; margin-bottom: 10px;" />
                                            <img id="img_error" src="../img/error.png" alt="loader" style="width: 70px; margin-bottom: 10px;" />
                                        </div>
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
                            <div class="report-wrapper">
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
                                                    @if ($item->is_confirmed == 0)
                                                        <div class="links">
                                                            <a href="Javascript:void(0);" data-package-id="{{ $item->package_id }}" data-request-id="{{ $item->id }}" onclick="showModalConfirm(this)">Подтвердить</a>
                                                            /
                                                            <a href="Javascript:void(0);" data-package-id="{{ $item->package_id }}" data-request-id="{{ $item->id }}" onclick="cancelRequest(this)">Отклонить</a>
                                                        </div>
                                                    @else
                                                        @if ($item->is_confirmed == 1)
                                                            Подтвержден
                                                        @endif
                                                        @if ($item->is_confirmed == 2)
                                                            Отклонен
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

{{--                    <div class="text-center">--}}
{{--                        {{ $data->links() }}--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        //
        $('#reload').hide();
        $('#img_loader').hide();
        $('#img_error').hide();
        $('#img_success').hide();

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

        function showModalConfirm(element) {
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
            let links = $('.links');
            links.remove();
            let m = $('#modal-confirm'),
                btn = $(el),
                id = btn.attr('data-request-id'),
                packageId = m.find('option:selected').val();
            btn.remove();

            //заменяем форму с выбранным пакетом - на лоадер

            //новый элемент на период загрузки
            let div = document.createElement("div")
            div.append("Не перезагружайте и не обновляйте страницу, запрос обрабатывается...");
            div.setAttribute("id", "loader_text");
            div.style.cssText = 'text-align: center;';



            //удаляем дочерние элементы
            let form_block = $('.form-group')[0];
            while (form_block.firstChild) {
                form_block.removeChild(form_block.firstChild);
            }

            //добавляем новый дочерний элемент
            $('#img_loader').show();
            $('.form-horizontal')[0].appendChild(div);

            //alert('Запрос отправлен');
            axios.put('/admin/be-partner-requests/'+id, { is_confirmed: true, package_id: packageId })
                .then((response) => {
                    //новый элемент в случае успешной загрузки
                    let div_success = document.createElement("div")
                    div_success.append("Запрос успешно обработан!");
                    div_success.setAttribute("id", "div_success");
                    div_success.style.cssText = 'text-align: center; color: green;';

                    //новый элемент в случае ошибки загрузки
                    let div_error = document.createElement("div")
                    div_error.append("Ошибка выполнения запроса. Код ошибки - " + response.status);
                    div_error.setAttribute("id", "div_success");
                    div_error.style.cssText = 'text-align: center; color: red;';

                    //удаляем дочерние элементы
                    let form_block = $('.form-horizontal')[0];
                    while (form_block.firstChild) {
                        form_block.removeChild(form_block.firstChild);
                    }

                    if (response.data.is_confirmed == 1 || response.data.is_confirmed == true) {
                        //alert('Подтвержден');
                        //location.reload();
                        //добавляем новый дочерний элемент
                        $('#img_loader').hide();
                        $('#img_error').hide();
                        $('#img_success').show();
                        $('.form-horizontal')[0].appendChild(div_success);
                        location.reload();
                    }
                    else{
                        //статус 200 - запрос прошел успешно
                        if(response.status == 200){
                            var p = btn.parent();            //
                            //p.text('Подтвержден');
                            //alert('Подтвержден');
                            $('#img_loader').hide();
                            $('#img_error').hide();
                            $('#img_success').show();
                            $('.form-horizontal')[0].appendChild(div_success);
                            location.reload();
                        }
                        //другие варианты
                        else{
                            var p = btn.parent();            //
                            $('.form-horizontal')[0].appendChild(div_error);
                            $('#img_loader').hide();
                            $('#img_success').hide();
                            $('#img_error').show();
                            alert('Выполнение запроса прервалось из-за ошибки. Код ошибки - ' + response.status);
                            location.reload();
                        }
                    }
                })
                .catch((error) => {
                    console.error(error);
                    alert('Выполнение запроса прервалось из-за ошибки.');
                    alert(error);
                })
        }

        function cancelRequest(el) {
            let links = $('.links');
            let btn = $(el),
                id = btn.attr('data-request-id'),
                packageId = btn.attr('data-package-id');
            links.remove();
            axios.put('/admin/be-partner-requests/canceled/'+id, { is_confirmed: 2, package_id: packageId })
                .then((response) => {
                    alert('Запрос обработан');
                    location.reload();
                })
                .catch((error) => { console.error(error); })
        }
    </script>
@endsection
