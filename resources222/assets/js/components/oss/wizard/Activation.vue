<template>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 lock-wrapper">
            <div class="well animated flipInY">

                <br>
                <video-player :source="activationData.video.src"></video-player>
                <br>

                <template v-if="step == 1">
                    <step-1 :activation-data="activationData"></step-1>
                </template>

                <template v-else-if="step == 2">
                    <step-2 :activation-data="activationData"></step-2>
                </template>

                <template v-else-if="step == 3">
                    <step-3 :activation-data="activationData"></step-3>
                </template>

            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from './../../media/VideoPlayer.vue';

    import Step1 from './ActivationStep1.vue';
    import Step2 from './ActivationStep2.vue';
    import Step3 from './ActivationStep3.vue';

    export default {
        name: "Activation",
        props: {
            activationData: Object,
        },
        data () {
            return {
                step: 0
            }
        },
        mounted() {
            if (this.activationData.requisition) {
                if (this.activationData.requisition.curator_quittance_id > 0) {
                    this.step = 3;
                } else {
                    this.step = 2;
                }
            } else {
                this.step = 1;
            }
        },
        components: {
            VideoPlayer,
            Step1,
            Step2,
            Step3
        },
    }
</script>