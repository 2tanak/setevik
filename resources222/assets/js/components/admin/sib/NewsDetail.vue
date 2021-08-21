<template>
    <div class="events-detail-wrapper">

        <!-- #MODAL FILE ADD -->
        <div class="modal fade" :id="modalId">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">
                            <!--Заголовок-->
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
                            <br>
                        </div>
                        <div class="form-horizontal">
                            <fieldset>
                                <div class="wrapper-form-input">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Файл</label>
                                        <div class="col-md-10">
                                            <input type="file" name="files[]" id="new-file" class="btn btn-default" multiple>
                                            <!--<p class="help-block">-->
                                                <!--<i>Файл не должен превышать 2GB</i>-->
                                            <!--</p>-->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <button @click="uploadFile" class="btn btn-default" :disabled="loading">
                                        <span v-show="loading">Сохранение..</span>
                                        <span v-show="!loading">Сохранить</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs-left">
            <ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
                <li class="active">
                    <a href="#tab-r1" data-toggle="tab">Общее</a>
                </li>
                <li>
                    <a href="#tab-r2" data-toggle="tab">Анонс</a>
                </li>
                <li>
                    <a href="#tab-r3" data-toggle="tab">Подробно</a>
                </li>
                <li>
                    <a href="#tab-r4" data-toggle="tab">Файлы</a>
                </li>
                <li>
                    <a href="#tab-r5" data-toggle="tab">Активность</a>
                </li>
            </ul>
            <div class="tab-content">

                <!-- #MAIN -->
                <div class="tab-pane active" id="tab-r1">
                    <fieldset>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Название:</label>
                                <div class="col-md-6">
                                    <input v-model="title" class="form-control" placeholder="Введите название">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- #ANOUNCE -->
                <div class="tab-pane" id="tab-r2">
                    <fieldset>
                        <legend>Картинка для анонса</legend>
                        <input type="file" id="announce_pic">

                        <img v-if="item.announce_pic" :src="item.announce_pic.path.replace('public/', '/storage/')" alt="announce">
                    </fieldset>
                    <br>
                    <br>
                    <fieldset>
                        <textarea name="detail" id="ckeditor-announce" v-html="announce"></textarea>
                    </fieldset>
                </div>

                <!-- #DETAIL -->
                <div class="tab-pane" id="tab-r3">
                    <fieldset>
                        <legend>Детальная картинка</legend>
                        <input type="file" id="detail_pic">

                        <img v-if="item.detail_pic" :src="item.detail_pic.path.replace('public/', '/storage/')" alt="detail">
                    </fieldset>
                    <br>
                    <br>
                    <fieldset>
                        <textarea name="detail" id="ckeditor-detail" v-html="detail"></textarea>
                    </fieldset>
                </div>

                <!-- #FILES -->
                <div class="tab-pane" id="tab-r4">
                    <fieldset>
                        <!-- #BUTTON ATTACH FILE -->
                        <a href="Javascript:void(0);" @click="attachFile" class="btn btn-default">Добавить</a>
                        <div class="clb"></div>
                        <br>

                        <!-- #FILES -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Действия</th>
                                    <th class="text-center">Наименование</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in list" :key="index">
                                    <td width="10%" align="center">
                                        <a href="Javascript:void(0);" class="btn btn-xs btn-default" @click="removeFile(index)" :disabled="loading">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a :href="item.path.replace('public/', '/storage/')" target="_blank">{{ item.original_name }}</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <!-- #ACTIVITY -->
                <div class="tab-pane" id="tab-r5">
                    <div class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Активность:</label>
                                <div class="col-md-6">
                                    <input type="checkbox" v-model="is_active" class="form-control">
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                                <!--<label class="col-md-2 control-label">Начало активности:</label>-->
                                <!--<div class="col-md-6">-->
                                    <!--<input type="text" v-model="started_at" class="form-control">-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<div class="form-group">-->
                                <!--<label class="col-md-2 control-label">Окончание активности:</label>-->
                                <!--<div class="col-md-6">-->
                                    <!--<input type="text" v-model="expired_at" class="form-control">-->
                                <!--</div>-->
                            <!--</div>-->
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="clb"></div>

            <!-- #ACTIONS -->
            <div class="text-center">
                <br>
                <br>
                <button class="btn btn-success" @click="save">Сохранить</button>
                <a href="/admin/sib/news" class="btn btn-default">Отменить</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewsDetail",
        props: {
            item: Object,
        },
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                dialog: '',
                errors: '',
                loading: false,

                announceEditor: null,
                detailEditor: null,

                editor: null,

                id: null,
                title: '',
                announce: '',
                detail: '',
                is_active: false,
                started_at: '',
                expired_at: '',
                list: [],
            }
        },
        mounted () {
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this;

                th.id           = th.item.id;
                th.title        = th.item.title || '';
                th.announce     = th.item.announce || '';
                th.detail       = th.item.detail || '';
                th.list         = th.item.files || [];
                th.is_active    = th.item.is_active || false;
                th.started_at   = th.item.started_at || '';
                th.expired_at   = th.item.expired_at || '';

                //
                th.dialog = $('#'+this.modalId);
                th.dialog.on('hidden.bs.modal', function () {
                    th.refresh();
                });

                //
                th.announceEditor = CKEDITOR.replace('ckeditor-announce', { height: '380px', startupFocus : true} );
                th.detailEditor = CKEDITOR.replace('ckeditor-detail', { height: '380px', startupFocus : true} );
            },
            refresh: function () {
                this.errors = '';
                document.querySelector('#new-file').value = null;
            },
            save: function (params, callback) {
                let th = this;
                th.loading = true;

                if (th.id) {
                    th.update();
                } else {
                    if (th.title.length == 0) {
                        th.title = 'noname';
                    }

                    let formData = new FormData(),
                        announcePic = document.querySelector('#announce_pic'),
                        detailPic = document.querySelector('#detail_pic');

                    formData.append('title', th.title);
                    formData.append('announce', th.announceEditor.getData());
                    formData.append('detail', th.detailEditor.getData());
                    formData.append('is_active', th.is_active);
                    // formData.append('started_at', th.started_at);
                    // formData.append('expired_at', th.expired_at);

                    if (announcePic.files.length > 0) {
                        formData.append('announce_pic', announcePic.files[0]);
                    }

                    if (detailPic.files.length > 0) {
                        formData.append('detail_pic', detailPic.files[0]);
                    }

                    axios.post('/admin/sib/news', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            th.loading      = false;
                            th.id           = response.data.id;
                            th.title        = response.data.title;
                            th.announce     = response.data.announce;
                            th.detail       = response.data.detail;
                            th.is_active    = response.data.is_active;
                            th.started_at   = response.data.started_at;
                            th.expired_at   = response.data.expired_at;

                            if (callback) {
                                callback();
                            } else {
                                location.href = '/admin/sib/news';
                            }

                        }).catch((error) => {
                            th.errors = error;
                            th.loading = false;
                    })
                }
            },
            update: function (callback) {
                let th = this,
                    formData = new FormData(),
                    announcePic = document.querySelector('#announce_pic'),
                    detailPic = document.querySelector('#detail_pic');

                th.loading = true;

                formData.append('title', th.title);
                formData.append('announce', th.announceEditor.getData());
                formData.append('detail', th.detailEditor.getData());
                formData.append('is_active', th.is_active);
                // formData.append('started_at', th.started_at);
                // formData.append('expired_at', th.expired_at);

                if (announcePic.files.length > 0) {
                    formData.append('announce_pic', announcePic.files[0]);
                }

                if (detailPic.files.length > 0) {
                    formData.append('detail_pic', detailPic.files[0]);
                }

                axios.post('/admin/sib/news/'+th.id, formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then((response) => {
                        th.loading      = false;
                        th.title        = response.data.title;
                        th.announce     = response.data.announce;
                        th.detail       = response.data.detail;
                        th.is_active    = response.data.is_active;
                        th.started_at   = response.data.started_at;
                        th.expired_at   = response.data.expired_at;

                        CKEDITOR.instances['ckeditor-announce'].setData(th.announce);
                        CKEDITOR.instances['ckeditor-detail'].setData(th.detail);

                        if (callback) {
                            callback();
                        } else {
                            location.href = '/admin/sib/news';
                        }

                    }).catch((error) => {
                        th.errors = error;
                        th.loading = false;
                    })
            },
            attachFile: function () {
                this.dialog.modal('show');
            },
            removeFile: function (index) {
                this._removeFile(index);
            },
            uploadFile: function () {
                if (this.id > 0) {
                    this._uploadFile();
                } else {
                    this.save({}, this._uploadFile);
                }
            },
            _removeFile: function (index) {
                let th = this;
                th.loading = true;

                if (confirm('Удалить?')) {

                    axios.delete('/admin/sib/news/'+th.id+'/files/'+th.list[index].id)
                        .then((response) => {

                            th.loading = false;
                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                th.list = response.data;
                                th.refresh();
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        })
                }
            },
            _uploadFile: function () {
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#new-file');

                th.loading = true;

                if (file.files.length > 0) {

                    for (var i = 0; i < file.files.length; i++) {
                        formData.append('files[]', file.files[i]);
                    }

                    axios.post('/admin/sib/news/'+th.id+'/files', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            th.loading = false;

                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                th.list = response.data;
                                th.refresh();
                                th.dialog.modal('hide');
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        });
                } else {
                    th.errors = 'Прикрепите файл';
                    th.loading = false;
                }
            },
        }
    }
</script>

<style scoped>
    img {
        max-width: 200px;
    }
</style>