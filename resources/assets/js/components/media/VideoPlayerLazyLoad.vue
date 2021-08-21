<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="video-player-wrapper">
                <video :id="elementId" :poster="poster" @click="load" @contextmenu="prevent" controls></video>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "VideoPlayerLazyLoad",
        props: {
            link: String,
            poster: String,
            watchedApi: String,
            isWatched: false,
            autoplay: Boolean,
        },
        data () {
            return {
                elementId: 'video-player-' + Math.floor(Math.random() * 100000000),
                video: null,
                loaded: false,
            }
        },
        mounted () {
            document.addEventListener('DOMContentLoaded', this.init);
        },
        methods: {
            init: function () {
                let th = this;
                th.video = $('#'+th.elementId);

                if (th.autoplay === true) {
                    th.load();
                    th.play();
                }
            },
            prevent: function (e) {
                e.stopPropagation();
                e.preventDefault();
            },
            load: function () {
                let th = this;
                if (th.loaded == false) {
                    th.video.attr('src', th.link);
                    th.video.attr('autoplay', true);
                    th.onLoad();
                }
            },
            onLoad: function () {
                this.loaded = true;
                this.watched();
            },
            watched: function () {
                let th = this;
                if (th.watchedApi) {
                    axios.get(th.watchedApi)
                        .then((response) => {
                            //
                        });
                }
            },
            play: function () {
                this.video.get(0).play();
            },
            pause: function () {
                this.video.get(0).pause();
            }
        }
    }
</script>

<style scoped>
    .video-player-wrapper {

    }
</style>