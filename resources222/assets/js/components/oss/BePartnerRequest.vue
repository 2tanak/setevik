<template>
    <div class="row" id="modal-wizard-subscription-purchase-1">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
            <div class="lockscreen animated flipInY">
                <div class="padding-20">
                    <img src="/img/SIB.png" class="img-responsive" alt="log">
                </div>
                <br>
                <div class="row">
                    <form novalidate="novalidate">
                        <div :id="'bootstrap-wizard-subscription-purchase-1'" class="col-sm-12">
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
                                            <span class="title">Регистрация <br>(резидент+куратор)</span>
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
                                            <span class="title">Регистрация <br>(резидент+куратор)</span>
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
                                            <span class="title">Регистрация <br>(резидент+куратор)</span>
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
                                                <p>⠀⠀Для начала Тебе необходимо выбрать пакет, который планируешь приобрести и произвести оплату в сумме, соответствующей выбранному пакету.</p>
                                                <p>⠀⠀Актуальные типы и стоимости пакетов, а также реквизиты компании для оплаты Ты можешь взять у своего вышестоящего активного куратора в структуре SIB:
                                                    <template v-if="activationData.curator">
                                                        <b>{{ activationData.curator.name }} {{ activationData.curator.last_name }}</b>,
                                                        <a :href="'tel:'+activationData.curator.phone">{{ activationData.curator.phone }}</a>.
                                                    </template>
                                                </p>
                                                <p>⠀⠀Для завершения этого шага и перехода к следующему необходимо прикрепить скриншот квитанции об оплате в поле ниже и нажать кнопку «Далее».</p>
                                                <br>
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
                                                            <span class="text-red">{{ errors }}</span>
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
                                    <h5><strong>Шаг 2: Регистрация (резидент+куратор)</strong></h5>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p>⠀⠀Отлично. Теперь Тебе нужно запросить у своего вышестоящего активного Куратора в структуре SIB ссылку для регистрации в его команде и вставить эту ссылку в соответствующее поле ниже.</p>
                                            <p>⠀⠀Далее нужно выбрать Пакет, по которому была произведена оплата и нажать кнопку «Далее».</p>
                                            <p>⠀⠀<b>Примечание.</b> Ссылка для регистрации может быть взята только у Куратора:
                                                <template v-if="activationData.curator">
                                                    <b>{{ activationData.curator.name }} {{ activationData.curator.last_name }}</b>,
                                                    <a :href="'tel:'+activationData.curator.phone">{{ activationData.curator.phone }}</a>.
                                                </template>
                                                Другие ссылки не подойдут и не будут пропущены системой.
                                            </p>
                                            <br>
                                            <div class="form-horizontal">
                                                <div class="form-group" :class="{'has-error': errors.length}">
                                                    <label class="col-md-2 control-label">Реф. ссылка:</label>
                                                    <div data-v-16cad91a="" class="col-md-10">
                                                        <input type="text" name="refLink" v-model="refLink" aria-required="true" aria-describedby="refLink-error" :disabled="isConfirmed" class="form-control" placeholder="Вставьте реф. ссылку Куратора">
                                                        <span class="text-error">{{ errors }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Пакет:</label>
                                                    <div class="col-md-10">
                                                        <select v-model="packageSelected" name="packageSelected" class="form-control" :disabled="isConfirmed">
                                                            <option disabled="disabled" value="0">Выберите пакет</option>
                                                            <option v-for="(pack, index) in activationData.packages" :key="index" :value="pack.id">{{ pack.name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 v-if="isConfirmed" class="text-center text-success">
                                                <strong>
                                                    <i class="fa fa-check fa-lg"></i> Ссылка зарегистрирована в системе
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
                                            <p>⠀⠀Отлично. Поздравляем с регистрацией в системе.</p>
                                            <p>⠀⠀Теперь осталось всего лишь подождать пока Администратор активирует Твой аккаунт SIB. Для этого, как Ты знаешь, в нашей системе предусмотрено два <b>рабочих!!!</b> часа.</p>
                                            <p>⠀⠀В случае возникновения вопросов Ты всегда можешь обращаться к своему Куратору (указан в предыдущих шагах) или к Администратору системы:</p>
                                            <div class="text-center">
                                                <span>
                                                    E-mail: <a href="mailto:support@sib.company">support@sib.company</a><br>
                                                    Telegram (messenger): <a href="https://t.me/era_school_01">@era_school_01 </a> <br>
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
</template>

<script>
    export default {
        name: 'BePartnerRequest',
        props: {
            activationData: Object,
        },
        data () {
            return {
                errors: '',
                loading: false,

                wizard: null,
                step: 0,
                isQuittanceSent: false,
                isConfirmed: false,

                packageSelected: 0,
                refLink: '',
            }
        },
        mounted () {
            //
            if (this.activationData.user.be_partner_request) {

                //
                if (this.activationData.user.be_partner_request.quittance_id) {
                    this.isQuittanceSent = true;
                    if (this.activationData.user.be_partner_request.curator_id) {
                        this.step = 3;
                    } else {
                        this.step = 2;
                    }
                } else {
                    this.step = 1;
                }

                //
                if (this.activationData.user.be_partner_request.package_id) {
                    this.isConfirmed = true;
                    this.packageSelected = this.activationData.user.be_partner_request.package_id;
                    this.refLink = this.activationData.user.be_partner_request.link;
                }
            } else {
                this.step = 1;
            }

            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this,
                    form = $('#modal-wizard-subscription-purchase-1').find('form'),
                    wizardWrapper = $('#bootstrap-wizard-subscription-purchase-1');

                //
                let validator = form.validate({
                    rules: {
                        quittance: {
                            required: true,
                        },
                        packageSelected: {
                            required: true,
                        },
                        refLink: {
                            required: true,
                        }
                    },
                    messages: {
                        quittance: 'Прикрепите квитанцию (скриншот) об оплате',
                        packageSelected: 'Выберите пакет',
                        refLink: 'Вставьте реферальную ссылку партнера',
                    },
                    highlight: function (element) {
                        th.refresh();
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    },
                    unhighlight: function (element) {
                        th.refresh();
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

                            if (index == 0) {
                                wizardWrapper.find('li.previous').hide();
                            } else {
                                wizardWrapper.find('li.previous').show();
                            }

                            if (index == 1) {
                                if (!th.isQuittanceSent) {
                                    th.sendQuittance();
                                }
                            }

                            if (index == 2) {
                                wizardWrapper.find('li.next').hide();
                                if (th.isConfirmed == false) {
                                    th.confirm();
                                }
                            }

                            wizardWrapper.find('.form-wizard').children('li').eq(index - 1).addClass('complete');
                            wizardWrapper.find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
                        }
                    },
                    'onPrevious': function (tab, navigation, index) {
                        if (index == 0) {
                            wizardWrapper.find('li.previous').hide();
                        } else {
                            wizardWrapper.find('li.previous').show();
                        }

                        wizardWrapper.find('li.next').show();
                    },
                });

                // wizard step variants
                if (th.step == 1) {
                    wizardWrapper.find('li.previous').hide();
                }
                if (th.step == 3) {
                    wizardWrapper.find('li.next').hide();
                }
            },
            refresh: function () {
                let th = this;
                th.errors = '';
            },
            confirm: function () {
                let th = this;

                th.refresh();

                if (th.packageSelected == 0) {
                    th.errors = 'Укажите пакет';
                    console.error('Не указан пакет');
                } else if (th.refLink.length == 0) {
                    th.errors = 'Вставьте реферальную ссылку';
                    console.error('Не вставлена реферальная ссылка');
                } else {
                    th.loading = true;
                    axios.put('/oss/be-partner-request/'+th.activationData.user.be_partner_request.id, {
                            package_id: th.packageSelected,
                            link: th.refLink,
                        })
                        .then((response) => {

                            if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                                th.errors = response.data.error;
                                th.wizard.bootstrapWizard('previous');
                            } else {
                                th.isConfirmed = true;
                                th.errors = '';
                            }
                            th.loading = false;
                        })
                        .catch((error) => {
                            th.errors = 'Ошибка в файле. Попробуйте еще раз.';
                            th.wizard.bootstrapWizard('previous');
                            console.error(error);
                        });
                }
            },
            sendQuittance: function () {
                let th = this,
                    formData = new FormData(),
                    file = document.querySelector('#subscription-purchase-quittance');

                th.refresh();

                if (file.files.length == 0) {
                    th.errors = 'Прикрепите квитанцию';
                    console.error('Квитацния не прикреплена');
                } else {
                    th.loading = true;
                    formData.append('image', file.files[0]);

                    axios.post('/oss/be-partner-request/'+th.activationData.user.be_partner_request.id+'/upload', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                        .then((response) => {

                            if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                                th.errors = response.data.error;
                            } else {
                                th.isQuittanceSent = true;
                                th.errors = '';
                            }
                            th.loading = false;
                        })
                        .catch((error) => {
                            th.errors = 'Ошибка в файле. Попробуйте еще раз.';
                            th.wizard.bootstrapWizard('previous');
                            console.error(error);
                        });
                }
            },
        }
    }
</script>

<style scoped>
    .bootstrapWizard li {
        width: 33%;
    }
    .text-error {
        color: #b94a48;
    }
    .form-actions {
        margin-left: -10px;
        margin-right: -10px;
    }
    .form-actions .row {

    }
</style>