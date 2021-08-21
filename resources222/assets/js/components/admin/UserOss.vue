<template>
    <div class="user-detail-wrapper">

        <!-- #MODAL QUITTANCE -->
        <div class="modal fade" id="modal-quittance">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">Квитанция</h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
                        </div>
                        <br>
                        <div>
                            <div class="text-center">
                                <img :src="quittanceSource" class="img img-responsive" alt="quittance">
                            </div>
                            <hr/>
                            <span class="pull-right">
                                <a :href="'/quittance/'+selectedQuittance+'/download'" target="_blank">
                                    Скачать
                                </a>
                            </span>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #MODAL REQUISITION CONFIRMATION -->
        <div class="modal fade" id="modal-requisition-confirmation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">Подтверждение заявки</h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
                        </div>
                        <br>
                        <div class="form-horizontal">
                            <fieldset>
                                <div class="wrapper-form-input">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Имя</label>
                                        <div class="col-md-10">
                                            <input v-model="name" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Фамилия</label>
                                        <div class="col-md-10">
                                            <input v-model="last_name" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Продукт</label>
                                        <div class="col-md-10">
                                            <input v-model="product" class="form-control" disabled="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <button @click="confirm" class="btn btn-default" :disabled="loading">
                                        <span v-show="loading">Подтверждение..</span>
                                        <span v-show="!loading">Подтвердить</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #MODAL USER DEACTIVATION-->
        <div class="modal fade" id="modal-user-deactivation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">Деактивация пользователя</h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
                        </div>
                        <br>
                        <div class="alert adjusted alert-warning fade in">
                            <i class="fa fa-fw fa-lg fa-exclamation"></i>
                            <strong>Вы действительно хотите деактивировать пользователя?</strong>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <button @click="deactivate" class="btn btn-default" :disabled="loading">
                                        Деактивировать
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #MODAL UPDATE USER DATA -->
        <div class="modal fade" id="modal-update-user-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">Изменить данные</h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
                        </div>
                        <br>
                        <div class="form-horizontal">
                            <fieldset>
                                <div class="wrapper-form-input">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Имя</label>
                                        <div class="col-md-10">
                                            <input v-model="user_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Фамилия</label>
                                        <div class="col-md-10">
                                            <input v-model="user_last_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">E-Mail</label>
                                        <div class="col-md-10">
                                            <input v-model="user_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Мобильный</label>
                                        <div class="col-md-10">
                                            <input v-model="user_phone" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Дата рождения</label>
                                        <div class="col-md-10">
                                            <input v-model="user_birthday" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Страна</label>
                                        <div class="col-md-10">
                                            <select v-model="user_country" class="form-control">
                                                <option disabled value="0">Страна не выбрана</option>
                                                <option v-for="country in countries" v-bind:value="country.id">{{ country.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Населенный пункт</label>
                                        <div class="col-md-10">
                                            <input v-model="user_city" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Новый пароль</label>
                                        <div class="col-md-10">
                                            <input type="password" v-model="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Подтверждение нового пароля</label>
                                        <div class="col-md-10">
                                            <input type="password" v-model="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="file" name="photo" id="input-user-photo">
                                                <small id="fileHelp" class="form-text text-muted">Размер файла не должен превышать 20 MB.</small>
                                                <br>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <button @click="updateUserData" class="btn btn-default" :disabled="loading">
                                        <span v-show="loading">Сохранение...</span>
                                        <span v-show="!loading">Сохранить</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #ROWS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="well-sm">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 padding-5">
                            <div class="well well-light well-sm no-margin no-padding">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="carousel fade profile-carousel">
                                            <div class="air air-bottom-right padding-10">
                                                <a href="Javascript:void(0);" @click="showModalUpdateUserData" class="btn txt-color-white bg-color-orange btn-sm">
                                                    Изменить данные
                                                </a>
                                                <a v-show="user.is_active && user.oss_activated_at" href="Javascript:void(0);" @click="showModalUserDeactivation" class="btn txt-color-white bg-color-red btn-sm">
                                                    Заблокировать
                                                </a>
                                            </div>
                                            <div class="air air-top-left padding-10">
                                                <h4 class="txt-color-white font-lg"><b>{{ user.cabinet.name }}</b></h4>
                                            </div>
                                            <div class="carousel-inner">
                                                <div class="item active">
                                                    <img src="/img/demo/s1.jpg" alt="background">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3 profile-pic">
                                                <img :src="user.photo" alt="demo user">
                                                <div class="padding-10">
                                                    <!--<h4 class="font-md"><strong>$1 000 000</strong>-->
                                                        <!--<br>-->
                                                        <!--<small>Заработано</small>-->
                                                    <!--</h4>-->
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <h1>{{ user.name }} {{ user.last_name }}</h1>

                                                <ul class="list-unstyled">
                                                    <li>
                                                        <p class="text-muted">
                                                            <i class="fa fa-birthday-cake"></i>&nbsp;&nbsp;
                                                            <span class="txt-color-darken">
                                                            <a href="Javascript:void(0);" rel="tooltip" title="" data-placement="top" data-original-title="Create an Appointment">
                                                                {{ user.birthday }}
                                                            </a>
                                                        </span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="text-muted">
                                                            <i class="fa fa-phone"></i>&nbsp;&nbsp;
                                                            <span class="txt-color-darken">{{ user.phone }}</span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="text-muted">
                                                            <i class="fa fa-envelope"></i>&nbsp;&nbsp;
                                                            <a href="mailto:simmons@smartadmin">{{ user.email }}</a>
                                                        </p>
                                                    </li>
                                                </ul>
                                                <br>
                                            </div>
                                            <div class="col-sm-3">
                                                <!--<h1>-->
                                                    <!--<small>Куратор OSS</small>-->
                                                <!--</h1>-->
                                                <!--<ul class="list-inline friends-list">-->
                                                    <!--<li><img src="/img/avatars/no-photo.png" alt="friend-1"></li>-->
                                                <!--</ul>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="padding-10">
                                            <ul class="nav nav-tabs tabs-pull-right">
                                                <li><a href="#a3" data-toggle="tab">Команда OSS</a></li>
                                                <li><a href="#requisition" data-toggle="tab">Заявки</a></li>
                                                <li class="active"><a href="#a1" data-toggle="tab">Кошелёк</a></li>
                                            </ul>
                                            <div class="tab-content padding-top-10">

                                                <!-- #TAB WALLET -->
                                                <div class="tab-pane fade in active" id="a1">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="txt-color-red">Раздел заполняется</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- #TAB REQUISITIONS -->
                                                <div class="tab-pane fade" id="requisition">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped table-hover table-bordered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th class="text-center">Дата создания</th>
                                                                            <th class="text-center">Дата подтверждения</th>
                                                                            <th class="text-center">Продукт</th>
                                                                            <th class="text-center">Вид заявки</th>
                                                                            <th class="text-center">Куратор, ID</th>
                                                                            <th class="text-center">Инициатор, ID</th>
                                                                            <th class="text-center">Статус</th>
                                                                        </tr>
                                                                        <tr v-for="item in user.requisitions" :key="item.id">
                                                                            <td>
                                                                                {{ item.created_at }}
                                                                            </td>
                                                                            <td>
                                                                                {{ item.confirmed_at }}
                                                                            </td>
                                                                            <td>
                                                                                {{ item.product.name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ item.requisition_type.name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ item.curator.name }} {{ item.curator.last_name }} <br>
                                                                                <a v-if="item.curator_quittance_id" href="Javascript:void(0);" @click="showModalQuittance(item.curator_quittance.id)">
                                                                                    (квитанция)
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                {{ item.user.name }} {{ item.user.last_name }} <br>
                                                                                <a v-if="item.user_quittance_id" href="Javascript:void(0);" @click="showModalQuittance(item.user_quittance.id)">
                                                                                    (квитанция)
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <template v-if="item.is_cancelled">
                                                                                    Отменен
                                                                                </template>
                                                                                <template v-else-if="item.is_confirmed">
                                                                                    Подтвержден
                                                                                </template>
                                                                                <template v-else="item.quittance_id">
                                                                                    <a href="Javascript:void(0);" @click="showModalRequisitionConfirmation(item.id)">
                                                                                        Подтвердить
                                                                                    </a>
                                                                                </template>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- #TAB .. -->
                                                <div class="tab-pane fade" id="a3">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="txt-color-red">Раздел заполняется</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 padding-5">
                            <form method="post" class="well padding-bottom-10" onsubmit="return false;">
                                <textarea rows="2" class="form-control" placeholder="What are you thinking?"></textarea>
                                <div class="margin-top-10">
                                    <button type="submit" class="btn btn-sm btn-primary pull-right">
                                        Post
                                    </button>
                                    <a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="Add Location"><i class="fa fa-location-arrow"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="Add Voice"><i class="fa fa-microphone"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="Add Photo"><i class="fa fa-camera"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-link profile-link-btn" rel="tooltip" data-placement="bottom" title="Add File"><i class="fa fa-file"></i></a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserOss",
        props: {
            user: Object,
            countries: Array,
            statuses: Array,
        },
        data () {
            return {
                errors: '',
                loading: false,

                selectedQuittance: 0,
                quittanceSource: '',

                selectedRequisition: 0,
                name: '',
                last_name: '',
                product: '',

                user_id: null,
                user_name: '',
                user_last_name: '',
                user_email: '',
                user_phone: '',
                user_birthday: '',
                user_country: '',
                user_city: '',
                password: '',
                password_confirmation: '',
                user_photo: '',
            }
        },
        mounted () {
            let th = this;

            th.user_id = th.user.id;
            th.user_name = th.user.name;
            th.user_last_name = th.user.last_name;
            th.user_email = th.user.email;
            th.user_phone = th.user.phone;
            th.user_birthday = th.user.birthday;
            th.user_country = th.user.country_id;
            th.user_city = th.user.city;
            th.user_photo = th.user.photo;
        },
        methods: {
            refresh: function () {
                this.errors = '';
                this.loading = false;
            },
            showModalUpdateUserData: function () {
                let th = this;
                th.refresh();
                $('#modal-update-user-data').modal('show');
            },
            updateUserData: function () {
                let th = this,
                    formData = new FormData(),
                    photo = document.querySelector('#input-user-photo');

                th.loading = true;

                formData.append('name', th.user_name);
                formData.append('last_name', th.user_last_name);
                formData.append('phone', th.user_phone);
                formData.append('country_id', th.user_country);
                formData.append('city', th.user_city);

                if (th.user_birthday) {
                    formData.append('birthday', th.user_birthday);
                }

                if (th.user_email != th.user.email) {
                    formData.append('email', th.user_email);
                }

                if (th.password.length) {
                    formData.append('password', th.password);
                    formData.append('password_confirmation', th.password_confirmation);
                }

                if (photo.files.length > 0) {
                    formData.append('photo', photo.files[0]);
                }

                axios.post('/admin/users/'+th.user_id, formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then((response) => {
                        location.href = '/admin/users/'+th.user_id;
                    })
                    .catch((error) => {
                        th.errors = error;
                        th.loading = false;
                    });
            },
            showModalQuittance: function (quittanceId) {
                let th = this;
                th.refresh();
                th.selectedQuittance = quittanceId;
                axios.get('/quittance/'+quittanceId+'/image')
                    .then((response) => {
                        th.quittanceSource = response.data;
                    });

                $('#modal-quittance').modal('show');
            },
            showModalRequisitionConfirmation: function (requisistionId) {
                let th = this;
                th.refresh();
                th.selectedRequisition = requisistionId;

                th.user.requisitions.forEach(function (item) {
                    if (item.id == th.selectedRequisition) {
                        th.name = item.user.name;
                        th.last_name = item.user.last_name;
                        th.product = item.product.name;
                    }
                });

                $('#modal-requisition-confirmation').modal('show');
            },
            confirm: function () {
                let th = this;
                th.loading = true;
                axios.post('/admin/requisition/'+th.selectedRequisition, { is_confirmed: true })
                    .then((response) => {
                        th.loading = false;
                        location.reload();
                    }).catch((error) => { th.errors = error; })
            },
            showModalUserDeactivation: function () {
                this.refresh();
                $('#modal-user-deactivation').modal('show');
            },
            activate: function () {
                let th = this;
                th.loading = true;
                axios.put('/admin/users/'+th.user.id, { package_id: th.packageSelected })
                    .then((response) => {
                        th.loading = false;
                        location.reload();
                    }).catch((error) => { th.errors = error; })
            },
            deactivate: function () {
                let th = this;
                th.loading = true;
                axios.put('/admin/users/'+th.user.id, { is_active: false })
                    .then((response) => {
                        th.loading = false;
                        location.reload();
                    }).catch((error) => { th.errors = error; })
            },
        },
    }
</script>

<style scoped>
    #modal-quittance img {
        display: inline;
    }

    #requisition table tr td {
        text-align: center;
    }

    .carousel-inner img {
        width: 100%;
    }
</style>