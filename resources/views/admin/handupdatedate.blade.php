@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<?php
    //Дата создания
    if(!empty($user_oss->started_at)){
        $oss_started_at = $user_oss->started_at;
    }
    else{
        $oss_started_at = '';
    }
    //Дата истечения
    if(!empty($user_oss->expired_at)){
        $oss_expired_at = $user_oss->expired_at;
    }
    else{
        $oss_expired_at = '';
    }
    //ID записи о подписке OSS
    if(!empty($user_oss->id)){
        $user_oss_id = $user_oss->id;
    }
    else{
        $user_oss_id = '';
    }
?>
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
                        <?php if(isset($user_data->is_active) && !empty($user_data->is_active) && $user_data->is_active == 1){?>
                        <div class="col-md-8 col-md-offset-2">
                            <form role="form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userid">ID пользователя</label>
                                        <input name="userid" type="text" class="form-control" id="userid" placeholder="Введите ID" required value="<?php echo $user_id;?>" disabled>
                                        <small class="form-text text-muted"><?php echo $user_data->name.' '.$user_data->last_name;?></small>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($user_data->activated_at) && !empty($user_data->activated_at)){?>
                                            <label for="activated_at">Дата активации пользователя:</label>
                                            <input name="activated_at" type="text" class="form-control" id="activated_at" placeholder="Введите дату" value="<?php echo $user_data->activated_at;?>">
                                            <small class="form-text text-muted">Введите дату активации в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="activated_at" type="text" class="form-control hidden" id="activated_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате активации пользователя отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($user_data->created_at) && !empty($user_data->created_at)){?>
                                            <label for="created_at">Дата регистрации пользователя:</label>
                                            <input name="created_at" type="text" class="form-control" id="created_at" placeholder="Введите дату" value="<?php echo $user_data->created_at;?>">
                                            <small class="form-text text-muted">Введите дату регистрации в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="created_at" type="text" class="form-control hidden" id="created_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате регистрации пользователя отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($user_data->sib_registered_at) && !empty($user_data->sib_registered_at)){?>
                                            <label for="sib_registered_at">Дата регистрации SIB:</label>
                                            <input name="sib_registered_at" type="text" class="form-control" id="sib_registered_at" placeholder="Введите дату" value="<?php echo $user_data->sib_registered_at;?>">
                                            <small class="form-text text-muted">Введите дату регистрации SIB в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="sib_registered_at" type="text" class="form-control hidden" id="sib_registered_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате регистрации SIB отсутствует</span>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oss_activated_at">Дата активации OSS:</label>
                                        <?php if(isset($user_data->oss_activated_at) && !empty($user_data->oss_activated_at)){?>
                                            <input name="oss_activated_at" type="text" class="form-control" id="oss_activated_at" placeholder="Введите дату" value="<?php echo $user_data->oss_activated_at;?>">
                                            <small class="form-text text-muted">Введите дату активации OSS в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="oss_activated_at" type="text" class="form-control hidden" id="oss_activated_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате активации OSS отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($user_data->oss_registered_at) && !empty($user_data->oss_registered_at)){?>
                                            <label for="oss_registered_at">Дата регистрации OSS:</label>
                                            <input name="oss_registered_at" type="text" class="form-control" id="oss_registered_at" placeholder="Введите дату" value="<?php echo $user_data->oss_registered_at;?>">
                                            <small class="form-text text-muted">Введите дату регистрации OSS в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="oss_registered_at" type="text" class="form-control hidden" id="oss_registered_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате регистрации OSS отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($oss_started_at) && !empty($oss_started_at)){?>
                                            <label for="subscription_registered_at">Дата регистрации абонемента OSS:</label>
                                            <input name="subscription_registered_at" type="text" class="form-control" id="subscription_registered_at" placeholder="Введите дату" value="<?php echo $oss_started_at;?>">
                                            <small class="form-text text-muted">Введите дату регистрации регистрации абонемента OSS в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="subscription_registered_at" type="text" class="form-control hidden" id="subscription_registered_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате регистрации абонемента OSS отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($oss_expired_at) && !empty($oss_expired_at)){?>
                                            <label for="subscription_expired_at">Дата истечения абонемента OSS:</label>
                                            <input name="subscription_expired_at" type="text" class="form-control" id="subscription_expired_at" placeholder="Введите дату" value="<?php echo $oss_expired_at;?>">
                                            <small class="form-text text-muted">Введите дату истечения срока регистрации абонемента OSS в формате - 2020-12-31</small>
                                        <?php }else{?>
                                            <input name="subscription_expired_at" type="text" class="form-control hidden" id="subscription_expired_at" placeholder="Введите дату" value="0">
                                            <span>Информация о дате истечения срока регистрации абонемента OSS отсутствует</span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($user_oss_id) && !empty($user_oss_id)){?>
                                            <label for="oss_id">ID заявки OSS:</label>
                                            <input name="oss_id" type="text" class="form-control" id="oss_id" placeholder="Введите ID" value="<?php echo $user_oss_id;?>" disabled>
                                        <?php }else{?>
                                            <input name="oss_id" type="text" class="form-control hidden" id="oss_id" placeholder="Введите ID" value="0" disabled>
                                            <span>ID заявки OSS отсутствует</span>
                                        <?php }?>
                                    </div>
                                </div>
                                <a href="javascript:void(0);" id="date_update" type="submit" class="btn btn-primary" onclick="handUserDateUpdate()">Сохранить данные</a>
                            </form>
                        </div>
                        <?php } else {?>
                        <div class="col-md-6">
                            <h1>Доступно только для активированных пользователей</h1>
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
        function handUserDateUpdate() {
            let date_update = $('#date_update');
            let userid = $('#userid').val();
            let activated_at = $('#activated_at').val();
            let created_at = $('#created_at').val();
            let sib_registered_at = $('#sib_registered_at').val();
            let oss_activated_at = $('#oss_activated_at').val();
            let oss_registered_at = $('#oss_registered_at').val();
            let subscription_registered_at = $('#subscription_registered_at').val();
            let subscription_expired_at = $('#subscription_expired_at').val();
            let oss_id = $('#oss_id').val();


            if (activated_at == 0) {
                activated_at = 'NULL';
            }
            if (created_at == 0) {
                created_at = 'NULL';
            }
            if (sib_registered_at == 0) {
                sib_registered_at = 'NULL';
            }
            if (oss_activated_at == 0) {
                oss_activated_at = 'NULL';
            }
            if (oss_registered_at == 0) {
                oss_registered_at = 'NULL';
            }
            if (subscription_registered_at == 0) {
                subscription_registered_at = 'NULL';
            }
            if (subscription_expired_at == 0) {
                subscription_expired_at = 'NULL';
            }
            if (oss_id == 0) {
                oss_id = 'NULL';
            }

            if (userid.length > 0) {
                date_update.remove();
                axios({
                    method: 'post',
                    url: '/admin/handupdatedatesuccess',
                    data: {
                        userid: userid,
                        activated_at: activated_at,
                        created_at: created_at,
                        sib_registered_at: sib_registered_at,
                        oss_activated_at: oss_activated_at,
                        oss_registered_at: oss_registered_at,
                        subscription_registered_at: subscription_registered_at,
                        subscription_expired_at: subscription_expired_at,
                        oss_id: oss_id,
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
