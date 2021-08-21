<template>
    <div :id="'modal-wizard-subscription-purchase-'+product.id" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Покупка абонемента</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form novalidate="novalidate">
                            <div :id="'bootstrap-wizard-subscription-purchase-'+product.id" class="col-sm-12">
                                <div class="form-bootstrapWizard">
                                    <ul class="bootstrapWizard form-wizard">
                                        <!-- #STEP 1 -->
                                        <template v-if="step == 1">
                                            <li data-target="#step1" class="active">
                                                <a href="#wizard-tab-subscription-purchase-1" data-toggle="tab" class="hidden"></a>
                                                <span class="step">1</span>
                                                <span class="title">Оплата <br>(резидент)</span>
                                            </li>
                                            <li data-target="#step2">
                                                <a href="#wizard-tab-subscription-purchase-2" data-toggle="tab" class="hidden"></a>
                                                <span class="step">2</span>
                                                <span class="title">Подтверждение <br>(активный куратор)</span>
                                            </li>
                                            <li data-target="#step3">
                                                <a href="#wizard-tab-subscription-purchase-3" data-toggle="tab" class="hidden"></a>
                                                <span class="step">3</span>
                                                <span class="title">Активация <br>(администратор)</span>
                                            </li>
                                        </template>

                                        <!-- #STEP 2 -->
                                        <template v-if="step == 2">
                                            <li data-target="#step1" class="complete">
                                                <a href="#wizard-tab-subscription-purchase-1" data-toggle="tab" class="hidden"></a>
                                                <span class="step"><i class="fa fa-check"></i></span>
                                                <span class="title">Оплата <br>(резидент)</span>
                                            </li>
                                            <li data-target="#step2" class="active">
                                                <a href="#wizard-tab-subscription-purchase-2" data-toggle="tab" class="hidden"></a>
                                                <span class="step">2</span>
                                                <span class="title">Подтверждение <br>(активный куратор)</span>
                                            </li>
                                            <li data-target="#step3">
                                                <a href="#wizard-tab-subscription-purchase-3" data-toggle="tab" class="hidden"></a>
                                                <span class="step">3</span>
                                                <span class="title">Активация <br>(администратор)</span>
                                            </li>
                                        </template>

                                        <!-- #STEP 3 -->
                                        <template v-if="step == 3">
                                            <li data-target="#step1" class="complete">
                                                <a href="#wizard-tab-subscription-purchase-1" data-toggle="tab" class="hidden"></a>
                                                <span class="step"><i class="fa fa-check"></i></span>
                                                <span class="title">Оплата <br>(резидент)</span>
                                            </li>
                                            <li data-target="#step2" class="complete">
                                                <a href="#wizard-tab-subscription-purchase-2" data-toggle="tab" class="hidden"></a>
                                                <span class="step"><i class="fa fa-check"></i></span>
                                                <span class="title">Подтверждение <br>(активный куратор)</span>
                                            </li>
                                            <li data-target="#step3" class="active">
                                                <a href="#wizard-tab-subscription-purchase-3" data-toggle="tab" class="hidden"></a>
                                                <span class="step">3</span>
                                                <span class="title">Активация <br>(администратор)</span>
                                            </li>
                                        </template>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <br>
                                <div class="tab-content">

                                    <!-- #TAB1-->
                                    <div class="tab-pane" id="wizard-tab-subscription-purchase-1" :class="{active: step == 1}">
                                        <br>
                                        <h5><strong>Шаг 1: Оплата (резидент)</strong></h5>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div>
                                                    <p>
                                                        ⠀⠀Необходимо произвести оплату резидентства OSS на карту вышестоящего Куратора,
                                                        имеющего действующий абонемент, и прикрепить квитанцию (скриншот) об оплате,
                                                        нажав соответствующую кнопку ниже.
                                                    </p>
                                                    <p>
                                                        ⠀⠀Реквизиты для оплаты нужно запросить у вышестоящего «активного» Куратора:

                                                        <template v-if="curator">
                                                            <b>{{ curator.name }} {{ curator.last_name }}</b>,
                                                            <a :href="'tel:'+curator.phone">{{ curator.phone }}</a>
                                                        </template>
                                                    </p>
                                                    <p>
                                                        ⠀⠀Для завершения этого шага и перехода к следующему необходимо нажать кнопку «Далее».
                                                    </p>
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
                                                                <input type="file" name="quittance" id="subscription-purchase-quittance" :disabled="isQuittanceSent">
                                                                <small id="fileHelp" class="form-text text-muted">Размер файла не должен превышать 20 MB.</small>
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
                                    <div class="tab-pane" id="wizard-tab-subscription-purchase-2" :class="{active: step == 2}">
                                        <br>
                                        <h5><strong>Шаг 2: Подтверждение (куратор)</strong></h5>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p>⠀⠀Отлично. Теперь Твой вышестоящий Куратор должен произвести подтверждение Администратору системы
                                                    и после этого Ты автоматически перейдёшь к последнему шагу активации абонемента,
                                                    которое производит Администратор.
                                                </p>
                                                <p>⠀⠀В случае возникновения вопросов обращайся к Куратору.</p>
                                                <h3 v-if="step == 3" class="text-center text-success">
                                                    <strong>
                                                        <i class="fa fa-check fa-lg"></i> Оплата подтверждена Куратором
                                                    </strong>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- #TAB3-->
                                    <div class="tab-pane" id="wizard-tab-subscription-purchase-3" :class="{active: step == 3}">
                                        <br>
                                        <h5><strong>Шаг 3: Активация (администратор)</strong></h5>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p>
                                                    ⠀⠀Куратор подтвердил оплату, Ты на финишной прямой. <br>
                                                    ⠀⠀Осталось подождать пока Администратор активирует кабинет.
                                                    Для этого, как Ты знаешь, в нашей Системе предусмотрено два рабочих!!! часа.
                                                </p>
                                                <p>
                                                    ⠀⠀В случае возникновения вопросов Ты можешь обращаться к своему Куратору, а также к Администратору системы:
                                                </p>
                                                <div class="text-center">
                                                        <span>
                                                            E-mail: <a href="mailto:support@sib.company">support@sib.company</a><br>
                                                            Telegram (messenger): <a href="https://t.me/era_school_02">@era_school_02</a> <br>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SubscriptionPurchase",
        props: {
            product: Object,
            curator: Object,
        },
        data () {
            return {
                errors: '',
                loading: false,
                wizard: null,
                step: 0,
                isQuittanceSent: false,
            }
        },
        mounted () {
            // init wizard steps
            if (this.product.requisitions.length > 0) {
                this.isQuittanceSent = true;
                if (this.product.requisitions[0].curator_quittance_id) {
                    this.step = 3;
                } else {
                    this.step = 2;
                }
            } else {
                this.step = 1;
            }

            // init others
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function (product) {
                let th = this,
                    form = $('#modal-wizard-subscription-purchase-'+th.product.id).find('form'),
                    wizardWrapper = $('#bootstrap-wizard-subscription-purchase-'+th.product.id);

                //
                let validator = form.validate({
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

                //
                th.wizard = wizardWrapper.bootstrapWizard({
                    'tabClass': 'form-wizard',
                    'onNext': function (tab, navigation, index) {
                        if (form.valid() == false) {
                            validator.focusInvalid();
                            return false;
                        } else {

                            if (index == 1) {

                                if (th.step != 3) {
                                    wizardWrapper.find('li.next').hide();
                                }

                                if (!th.isQuittanceSent) {
                                    th.sendQuittance();
                                }
                            }

                            if (index == 2) {
                                wizardWrapper.find('li.next').hide();
                            }

                            wizardWrapper.find('.form-wizard').children('li').eq(index - 1).addClass('complete');
                            wizardWrapper.find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
                        }
                    },
                    'onPrevious': function (tab, navigation, index) {
                        wizardWrapper.find('li.next').show();
                    },
                });

                // wizard step variants
                if (th.step != 1) {
                    wizardWrapper.find('li.next').hide();
                }
            },
            sendQuittance: function () {
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#subscription-purchase-quittance');

                if (file.files.length == 0) {
                    th.errors = 'Прикрепите квитанцию';
                    console.error('Квитацния не прикреплена');
                } else {
                    th.loading = true;
                    formData.append('quittance', file.files[0]);
                    formData.append('productId', th.product.id);

                    axios.post('/oss/requisitions', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {
                            if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                                //th.errors = response.data.error;
                                th.errors = 'Некорректный файл';
                                th.wizard.bootstrapWizard('previous');
                            } else {
                                th.isQuittanceSent = true;
                                th.errors = '';
                            }
                            th.loading = false;
                        })
                        .catch((error) => {
                            //th.errors = 'Размер файла превышает 20 MB';
                            th.errors = 'Некорректный файл';
                            th.wizard.bootstrapWizard('previous');
                            console.error(error);
                        });
                }
            },
            show: function () {
                $('#modal-wizard-subscription-purchase-'+this.product.id).modal('show');
            },
        }
    }
</script>

<style scoped>
    .bootstrapWizard li {
        width: 33%;
    }
</style>