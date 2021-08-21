<template>
    <div>
        <!-- #MODAL -->
        <div class="modal fade" :id="modalId">
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

        <!-- #GRID -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Actions</th>
                    <th v-for="col in cols">
                        {{ col.name }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, index) in rows" :key="index">
                    <td width="10%">
                        <button class="btn btn-xs btn-default" @click="edit(index)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-xs btn-default" @click="save(index)">
                            <i class="fa fa-save"></i>
                        </button>
                        <button class="btn btn-xs btn-default" @click="remove(index)">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                    <td v-for="item in row" :style="'text-align:'+item.align">
                        {{ item.value }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import BaseGrid from './BaseGrid.vue';

    export default {
        name: "EditableGrid",
        extends: BaseGrid,
        data () {
            return {
                modalId: 'modal-grid-' + Math.floor(Math.random() * 1000000),
                loading: false,
                errors: '',
                api: '',
            }
        },
        mounted() {
            this.loadData()
        },
        methods: {
            loadData () {
                this.cols = [
                    {name: 'id'},
                    {name: 'name'}
                ];
                this.rows = [
                    [
                        {value: '1', name: '', label: 'ID', type: 'text', align: 'center'},
                        {value: 'one', name: '', label: 'Наименование', type: 'text', align: 'center'},
                    ],
                    [
                        {value: '2', name: '', label: 'ID', type: 'text', align: 'center'},
                        {value: 'two', name: '', label: 'Наименование', type: 'text', align: 'center'},
                    ],
                ];
            },
            edit: function (index) {
                let th = this;
                th.showModal();
            },
            save: function (index) {
                let th = this;
                th.showModal();
            },
            remove: function (index) {
                let th = this;
                th.showModal();
            },
            showModal: function () {
                $('#'+this.modalId).modal('show');
            }
        }
    }
</script>

<style scoped>
    table tr th {
        text-align: center;
    }
    table tr td {
        text-align: center;
    }
</style>