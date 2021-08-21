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
                <h2>Изменение данных пользователя </h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                   <h1> Изменение даты регистрации пользователя </h1>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <form role="form">
                                <div class="form-group">
                                    <label for="userid">ID пользователя</label>
                                    <input name="id" type="text" class="form-control" id="userid" placeholder="Введите ID" required value="<?php echo $user_id;?>" disabled>
                                    <small class="form-text text-muted">Начиная с данного пользователя - всем вышестоящим будут начислены бонусы</small>
                                </div>
                                <div class="form-group">
                                    <label for="packages">Выберите текущий пакет пользователя:</label>
                                    <select name="tekpackage" class="form-control" id="tekpackage" required>
                                        <option>Выберите пакет</option>
                                        <option value="1">Basic</option>
                                        <option value="2">Standart</option>
                                        <option value="3">Premium</option>
                                        <option value="4">VIP</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="packages">Выберите пакет по которому должны начислиться баллы:</label>
                                    <select name="package" class="form-control" id="package" required>
                                        <option>Выберите пакет</option>
                                        <option value="1">Basic</option>
                                        <option value="2">Standart</option>
                                        <option value="3">Premium</option>
                                        <option value="4">VIP</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date">Введите дату начисления:</label>
                                    <input name="date" type="text" class="form-control" id="date" placeholder="Введите дату" required>
                                    <small class="form-text text-muted">Введите дату начисления в формате - 2021-06-04</small>
                                </div>
{{--                                <a href="javascript:void(0);" type="submit" class="btn btn-primary" onclick="pointsUpdate()">Начислить</a>--}}
                                <a href="javascript:void(0);" type="submit" class="btn btn-primary" onclick="pointsUpdate()">Начислить</a>
{{--                                <button type="submit" class="btn btn-primary">Начислить</button>--}}
                            </form>
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
        function pointsUpdate() {
            let user_id = $('#userid').val();
            let tekpackage = $('#tekpackage').val();
            let package = $('#package').val();
            let date = $('#date').val();

            if (user_id.length > 0 && tekpackage > 0 && package > 0 && date.length === 10) {

                // axios.get('/admin/updatebinaryview/'+user_id+'/'+tekpackage+'/'+package+'/'+date);
                //axios.get('/admin/updatebinaryview/'+user_id+'/'+tekpackage+'/'+package+'/'+date);
                window.location.href = '/admin/updatebinaryview/'+user_id+'/'+tekpackage+'/'+package+'/'+date;
                // alert(user_id);
                // alert(tekpackage);
                // alert(package);
                // alert(date);
                //alert('Данные успешно обновлены. Подождите. Сейчас страница перезагрузится');

                // setTimeout(function(){
                //     window.location.href = '/admin/users/'+user_id;
                // }, 3000);
            }
            else{
                if (package.length > 5) {
                    alert('Поле пакет для начисления - обязательно к заполнению');
                }
                if (date.length < 10) {
                    alert('Поле дата начисления - обязательно к заполнению');
                }
            }

            return false;
        }
    </script>
@endsection




