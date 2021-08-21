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
                <h2>Действия с пользователем </h2>
            </header>

            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                   <h1> Для продолжения - выберите раздел: </h1>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-md-4 text-left">
                                <h3>Порядок действий для регистрации:</h3>
                                <p>1) Перейдите в профиль <b>куратора</b> и сделайте бекап данных </p>
                                <p>2) Перейдите в профиль <b>пользователя</b> и удалите данные старого профиля, предварительно сохранив ID, так как он потребуется для слияния</p>
                                <p>3) Возьмите ссылку в профиле <b>куратора</b> и создайте новый профиль <b>пользователя</b></p>
                                <p>4) Активируйте новый профиль <b>пользователя</b> в админке</p>
                                <p>5) Сделайте слияние данных старого и нового профиля <b>пользователя</b></p>
                                <p>6) Восстановите старый пароль <b>куратора</b></p>
                            </div>
                            <div class="col-md-6 text-center" style="padding-bottom: 30px;">
                                <div class="col-md-4 text-center">
                                    <h3>Бекап профиля</h3>
                                    <p>
                                       Копия данных пользователя, для восстановления после проведения манипуляций (логин, пароль, почта, адрес, телефон).
                                    </p>
                                    <a href="javascript:void(0);"  onclick="createUserBackup()" class="btn txt-color-white bg-color-green btn-sm btn-block">Начать</a>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3>Удаление данных</h3>
                                    <p>
                                        Данные выбранного профиля удаляются с базы, но остаются доступны локально, для дальнейшего слияния с новым профилем.
                                    </p>
                                    <a href="javascript:void(0);"  onclick="deleteUserProfile()" class="btn txt-color-white bg-color-green btn-sm btn-block">Начать</a>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3>Слияние профилей</h3>
                                    <p>
                                        Слияние данных 2 профилей для 1 пользователя - старого + нового. Для восстановления данных пользователя в бинаре.
                                    </p>
                                    <a href="javascript:void(0);"  onclick="mergeUserProfile()" class="btn txt-color-white bg-color-green btn-sm btn-block">Начать</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="padding-bottom: 30px;">
                            <div class="col-md-6 col-md-offset-4 text-center" style="padding-bottom: 30px;">
                                <div class="col-md-4 text-center">
                                    <a href="javascript:void(0);"  onclick="updateUserDate()" class="btn txt-color-white bg-color-green btn-sm btn-block">Дата регистрации</a>
                                    <br>
                                    <a href="javascript:void(0);"  onclick="updateUserBonuses()" class="btn txt-color-white bg-color-green btn-sm btn-block">Бонусы</a>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3>Восстановление пароля</h3>
                                    <p>
                                        Восстановление пароля пользователя с локального хранилища, после регистрации и слияния данных нового пользователя.
                                    </p>
                                    <a href="javascript:void(0);"  onclick="restoreUserPassword()" class="btn txt-color-white bg-color-green btn-sm btn-block">Начать</a>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3>Начисление баллов</h3>
                                    <p>
                                        Ручное начисление баллов всем вышестоящим пользователям в бинаре, над данным пользователем. Начисляются баллы + пакеты.
                                    </p>
                                    <a href="javascript:void(0);"  onclick="updateUserBinaryBonus()" class="btn txt-color-white bg-color-green btn-sm btn-block">Начать</a>
                                </div>
                            </div>
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
            let package = $('#package').val();
            let date = $('#date').val();

            if (user_id.length > 0 && package.length > 0 && date.length === 10) {
                axios.get('/admin/updatebinary/'+user_id+'/'+package+'/'+date);
                alert('Данные успешно обновлены');

                setTimeout(function(){
                    window.location.href = '/admin/users/'+user_id;
                }, 1000);
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




