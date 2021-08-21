<template>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">
            <video-player
                    :public-link="video.public_link"
                    :poster="poster"
                    :watched-api="watchedApi"
                    :autoplay="true">
            </video-player>

            <div class="pull-left">
                <h4>
                    <b>Тема эфира:</b> <i>{{ video.title }}</i><br>
                    <b>Описание:</b> <i>{{ video.description }}</i><br>
                    <b>Спикер(ы):</b> <i>{{ video.speaker }}</i><br>
                </h4>
            </div>
            <div class="clb"></div>
            <hr>
            <div v-if="!isConfirmed" class="form-inline">
                <fieldset>
                    <div class="checkbox">
                        <label class="text-center">
                            <input type="checkbox" v-model="isChecked" class="checkbox">
                            <span> Я полностью просмотрел(-а) запись обучения и подтверждаю, что усвоил(-а) весь объём информации и выполнил(-а) все рекомендации, данные в видео.</span>
                        </label>
                    </div>
                </fieldset>
                <br>
                <fieldset class="text-center">
                    <button type="submit" class="btn btn-success" @click="confirm" :disabled="!isChecked">Подтверждаю</button>
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from './../media/VideoPlayer.vue';

    export default {
        name: "AttestationDetail",
        props: {
            video: Object,
            poster: String,
            watchedApi: String,
        },
        data () {
            return {
                isConfirmed: false,
                isChecked: false,
            }
        },
        mounted() {
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this;
                if (th.video.isConfirmed) {
                    th.isConfirmed = true;
                }
            },
            confirm: function () {
                let th = this;
                if (th.isChecked) {
                    th.isConfirmed = true;

                    axios.get('/oss/attestation/'+th.video.id+'/confirmed')
                        .then((response) => {
                            if (response.status == 200) {
                                th.notify();
                            }
                        });
                } else {
                    alert('Check it!');
                }
            },
            notify: function () {
                $.smallBox({
                    title : 'Подтверждение принято!',
                    content : "<i class='fa fa-clock-o'></i> <i>Автозакрытие через 2 секунды...</i>",
                    color : "rgb(115, 158, 115)",
                    //iconSmall : "fa fa-thumbs-up bounce animated",
                    timeout : 4000
                });
            },
        },
        components: {
            VideoPlayer,
        }
    }
</script>

<style scoped>

</style>