<template>
  <div class="row">
    <div class="col-xs-12">
      <div class="video-player-wrapper">
        <vue-plyr :data-poster="poster" @play.native.once="load">
          <video :id="`video-player-${elementId}`"></video>
        </vue-plyr>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "VideoPlayer",
  props: {
    link: String,
    publicLink: String,
    source: String,
    poster: String,
    autoplay: Boolean,
    watchedApi: String,
  },
  data: () => ({
    elementId: Math.floor(Math.random() * 100000000),
    video: null,
    loaded: false,
  }),
  methods: {
    init() {
      this.video = $("#video-player-" + this.elementId);

      if (this.source) {
        this.loaded = true;
        this.video.attr("src", this.source);
      }
    },
    load() {
      if (this.publicLink) {
        this.loaded = true;
        this.video.attr("src", this.publicLink);
      } else if (this.link) {
        axios(this.link).then(({ data }) => {
            this.loaded = true;
            this.video.attr("src", data);
            this.watched();
          }).catch((error) => console.error(error));
      }
    },

    watched() {
      axios.get(this.watchedApi);
    },
  },
  mounted() {
    document.addEventListener("DOMContentLoaded", this.init);
  },
};
</script>

<style lang="scss">
@import '~vue-plyr/dist/vue-plyr.css';

html {
  --plyr-color-main: #cc8960;
}

.plyr--full-ui.plyr--video .plyr__control--overlaid {
  z-index: 999;
}
</style>
