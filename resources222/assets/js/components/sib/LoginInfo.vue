<template>
    <div class="login-info">
        <span>
            <a href="Javascript:void(0);">
                <div class="login-img-wrapper" :class="{active: is_qualified}">
				 <img v-if="photo !='null'" :src="photo" :class="{active: has_activity_sib}">
				 
				 <img v-else src="/img/avatars/no-photo.jpg" :class="{active: has_activity_sib}">
				
                </div>
                <div class="login-info-block">
                    {{ package_name }}<br>
                    <b>ID </b> {{ public_id }}<br>
                    <b v-if="status_name" rel="tooltip"
                       data-placement="right"
                       data-original-title="<i class='text-success'></i> Официальный представитель компании"
                       data-html="true">
                        {{ status_name }}
                    </b>
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
            'package_name',
            'status_name',
            'public_id',
            'full_name',
            'has_activity_sib',
            'is_qualified',
        ],
    }
</script>

<style scoped>
    .login-info img {
        
    }
</style>