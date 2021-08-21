<template>
    <div class="login-info oss">
        <span>
            <a href="Javascript:void(0);">
		
                <img v-if="photo !='null'" :src="photo" :class="{active: has_activity_oss}">
				
			    <img v-else src="/img/avatars/no-photo.jpg" :class="{active: has_activity_oss}">

                <div class="login-info-block">
                    <b>ID </b> {{ public_id }}<br>
                </div>
                <span>
                    {{ full_name }}
                </span>
                <i class="fa fa-angle-down"></i>
            </a>
        </span>
    </div>
</template>

<script>
    export default {
        name: "LoginInfo",
        props: [
            'photo',
            'public_id',
            'full_name',
            'has_activity_oss',
        ],
    }
</script>

<style scoped>
    .login-info img {
        width: 50px;
        height: 50px;
    }
</style>