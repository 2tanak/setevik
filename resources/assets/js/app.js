
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Plyr from 'vue-plyr'

window.Vue = require('vue');
Vue.use(Plyr)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//profile
Vue.component('profiles', require('./components/Profile.vue'));


// Common
Vue.component('main-menu', require('./components/navigation/Menu.vue'));
Vue.component('video-player', require('./components/media/VideoPlayer.vue'));
Vue.component('video-player-fake', require('./components/media/VideoPlayerFake.vue'));


// OSS
Vue.component('oss-login-info', require('./components/oss/LoginInfo.vue'));
Vue.component('oss-activation', require('./components/oss/Activation.vue'));
Vue.component('oss-activation-wizard', require('./components/oss/wizard/Activation.vue'));
Vue.component('oss-products', require('./components/oss/Products.vue'));
Vue.component('oss-requisitions', require('./components/oss/Requisitions.vue'));
Vue.component('oss-tree', require('./components/oss/Tree.vue'));
Vue.component('oss-attestation-detail', require('./components/oss/AttestationDetail.vue'));
Vue.component('oss-activation-partner', require('./components/oss/ActivationPartner.vue'));
Vue.component('oss-be-partner-request', require('./components/oss/BePartnerRequest.vue'));


// SIB
Vue.component('sib-login-info', require('./components/sib/LoginInfo.vue'));
Vue.component('binary-tree', require('./components/sib/BinaryTree.vue'));
Vue.component('events', require('./components/sib/Events.vue'));


// Admin
Vue.component('user', require('./components/admin/User.vue'));
Vue.component('admin-oss-wakeupera-broadcast', require('./components/admin/oss/WakeUpEraBroadcast.vue'));
Vue.component('admin-oss-wakeupera-broadcast-video', require('./components/admin/oss/WakeUpEraBroadcastVideo.vue'));
Vue.component('admin-oss-attestation', require('./components/admin/oss/Attestation.vue'));
Vue.component('documents-detail', require('./components/admin/sib/DocumentsDetail.vue'));
Vue.component('admin-events-detail', require('./components/admin/sib/EventsDetail.vue'));
Vue.component('admin-promo-detail', require('./components/admin/sib/PromoDetail.vue'));
Vue.component('admin-news-detail', require('./components/admin/sib/NewsDetail.vue'));
Vue.component('admin-oss-news-detail', require('./components/admin/oss/NewsDetail.vue'));


const app = new Vue({
    el: '#app'
});
