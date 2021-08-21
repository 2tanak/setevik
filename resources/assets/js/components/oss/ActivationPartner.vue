<template>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
            <div class="lockscreen animated flipInY">
                <div>
                    <div class="padding-20">
                        <img src="/img/SIB.png" class="img-responsive" alt="log">
                    </div>
                    <br>
                    <div>
                        <p class="text-center">
                            <b>Дорогой(-ая) {{ activationData.user.name }} {{ activationData.user.last_name }}.</b>
                        </p>
                        <p>
                            ⠀⠀Нажимая кнопку «Начать регистрацию», распложенную ниже, Ты принимаешь одно из Важнейших решений в Твоей жизни — стать Партнёром компании
                            <b>Smart International Business</b>.<br>
                            ⠀⠀Сказать, что после начала обучения на курсе <b>«Я — Личность»</b> Твоя жизнь уже никогда не будет прежней — это ничего не сказать!
                            Можно прочитать все отзывы об обучении в Школе, коих уже тысячи. Можно видеть, как Школьники, то и дело, на эфирах и в чатах пишут о том,
                            что Школа это лучшее решение в их жизни, разделившее её на ДО и ПОСЛЕ. Можно много раз слышать от Партнёров информацию о том,
                            что в Школе даётся в 10 раз больше полезности, по сравнению с другими образовательными проектами SIB. Но!!!
                            Понять и подтвердить всю искренность, истинность и значимость этих слов Ты сможешь лишь тогда, когда на себе опробуешь,
                            что значит <b>учиться в Международной школе жизни и бизнеса «ERA»</b> и быть полноправным <b>Партнёром компании Smart International Business</b>.
                        </p>
                        <p>
                            Для того, чтобы завершить процесс регистрации в партнёрстве SIB нужно сделать всего <b>три шага</b>!<br>
                            ⠀⠀1 — Выбор пакета и его Оплата, которые производишь Ты; <br>
                            ⠀⠀2 — Подтверждение пакета и Регистрация в системе SIB по реферальной ссылке, которые Ты производишь в связке с Куратором; <br>
                            ⠀⠀3 — Активация Твоего Партнёрства, производимая Администратором системы.
                        </p>
                        <p>
                            ⠀⠀Чем быстрее Ты совершишь эти шаги, тем раньше Тебе полностью откроется мир больших финансовых и личностных возможностей нашего Проекта, где <b>Ты вполне можешь стать долларовым миллионером или мультимиллионером</b>. Но что точно случится — Ты станешь более счастливым, радостным и успешным человеком.
                            <b>⠀⠀Скорее определяйся и жду Тебя в команде!</b>
                        </p>
                        <p><i>⠀⠀P.S.: нажав кнопку «Начать регистрацию» Ты уже никогда не сможешь вернуться на эту страницу.</i></p>
                        <p class="text-right">
                            <i>С уважением и наилучшими пожеланиями, <br>
                                СЕО Компании Smart International Business <br>
                                Ерлан Ахметов</i>
                        </p>

                        <span>
                            {{ errors }}
                        </span>

                        <div class="text-center">
                            <button class="btn btn-primary" @click="next" :disabled="loading">Начать регистрацию</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from './../media/VideoPlayer.vue';

    export default {
        name: "ActivationPartner",
        props: {
            activationData: Object,
        },
        data () {
            return {
                loading: false,
                errors: '',
            }
        },
        methods: {
            next: function () {
                let th = this;
                axios.post('/oss/be-partner-request', {})
                    .then(function (response) {
                        if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                            th.errors = response.data.error;
                        } else {
                            th.loading = false;
                            location.reload();
                        }
                    })
                    .catch(function (error) {
                        th.loading = false;
                        console.error(error);
                    });
            },
        },
        components: {
            VideoPlayer,
        }
    }
</script>

<style scoped>
    .lock-wrapper p {
        text-align: justify;
    }
</style>