<template>
    <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
        <header>
            <span class="widget-icon">
                <i class="fa fa-fw fa-certificate"></i>
            </span>
            <h2>Online Smart System / Обучение</h2>
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
                                                <label class="col-md-2 control-label" for="select-type">Тип</label>
                                                <div class="col-md-10">
                                                    <select v-model="selectedType" class="form-control" id="select-type">
                                                        <option v-for="(type, index) in types" :value="index">{{ type.name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label" for="select-2">Родитель</label>
                                                <div class="col-md-10">
                                                    <select v-model="parent_id" class="form-control" id="select-2">
                                                        <option value="0" selected>Без родителя</option>
                                                        <option v-for="item in types[selectedType].videos" v-if="item.id != id" :value="item.id">{{ item.title }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Тема<span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <input v-model="title" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Описание</label>
                                                <div class="col-md-10">
                                                    <input v-model="description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Спикер(ы)</label>
                                                <div class="col-md-10">
                                                    <input v-model="speaker" class="form-control">
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

                <div class="row">
                    <div class="col-xs-12">

                        <div v-for="(type, index) in types">
                            <h2>{{ type.name }}</h2>
                            <hr>
                            <div class="dd" :id="'nestable-'+index">
                                <ol class="dd-list">

                                    <li v-for="item in type.videos" :key="item.id" class="dd-item dd3-item" :data-id="item.id">
                                        <div class="dd-handle dd3-handle">Drag</div>
                                        <div class="dd3-content">

                                            <template v-if="item.file_id">
                                                <a href="Javascript:void(0);" @click="modalShowVideoPlayer(item)">
                                                    {{ item.title }}
                                                </a>
                                            </template>
                                            <template v-else>
                                                {{ item.title }}
                                            </template>

                                            <div class="pull-right">
                                                <div class="no-margin">
                                                    <button class="btn btn-xs btn-default" @click="edit(item)" :disabled="loading">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-default" @click="remove(item)" :disabled="loading">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                </ol>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from './../../media/VideoPlayer.vue';

    export default {
        name: "Attestation",
        props: {
            grid: Array,
            types: Array,
        },
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                dialog: '',
                action: '',
                errors: '',
                loading: false,

                selectedType: 0,

                id: '',
                parent_id: 0,
                learn_video_type_id: 1,
                title: '',
                description: '',
                speaker: '',

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
                for (let i = 0; i < th.types.length; i++) {
                    $('#nestable-'+i).nestable();
                }

                console.debug('Attestation', th.grid);
            },
            refresh: function () {
                this.action = '';
                this.errors = '';

                this.selectedType = 0;

                this.id = '';
                this.parent_id = 0;
                this.learn_video_type_id = 1;
                this.title = '';
                this.description = '';
                this.speaker = '';

                this.file_name = '';
            },
            create: function () {
                this.action = 'create';
                this.showModal();
            },
            edit: function (item) {
                let th = this;
                th.action = 'edit';

                th.types.forEach(function (v, k) {
                    if (v.id == item.learn_video_type_id) {
                        th.selectedType = k;
                    }
                });

                th.id                   = item.id;
                th.parent_id            = item.parent_id;
                th.learn_video_type_id  = item.learn_video_type_id;
                th.title                = item.title;
                th.description          = item.description;
                th.speaker              = item.speaker;

                if (item.file) {
                    th.file_name = item.file.original_name;
                }

                th.showModal();
            },
            save: function () {
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#new-file');

                th.loading = true;

                formData.append('parent_id', th.parent_id);
                formData.append('learn_video_type_id', th.types[th.selectedType].id);
                formData.append('title', th.title);
                formData.append('description', th.description);
                formData.append('speaker', th.speaker);

                if (file.files.length > 0) {
                    formData.append('video', file.files[0]);
                }

                if (th.action == 'create') {
                    axios.post('/admin/oss/attestation', formData, {headers: {'Content-Type': 'multipart/form-data'}})
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
                    axios.post('/admin/oss/attestation/'+th.id, formData, {headers: {'Content-Type': 'multipart/form-data'}})
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
            remove: function (item) {
                if (confirm('Удалить?')) {
                    let th = this;
                    axios.delete('/admin/oss/attestation/'+item.id)
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
            modalShowVideoPlayer: function (item) {
                let th = this;
                th.video_link = '/admin/oss/attestation/'+th.grid[key].id+'/source';
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
    .dd {
        max-width: 100%;
    }
    .dd ol {
        padding-bottom: 50px;
    }
</style>