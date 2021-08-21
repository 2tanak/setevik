<template>
    <component :is="type" :user="user" :countries="countries" :statuses="statuses"></component>
</template>

<script>
    import UserSib from './UserSib.vue';
    import UserOss from './UserOss.vue';

    export default {
        name: "User",
        props: {
            user: Object,
            countries: Array,
            statuses: Array,
        },
        data () {
            return {
                type: this.user.cabinet.code == 'sib' ? UserSib : UserOss,
            }
        },
        components: {
            UserSib,
            UserOss,
        },
    }
</script>