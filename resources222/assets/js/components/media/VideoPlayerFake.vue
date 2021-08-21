<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="video-player-wrapper">
                <video :poster="poster" @click="redirect" @contextmenu="prevent" controls></video>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "VideoPlayerFake",
        props: {
            link: String,
            poster: String,
        },
        methods: {
            prevent: function (e) {
                e.stopPropagation();
                e.preventDefault();
            },
            redirect: function () {
                window.location.href = this.link;
            }
        }
    }
</script>

<style scoped>
    .video-player-wrapper {

    }
</style>