<template>
    <div class="row">
        <form id="wizard-1" novalidate="novalidate">
            <div id="bootstrap-wizard-1" class="col-sm-12">
                <div class="form-bootstrapWizard">
                    <ul class="bootstrapWizard form-wizard">
                        <li data-target="#step1" class="complete">
                            <a href="#tab1" data-toggle="tab" class="hidden"></a>
                            <span class="step">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="title">
                                Оплата <br>
                                (резидент-новичок)
                            </span>
                        </li>
                        <li class="active" data-target="#step2">
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
                    <div class="tab-pane" id="tab1">
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
                                </div>
                                <h3 class="text-center text-success">
                                    <strong><i class="fa fa-check fa-lg"></i> Квитанция отправлена</strong>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- #TAB2-->
                    <div class="tab-pane active" id="tab2">
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
                                    <li class="previous">
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
        name: "ActivationStep2",
        props: {
            activationData: Object,
        },
        mounted() {
            console.info('wizard:step 2');
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
                        quittance: 'Прикрепите квитанцию перевода об оплате',
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
                $('#bootstrap-wizard-1').find('li.next').hide();

                th.wizard = $('#bootstrap-wizard-1').bootstrapWizard({
                    'tabClass': 'form-wizard',
                    'onNext': function (tab, navigation, index) {
                        if ($("#wizard-1").valid() == false) {
                            validator.focusInvalid();
                            return false;
                        } else {

                            if (index == 1) {
                                $('#bootstrap-wizard-1').find('li.next').hide();
                            }

                            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
                            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
                        }
                    },
                    'onPrevious': function (tab, navigation, index) {
                        $('#bootstrap-wizard-1').find('li.next').show();
                    },
                });
            },
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