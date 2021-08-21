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
                    <h1> Редактирование количества баллов пользователя </h1>
                    <div class="row">
                        <?php if(!empty($tek_bonuses->node_id)){?>
                        <div class="col-md-4 col-md-offset-4">
                            <form role="form">
                                <div class="form-group">
                                    <label for="userid">ID пользователя в бинаре</label>
                                    <input name="id" type="text" class="form-control" id="userid" placeholder="Введите ID" required value="<?php echo $tek_bonuses->node_id;?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="user_name">Имя пользователя</label>
                                    <input name="user_name" type="text" class="form-control" id="user_name" placeholder="Введите Имя" required value="<?php echo $user_name;?>" disabled>
                                </div>
                                {{--  Пакеты пользователя --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="packs_left">Пакеты слева</label>
                                        <input name="packs_left" type="text" class="form-control" id="packs_left" placeholder="Введите количество" required value="<?php echo $tek_bonuses->packs_left;?>">
                                        <small class="form-text text-muted">Количество пакетов пользователя слева</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="packs_right">Пакеты справа</label>
                                        <input name="packs_right" type="text" class="form-control" id="packs_right" placeholder="Введите количество" required value="<?php echo $tek_bonuses->packs_right;?>">
                                        <small class="form-text text-muted">Количество пакетов пользователя справа</small>
                                    </div>
                                </div>
                                {{--   Основные бонусы --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pts_left">Бонусы слева</label>
                                        <input name="pts_left" type="text" class="form-control" id="pts_left" placeholder="Введите количество" required value="<?php echo $tek_bonuses->pts_left;?>">
                                        <small class="form-text text-muted">Количество бонусов пользователя слева</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pts_right">Бонусы справа</label>
                                        <input name="pts_right" type="text" class="form-control" id="pts_right" placeholder="Введите количество" required value="<?php echo $tek_bonuses->pts_right;?>">
                                        <small class="form-text text-muted">Количество бонусов пользователя справа</small>
                                    </div>
                                </div>
                                {{--   Бонусы в красной зоне  --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pts_missed_left">Красные бонусы слева</label>
                                        <input name="pts_missed_left" type="text" class="form-control" id="pts_missed_left" placeholder="Введите количество" required value="<?php echo $tek_bonuses->pts_missed_left;?>">
                                        <small class="form-text text-muted">Количество бонусов пользователя слева</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="packs_missed_right">Красные бонусы справа</label>
                                        <input name="pts_missed_right" type="text" class="form-control" id="pts_missed_right" placeholder="Введите количество" required value="<?php echo $tek_bonuses->pts_missed_right;?>">
                                        <small class="form-text text-muted">Количество бонусов пользователя справа</small>
                                    </div>
                                </div>
                                <a href="javascript:void(0);" id="bonuses_update" type="submit" class="btn btn-primary" onclick="handUserBonusesUpdate()">Сохранить</a>
                            </form>
                        </div>
                        <?php } else{?>
                        <div class="col-md-6">
                            <h1>Доступно только для пользователей зарегистрированных в бинаре</h1>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script>
        function handUserBonusesUpdate() {
            let bonuses_update = $('#bonuses_update');
            let binary_id = $('#userid').val();
            let packs_left = $('#packs_left').val();
            let packs_right = $('#packs_right').val();
            let pts_left = $('#pts_left').val();
            let pts_right = $('#pts_right').val();
            let pts_missed_left = $('#pts_missed_left').val();
            let pts_missed_right = $('#pts_missed_right').val();
            if (binary_id.length > 0) {
                bonuses_update.remove();
                axios({
                    method: 'post',
                    url: '/admin/handupdatebonusessuccess',
                    data: {
                        binary_id: binary_id,
                        packs_left: packs_left,
                        packs_right: packs_right,
                        pts_left: pts_left,
                        pts_right: pts_right,
                        pts_missed_left: pts_missed_left,
                        pts_missed_right: pts_missed_right,
                    }
                })
                    .then((data) => {
                        alert('Данные сохранены');
                        location.reload();
                    })
                    .catch((error) => {
                        alert(error);
                        console.error(error);
                    });
            }
        }
    </script>
@endsection
