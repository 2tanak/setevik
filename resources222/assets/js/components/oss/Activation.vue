<template>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
            <div class="lockscreen animated flipInY">
                <div>
                    <video-player :source="activationData.video.src"></video-player>
                    <br>
                    <div>
                        <p class="text-center">
                            <b>Дорогой(-ая) {{ activationData.user.name }} {{ activationData.user.last_name }}.</b>
                        </p>
                        <p>
                            ⠀⠀Команда <b>Online Smart System (OSS)</b> поздравляет Тебя с Регистрацией на нашей Платформе,
                            единственном в своём роде проекте для онлайн-заработка и обучения,
                            созданном нашей командной профессиональных спикеров и разработчиков на собственной уникальной IT-платформе.<br>
                            ⠀⠀Мы не занимаемся плагиатом, а <b>сами создаём</b> и <b>постоянно совершенствуем</b> нашу площадку, на которой Ты сможешь найти всё,
                            чтобы начать зарабатывать реальные деньги онлайн, не выходя из дома, начиная от бизнес-инструментов и идей, заканчивая мотивацией к действию.
                        <p>
                            До момента, когда Тебе будут доступны все возможности нашей Платформы осталось всего <b>три шага</b>!<br>
                            ⠀⠀1 — Оплата, которую производишь Ты;<br>
                            ⠀⠀2 — Подтверждение, за которое отвечает Куратор;<br>
                            ⠀⠀3 — Активация, производимая Администратором системы.<br>
                            ⠀<br>
                            ⠀⠀Чем быстрее Ты совершишь первый шаг, тем раньше Тебе откроется мир больших возможностей нашей Платформы.
                        Начав пользоваться Online Smart System Ты будешь приятно удивлён тому, какую огромную ценность получишь за очень небольшие деньги. <br>
                            ⠀⠀Скорее переходи к первому Шагу и ждём Тебя в числе членов нашего мощного закрытого сообщества.
                        </p>
                        <p class="text-right">
                            <br>
                            <i>Команда Online Smart System⠀⠀</i>
                            <br>
                        </p>

                        <span>
                            {{ errors }}
                        </span>

                        <div class="text-center">
                            <button class="btn btn-default" @click="start" :disabled="loading">Начать активацию</button>
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
        name: "Activation",
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
            start: function () {
                let th = this;
                axios.post('/residents/' + th.activationData.user.id+'/wizard', {})
                    .then(function (response) {
                        if (typeof response.data.error != 'undefined' && response.data.error.length > 0) {
                            th.errors = response.data.error;
                        } else {
                            th.loading = false;
                            location.reload();
                        }
                    }).catch(function (error) {
                        console.error(error);
                    });
            }
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