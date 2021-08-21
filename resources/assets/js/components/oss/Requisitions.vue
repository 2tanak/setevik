<template>
    <div class="requisitions-wrapper">

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

        <!-- #MODAL -->
        <div id="modal-sib-oss-requisition" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">
                            Подтверждение заявки
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errors.length" class="alert alert-danger fade in">
                            <i class="fa-fw fa fa-times pull-right"></i>
                            <strong v-html="errors" class="text-left"></strong>
						
                            <br>
                        </div>
							 <div class="error" v-html="err">
                        </div>
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
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Файл</label>
                                        <div class="col-md-10">
                                            <input type="file" id="file" class="btn btn-default">
                                            <p class="help-block">

												

                                                <i>Прикрепите квитанцию (скриншот) об оплате в Компанию за приглашённого.Допустимые файлы jpg, png, pdf, размером не более 20мб</i>

                                            </p>
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

        <!-- #ROWS -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered table-requisition">
                <tbody>
                    <tr>
                        <th class="text-center">Дата</th>
                        <th class="text-center" style="width: 40%">Инициатор</th>
                        <th class="text-center">Контакты</th>
                        <th class="text-center">Продукт</th>
                        <th class="text-center">Вид заявки</th>
                        <th class="text-center">Статус</th>
                    </tr>

                    <tr v-for="item in requisitionData" :key="item.id">
                        <td>
                            {{ item.created_at}}
                        </td>
                        <td>
                            {{ item.user.name}} {{ item.user.last_name}} <br>
                            <a v-if="item.user_quittance_id" href="Javascript:void(0);" @click="showModalQuittance(item.user_quittance_id)">(квитанция)</a>
                        </td>
                        <td>
                            {{ item.user.phone }}
                        </td>
                        <td>
                            <a href="/oss/products">
                                {{ item.product.name }}
                            </a>
                        </td>
                        <td>
                            {{ item.requisition_type.name }}
                        </td>
                        <td>
                            <template v-if="item.is_cancelled">
                                Отменена
                            </template>
                            <template v-else-if="item.is_confirmed">
                                Подтвержден
                            </template>
                            <template v-else-if="item.curator_quittance_id">
                                В обработке
                            </template>
                            <template v-else>
                                <a href="#" @click="show(item.id)">
                                    Подтвердить
                                </a>
                            </template>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Requisitions",
        props: {
            requisitionData: Array,
        },
        data() {
            return {
                errors: '',
                loading: false,
                selected: 0,
                name: '',
                last_name: '',
                product: '',
                err:'',
                selectedQuittance: 0,
                quittanceSource: '',
            }
        },
        methods: {
            refresh: function () {
                this.errors = '';
                this.loading = false;
                this.selected = 0;
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
            show: function (id) {
                let th = this;
                th.selected = id;

                th.requisitionData.forEach(function (item) {
                    if (item.id == th.selected) {
                        th.name = item.user.name;
                        th.last_name = item.user.last_name;
                        th.product = item.product.name;
                    }
                });

                var m = $('#modal-sib-oss-requisition');
                m.modal('show');
            },
            confirm: function () {
				
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#file');
					if(file){
						
					 let ext = file.files[0].name.split('.').pop();
					 if(ext == 'jpg' || ext == 'png' || ext == 'pdf' || ext == 'JPG' || ext == 'JPEG' || ext == 'JPEG'){
					}else{
						
					  th.err='<div class="alert alert-danger fade in"><i class="fa-fw fa fa-times pull-right"></i><strong class="text-left">Ошибка в файле. Попробуйте еще раз</strong></div>'
					  
					  
					  
						return false;
					}
				   }
				   
					
					

                if (file.files.length == 0) {
                    th.errors = 'Прикрепите квитанцию';
                } else {
                    th.loading = true;
                    formData.append('quittance', file.files[0]);
                    axios.post('/oss/requisitions/'+th.selected+'/curator-file-upload', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
							console.log(response.data.html);
							if(response.data.html == 'html'){
								this.err = response.data.view;
								th.loading = false;
								return false;
							}
							
                            if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                                th.errors = response.data.error;
                            } else {
                                location.reload();
                            }
                            th.loading = false;
                        });
                }

                //setTimeout(th.refresh, 2000);
            }

        },
        mounted() {
            //console.info(this.requisitionData)
        }
    }
</script>

<style scoped>
    .requisitions-wrapper table tr td {
        text-align: center;
    }
    .requisitions-wrapper input {
        width: 100%;
    }
</style>