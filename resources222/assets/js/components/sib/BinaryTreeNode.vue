<template>
    <div>
        <div :id="'myModalBox-'+user_id" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Информация о пользователе</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="media-body text-left">
                                <b>Ф.И.О.:</b> {{ full_name }}<br>
                                <b>ID:</b> {{ user_public_id }}<br>
                                <b>Куратор:</b> {{ curator_fullname }}<br>
                                <b>Пакет:</b> {{ package_name }}<br>
                                <b>Дата регистрации:</b> {{ activated_at }}<br>
                                <!--<b>Статус:</b> {{ status_name }}<br>-->
                                <b>Квалификация:</b>  {{ qualification }}<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item" :class="{active: has_activity_sib, 'item-disabled': !is_active}">
            <a :href="'/me-and-my-team/'+root_node_id" rel="tooltip" data-placement="auto" data-original-title="<i class=\'text-success\'></i> Посмотреть бинар" data-html="true">
                <div class="img-wrapper" :class="{active: is_qualified}">
			        <img v-if="avatar != 'null'" :src="avatar">
					<img v-else src="/img/avatars/no-photo.jpg">
                </div>

                
                <br>
                {{ full_name }}
            </a>
			
            <br>
            <a href="Javascript:void(0);" @click="info(user_id)" rel="tooltip" data-placement="auto" data-original-title="<i class=\'text-success\'></i> Посмотреть информацию о пользователе" data-html="true">
                {{ package_name }}
            </a>
            <!--&lt;!&ndash; #DEBUG &ndash;&gt;-->
            <!--<br>-->
            <!--<small>id: <b>{{ id }}</b>, parent_id: <b>{{ parent_id }}</b></small>-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "BinaryTreeNode",
        props: [
            'id',
            'parent_id',
            'user_id',
            'root_node_id',
            'has_activity_sib',
            'is_active',
            'is_qualified',
            'avatar',
            'full_name',
            'package_name',
            'packs_left',
            'packs_right',

            'user_public_id',
            'curator_fullname',
            'created_at',
            'activated_at',
            'status_name',
        ],
        data () {
            return {
                qualification: this.is_qualified ? 'Есть' : 'Нет',
            }
        },
        methods: {
            info: function (user_id) {
                $('#myModalBox-'+user_id).modal('show');
            }
        }
    }
</script>

<style scoped>
    small {
        background-color: darkseagreen;
        font-size: 0.8em;
    }
</style>