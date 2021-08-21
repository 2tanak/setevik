<template>
    <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
        <header>
            <span class="widget-icon">
                <i class="fa fa-fw fa-mortar-board"></i>
            </span>
            <h2>Online Smart System / WakeUpERA / Записи текущих эфиров / Эфиры в постоянном доступе</h2>
        </header>

        <div>
            <div class="jarviswidget-editbox"></div>
            <div class="widget-body">

                <!-- #MODAL VIDEO PLAYER -->
                <div class="modal fade" id="modal-video-player">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title text-left">Видео</h4>
                            </div>
                            <div class="modal-body">
                                <video-player :link="video_link" :poster="video_poster"></video-player>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- #MODAL ADD/EDIT -->
                <div class="modal fade" :id="modalId">
                    <div class="modal-dialog modal-lg">
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
                                                <label class="col-md-2 control-label">Тема эфира<span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <input v-model="title" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Описание<span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <input v-model="description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Спикер(ы)<span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <input v-model="speaker" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Дата эфира<span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <input v-model="date" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Видео доступно с</label>
                                                <div class="col-md-10">
                                                    <input v-model="started_at" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Видео доступно до</label>
                                                <div class="col-md-10">
                                                    <input v-model="expired_at" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Доступно постоянно</label>
                                                <div class="col-md-10">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" v-model="is_available">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Видеофайл</label>
                                                <div class="col-md-10">
                                                    <span v-if="file_name.length" class="text-green">{{ file_name }}</span>
                                                    <input type="file" id="new-file" class="btn btn-default">
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
                                            <button @click="save" class="btn btn-default" :disabled="loading">
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

                <!-- #BUTTON CREATE -->
                <button class="btn btn-default" @click="create">Добавить</button>
                <div class="clb"></div>
                <br>

                <!-- #GRID -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Действия</th>
                            <th>Тема эфира</th>
                            <th>Описание</th>
                            <th>Спикер(ы)</th>
                            <th>Дата эфира</th>
                            <th>Видео доступно с</th>
                            <th>Видео доступно до</th>
                            <th>Доступно постоянно</th>
                            <th>Файл</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in grid" :key="index">
                            <td width="10%">
                                <button class="btn btn-xs btn-default" @click="edit(index)" :disabled="loading">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-xs btn-default" @click="remove(index)" :disabled="loading">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.speaker }}
                            <td>{{ item.date }}</td>
                            <td>{{ item.started_at }}</td>
                            <td>{{ item.expired_at }}</td>
                            <td>
                                <span v-if="item.is_available">Да</span>
                                <span v-else>Нет</span>
                            </td>
                            <td>
                                <a v-if="item.file_id" href="Javascript:void(0);" @click="modalShowVideoPlayer(index)">Открыть</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from './../../media/VideoPlayer.vue';

    export default {
        name: "WakeUpEraBroadcastVideo",
        props: {
            grid: Array,
        },
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                dialog: '',
                action: '',
                errors: '',
                loading: false,

                id: '',
                title: '',
                description: '',
                speaker: '',
                date: '',
                started_at: '',
                expired_at: '',
                is_available: '',

                file_name: '',

                video_link: '',
                video_poster: '/img/courses/wakeupera-wide.png',
            }
        },
        mounted() {
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this;

                //
                th.dialog = $('#'+this.modalId);
                th.dialog.on('hidden.bs.modal', function () {
                    th.refresh();
                });

                //
                console.debug('WakeUpEraBroadcastVideo', th.grid);
            },
            refresh: function () {
                this.action = '';
                this.errors = '';

                this.id = '';
                this.title = '';
                this.description = '';
                this.speaker = '';
                this.date = '';
                this.started_at = '';
                this.expired_at = '';
                this.is_available = '';

                this.file_name = '';
            },
            create: function () {
                this.action = 'create';
                this.showModal();
            },
            edit: function (index) {
                let th = this;
                th.action = 'edit';

                th.id           = th.grid[index].id;
                th.title        = th.grid[index].title;
                th.description  = th.grid[index].description;
                th.speaker      = th.grid[index].speaker;
                th.date         = th.grid[index].date;
                th.started_at   = th.grid[index].started_at;
                th.expired_at   = th.grid[index].expired_at;
                th.is_available = th.grid[index].is_available ? 1 : 0;

                if (th.grid[index].file_name) {
                    th.file_name = th.grid[index].file_name;
                }

                th.showModal();
            },
            save: function () {
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#new-file');

                th.loading = true;

                formData.append('title', th.title);
                formData.append('description', th.description);
                formData.append('speaker', th.speaker);
                formData.append('date', (th.date == undefined ? '' : th.date));
                formData.append('started_at', (th.started_at == undefined ? '' : th.started_at));
                formData.append('expired_at', (th.expired_at == undefined ? '' : th.expired_at));
                formData.append('is_available', (th.is_available ? 1 : 0));


                if (file.files.length > 0) {
                    formData.append('video', file.files[0]);
                }

                if (th.action == 'create') {
                    axios.post('/admin/oss/wake-up-era/broadcast-videos', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            th.loading = false;
                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                // todo: hot update
                                location.reload();
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        });
                } else if (th.action == 'edit') {
                    axios.post('/admin/oss/wake-up-era/broadcast-videos/'+th.id, formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            th.loading = false;
                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                // todo: hot update
                                location.reload();
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        });
                } else {
                    alert('Action is empty');
                }
            },
            remove: function (index) {
                if (confirm('Удалить?')) {
                    let th = this;
                    axios.delete('/admin/oss/wake-up-era/broadcast-videos/'+th.grid[index].id)
                        .then((response) => {
                            th.loading = false;
                            if (typeof response.data.errors != 'undefined' && response.data.errors.length > 0) {
                                th.errors = response.data.errors;
                            } else {
                                location.reload();
                            }
                        })
                        .catch((error) => {
                            th.loading = false;
                            console.error(error);
                        })
                }
            },
            showModal: function () {
                this.dialog.modal('show');
            },
            modalShowVideoPlayer: function (key) {
                let th = this;
                th.video_link = '/admin/oss/wake-up-era/broadcast-videos/'+th.grid[key].id+'/source';
                let m = $('#modal-video-player');
                m.on('hidden.bs.modal', function () {
                    th.$children[0].destroy();
                });
                m.modal('show');
            }
        },
        components: {
            VideoPlayer,
        }
    }
</script>

<style scoped>
    table tr th, td {
        text-align: center;
    }
</style>