<template>
    <div class="row">
        <form id="wizard-1" novalidate="novalidate">
            <div id="bootstrap-wizard-1" class="col-sm-12">
                <div class="form-bootstrapWizard">
                    <ul class="bootstrapWizard form-wizard">
                        <li class="active" data-target="#step1">
                            <a href="#tab1" data-toggle="tab" class="hidden"></a>
                            <span class="step">1</span>
                            <span class="title">
                                Оплата <br>
                                (резидент-новичок)
                            </span>
                        </li>
                        <li data-target="#step2">
                            <a href="#tab2" data-toggle="tab" class="hidden"></a>
                            <span class="step">2</span>
                            <span class="title">
                                Подтверждение <br>
                                (куратор)
                            </span>
                        </li>
                        <li data-target="#step3">
                            <a href="#tab3" data-toggle="tab" class="hidden"></a>
                            <span class="step">3</span>
                            <span class="title">
                                Активация <br>
                                (администратор)
                            </span>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <br>
                <div class="tab-content">

                    <!-- #TAB1-->
                    <div class="tab-pane active" id="tab1">
                        <br>
                        <h5><strong>Шаг 1: Оплата (резидент-новичок)</strong></h5>
                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    <p>
                                        ⠀⠀Необходимо произвести оплату резидентства OSS на карту Куратора и прикрепить квитанцию (скриншот) об оплате, нажав соответствующую кнопку ниже.
                                    </p>
                                    <p>
                                        ⠀⠀Реквизиты для оплаты нужно запросить у Куратора: <b>{{ activationData.curator.name }} {{ activationData.curator.last_name }}</b>,
                                        <a :href="'tel:'+activationData.curator.phone"><b>{{ activationData.curator.phone }}</b></a>.
                                    </p>
                                    <template v-if="!isQuittanceSent">
                                        <p>
                                            ⠀⠀Для завершения этого шага необходимо нажать кнопку «Далее».
                                        </p>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <template v-if="isQuittanceSent">
                                            <h3 class="text-center text-success">
                                                <strong><i class="fa fa-check fa-lg"></i> Квитанция отправлена</strong>
                                            </h3>
                                        </template>
                                        <template v-else>
                                            <label>
                                                <input type="file" name="quittance" id="quittance" :disabled="isQuittanceSent">
                                                <small id="fileHelp" class="form-text text-muted">
												
												Прикрепите квитанцию (скриншот).Допустимые файлы: jpg, png, pdf, размером не более 20мб
												
												
												</small>
                                                <br>
                                                <span class="error-block"></span>
                                                <span class="text-red">
                                                {{ errors }}
                                            </span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- #TAB2-->
                    <div class="tab-pane" id="tab2">
                        <br>
                        <h5><strong>Шаг 2: Подтверждение (куратор)</strong></h5>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>
                                    ⠀⠀Поздравляем с оплатой. Теперь Твой Куратор должен произвести подтверждение Администратору системы и после этого Ты автоматически перейдёшь к последнему шагу активации.

                                </p>
                                <p>
                                    ⠀⠀В случае возникновения вопросов обращайся к Куратору.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- #TAB3-->
                    <div class="tab-pane" id="tab3"></div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="pager wizard no-margin">
                                    <li class="previous disabled">
                                        <a href="javascript:void(0);" class="btn btn-default">Вернуться</a>
                                    </li>
                                    <li class="next">
                                        <a href="javascript:void(0);" class="btn txt-color-darken">Далее</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "ActivationStep1",
        props: {
            activationData: Object,
        },
        data () {
            return {
                errors: '',
                loading: false,
                indexx:'',
                isQuittanceSent: false,
                wizard: null,
            }
        },
        mounted() {
            console.info('wizard:step 1');
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this;
                let validator = $("#wizard-1").validate({
                    rules: {
                        quittance: {
                            required: true,
                        },
                    },
                    messages: {
                        quittance: 'Прикрепите квитанцию (скриншот) об оплате',
                    },
                    highlight: function (element) {
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    },
                    unhighlight: function (element) {
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function (error, element) {
                        if (element.parent('label').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                th.wizard = $('#bootstrap-wizard-1').bootstrapWizard({
                    'tabClass': 'form-wizard',
                    'onNext': function (tab, navigation, index) {
						
					let file = document.querySelector('#quittance');
					
					if(file){
					 let ext = file.files[0].name.split('.').pop();
					 if(ext == 'jpg' || ext == 'png' || ext == 'pdf' || ext == 'JPG'){
					}else{
					  th.errors="Прикрепите файл, соответствующий требуемым условиям и попробуйте ещё раз";
						return false;
					}
				   }
				   
					if ($("#wizard-1").valid() == false) {
							console.log('step1 wisard valid false');
                            validator.focusInvalid();
                            return false;
                        } else {
                           var p = false;
                            if (index == 1) {
								
                                $('#bootstrap-wizard-1').find('li.next').hide();
                                  if (!th.isQuittanceSent) {
                                    var p = new Promise(function(resolve,reject){
									th.indexx = index;
									th.sendQuittance()
									  
								  })
                                }
                            
							if(p){
								
							p.then(function(){
								
							$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
							 $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
							
                           })
						  }else{
							  $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
							   $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
						  }
						}//index 1
                       }
                    },
                    'onPrevious': function (tab, navigation, index) {
						$('.okno').removeClass('okno4');
                        $('#bootstrap-wizard-1').find('li.next').show();
                    },
                });
            },
            sendQuittance: function () {
				
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#quittance');

                if (file.files.length == 0) {
                    th.errors = 'Прикрепите квитанцию';
                    console.error('Квитацния не прикреплена');
                } else {
                    th.loading = true;
                    formData.append('quittance', file.files[0]);
                    formData.append('productId', th.activationData.product.id);
                     $('.okno').addClass('okno4');
                    axios.post('/oss/requisitions', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
							console.log('ajax 0');
							if(response.data.html){
								if(response.data.html =='html'){
									$('.okno').removeClass('okno4');
                                    th.loading = false;
									console.log('ajax 1');
									this.show();
									return false;
								}
							}
							
							
							
                            if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                                th.errors = response.data.error;
                            } else {
                                th.isQuittanceSent = true;
                                th.errors = '';
                            }
							console.log('ajax success');
							$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(th.indexx-1).addClass('complete');
							$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(th.indexx-1).find('.step').html('<i class="fa fa-check"></i>');
							$('.okno').removeClass('okno4');
                            th.loading = false;
                        })
                        .catch((error) => {
                            th.errors = 'Прикрепите файл, соответствующий требуемым условиям и попробуйте ещё раз';
                            th.wizard.bootstrapWizard('previous');
                            console.error(error);
                        });
                }
            }
        },
    }
</script>

<style scoped>
    .bootstrapWizard li {
        width: 33%;
    }
    #wizard-1 p {
        text-align: justify;
    }
</style>