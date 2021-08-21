<template>
    <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false" data-widget-fullscreenbutton="true">
        <header>
            <span class="widget-icon">
                <i class="fa fa-fw fa-mortar-board"></i>
            </span>
            <h2>Online Smart System / WakeUpERA / Ссылки на прямой эфир</h2>
        </header>

        <div>
            <div class="jarviswidget-editbox"></div>
            <div class="widget-body">

                <!-- #MODAL -->
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
                                                <label class="col-md-2 control-label">Ссылка доступна с</label>
                                                <div class="col-md-10">
                                                    <input v-model="started_at" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Ссылка доступна до</label>
                                                <div class="col-md-10">
                                                    <input v-model="expired_at" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Ссылка</label>
                                                <div class="col-md-10">
                                                    <input v-model="link" class="form-control">
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
                            <th>Ссылка доступна с</th>
                            <th>Ссылка доступна до</th>
                            <th>Ссылка</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in broadcastData" :key="index">
                            <td width="10%">
                                <button class="btn btn-xs btn-default" @click="edit(index)" :disabled="loading">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-xs btn-default" @click="remove(index)" :disabled="loading">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                            <td>{{ item.started_at }}</td>
                            <td>{{ item.expired_at }}</td>
                            <td>{{ item.link }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "WakeUpEraBroadcast",
        props: {
            broadcastData: Array,
        },
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                dialog: '',
                action: '',
                errors: '',
                loading: false,

                id: '',
                started_at: '',
                expired_at: '',
                link: '',
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
                console.debug('WakeUpEraBroadcast', th.broadcastData);
            },
            refresh: function () {
                this.action = '';

                this.id = '';
                this.errors = '';
                this.started_at = '';
                this.expired_at = '';
                this.link = '';
            },
            create: function () {
                this.action = 'create';
                this.showModal();
            },
            edit: function (index) {
                let th = this;
                th.action = 'edit';

                th.id           = th.broadcastData[index].id;
                th.started_at   = th.broadcastData[index].started_at;
                th.expired_at   = th.broadcastData[index].expired_at;
                th.link         = th.broadcastData[index].link;

                th.showModal();
            },
            save: function () {
                let th = this;
                let data = {
                    product_id: 5,
                    started_at: th.started_at,
                    expired_at: th.expired_at,
                    link: th.link,
                };

                if (th.action == 'create') {
                    axios.post('/admin/oss/wake-up-era/broadcasts', data)
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
                    axios.put('/admin/oss/wake-up-era/broadcasts/'+th.id, data)
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
                    axios.delete('/admin/oss/wake-up-era/broadcasts/'+th.broadcastData[index].id)
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
        }
    }
</script>

<style scoped>
    table tr th, td {
        text-align: center;
    }
</style>