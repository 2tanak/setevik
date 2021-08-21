<template>
    <div id="modal-oss-products-purchase" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Покупка абонемента</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="wizard-1" novalidate="novalidate">
                            <div id="bootstrap-wizard-1" class="col-sm-12">
                                <div class="form-bootstrapWizard">
                                    <ul class="bootstrapWizard form-wizard">
                                        <li class="active" data-target="#step1">
                                            <a href="#tab1" data-toggle="tab" class="hidden"></a>
                                            <span class="step">1</span>
                                            <span class="title">Информация</span>
                                        </li>
                                        <li data-target="#step2">
                                            <a href="#tab2" data-toggle="tab" class="hidden"></a>
                                            <span class="step">2</span>
                                            <span class="title">Информация о кураторе</span>
                                        </li>
                                        <li data-target="#step3">
                                            <a href="#tab3" data-toggle="tab" class="hidden"></a>
                                            <span class="step">3</span>
                                            <span class="title">Завершение</span>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <br>
                                <div class="tab-content">

                                    <!-- #TAB1-->
                                    <div class="tab-pane active" id="tab1">
                                        <br>
                                        <h5><strong>Шаг 1 </strong> - Информация</h5>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div>
                                                    <p>
                                                        Вашему куратору будет отправлена заявка на покупку абонемента.
                                                    </p>
                                                    <p>
                                                        Вам необходимо произвести оплату резидентства OSS и уведомить об этом своего Куратора,
                                                        отправив подтверждение об оплате в течение 2-х рабочих часов.</p>
                                                </div>
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="agreement" id="agreement">
                                                            <small>Я прочитал</small>
                                                            <br>
                                                            <span class="error-block"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- #TAB2-->
                                    <div class="tab-pane" id="tab2">
                                        <br>
                                        <h5><strong>Шаг 2</strong> - Информация о кураторе</h5>
                                        <div class="row">
                                            <div class="col-xs-12 text-center">
                                                <p>
                                                    <br>
                                                    {{ curatorName }} {{ curatorLastName }} <br>
                                                    <a :href="'tel:'+curatorPhone">{{ curatorPhone }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- #TAB3-->
                                    <div class="tab-pane" id="tab3">
                                        <br>
                                        <h5><strong>Шаг 3</strong> - Заявка</h5>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h1 class="text-center text-success">
                                                    <strong><i class="fa fa-check fa-lg"></i>
                                                        Заявка создана
                                                    </strong>
                                                </h1>
                                                <h4 class="text-center">
                                                    Ваша заявка отправлена куратору
                                                </h4>
                                                <br>
                                                <br>
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
                                                    <li class="finish">
                                                        <a href="javascript:void(0);" class="btn txt-color-darken pull-right">Закрыть</a>
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
        data () {
            return {
                modal: null,
                productId: null,
            }
        },
        methods: {
            init: function (productId) {
                let th = this;
                th.productId = productId;
                th.modal = $('#modal-oss-products-buy').modal();

                var validator = $("#wizard-1").validate({
                    rules: {
                        agreement: {
                            required: true,
                        },
                    },
                    messages: {
                        agreement: 'Поставьте галочку "Я прочитал"',
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

                $('#bootstrap-wizard-1').bootstrapWizard({
                    'tabClass': 'form-wizard',
                    'onNext': function (tab, navigation, index) {
                        let current = index + 1,
                            valid = $("#wizard-1").valid();

                        if (!valid) {
                            validator.focusInvalid();
                            return false;
                        } else {
                            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
                            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');

                            if (current == 3) {
                                th.createRequisition();
                            }
                        }
                    },
                    'onFinish': function () {
                        th.modal.hide();
                    },
                });
            },
            createRequisition: function () {
                //
            },
            show: function (productId) {
                this.init(productId);
                this.modal.show();
            }
        }
    }
</script>

<style scoped>
    .bootstrapWizard li {
        width: 33%;
    }
</style>