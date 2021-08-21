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
                <h2>Слияние профилей пользователя </h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                   <h1> Слияние данных </h1>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <form role="form">
                                <div class="form-group">
                                    <label for="userid">ID пользователя</label>
                                    <input name="id" type="text" class="form-control" id="userid" placeholder="Введите ID" required value="<?php echo $user_id;?>" disabled>
                                    <small class="form-text text-muted">Начиная с данного пользователя - всем вышестоящим будут начислены бонусы</small>
                                </div>
                                <div class="form-group">
                                    <input name="old" type="text" class="form-control" id="old" placeholder="ID старого профиля" required>
                                    <small class="form-text text-muted">Введите ID профиля с которым нужно слить данные</small>
                                </div>
                                <div class="form-group">
                                    <input name="date" type="text" class="form-control" id="date" placeholder="Введите дату" required>
                                    <small class="form-text text-muted">Введите дату регистрации в формате - 2021-06-04</small>
                                </div>
                                <a href="javascript:void(0);" type="submit" class="btn btn-primary" onclick="profilesMerge()">Слить данные</a>
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
        function profilesMerge() {
            let user_id = $('#userid').val();
            let old = $('#old').val();
            let date = $('#date').val();

            if (user_id.length > 0 && old.length > 0 && date.length === 10) {
                axios.get('/admin/handupdate/'+user_id+'/'+old+'/'+date);
                alert('Данные успешно обновлены');

                setTimeout(function(){
                    window.location.href = '/admin/users/'+user_id;
                }, 1000);
            }
            else{
                if (old.length > 5) {
                    alert('Поле ID старого профиля - обязательно к заполнению');
                }
                if (date.length < 10) {
                    alert('Поле дата начисления - обязательно к заполнению');
                }
            }
            return false;
        }
    </script>
@endsection




