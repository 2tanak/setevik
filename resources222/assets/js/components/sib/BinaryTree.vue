<template>
    <div class="table-responsive">

        <!-- #MODAL -->
        <div id="modalRefLink" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Передайте эту ссылку приглашённому</h4>
                    </div>
                    <div class="modal-body">
                        <div style="overflow: scroll">
                            <div class="media-body">
                                <textarea cols="30" rows="5"></textarea>
                            </div>
                            <div class="text-center">
                                <br>
                                <button class="btn btn-primary btn-copy">Копировать в буфер обмена</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="tree.prev_node_id">
            <a class="btn btn-success" :href="'/me-and-my-team/'+tree.prev_node_id">Вверх по бинару</a>
            <a class="btn btn-success" href="javascript:history.back()">Назад</a>
        </template>

        <div class="bin-tree">
            <ul id="binary-tree">
                <li>
                    <!-- #ROOT NODE -->
                    <binary-tree-node
                            :key="tree.node.id"
                            :id="tree.node.id"
                            :parent_id="tree.node.parent_id"
                            :team_id="tree.node.team_id"
                            :user_id="tree.node.user_id"
                            :root_node_id="tree.node.root_node_id"
                            :has_activity_sib="tree.node.has_activity_sib"
                            :is_active="tree.node.is_active"
                            :is_qualified="tree.node.is_qualified"
                            :avatar="tree.node.avatar"
                            :full_name="tree.node.full_name"
                            :package_name="tree.node.package_name"
                            :user_public_id="tree.node.user_public_id"
                            :curator_fullname="tree.node.curator_fullname"
                            :created_at="tree.node.created_at"
                            :activated_at="tree.node.activated_at"
                            :status_name="tree.node.status_name">
                    </binary-tree-node>
					
                    <div class="info">
                        <div class="info-left">
                            Левый отдел продаж<br>
                            {{ tree.node.packs_left }} packs ({{ tree.node.pts_left }} / <span class="text-red">{{ tree.node.pts_missed_left }}</span> pts)

                        </div>
                        <div class="info-right">
                            Правый отдел продаж<br>
                            {{ tree.node.packs_right }} packs ({{ tree.node.pts_right }} / <span class="text-red">{{ tree.node.pts_missed_right }}</span> pts)
                        </div>
                    </div>

                    <!-- #LEVEL 1 -->
                    <ul>
                        <li v-for="child_level_1 in tree.node.children">
                            <template v-if="child_level_1.is_blocked">
                                <div class="item">
                                    <a href="javascript:void(0)" class="lock"><i class="fa fa-lock"></i></a>
                                </div>
                            </template>
                            <template v-else-if="!child_level_1.is_blocked && child_level_1.is_free">
                                <div class="item">
                                    <a href="Javascript:void(0);" @click="getLink(child_level_1.id)" class="plus"> + </a>
                                </div>
                            </template>
                            <template v-else>
                                <binary-tree-node
                                        :key="child_level_1.id"
                                        :id="child_level_1.id"
                                        :parent_id="child_level_1.parent_id"
                                        :team_id="child_level_1.team_id"
                                        :user_id="child_level_1.user_id"
                                        :root_node_id="child_level_1.root_node_id"
                                        :has_activity_sib="child_level_1.has_activity_sib"
                                        :is_active="child_level_1.is_active"
                                        :is_qualified="child_level_1.is_qualified"
                                        :avatar="child_level_1.avatar"
                                        :full_name="child_level_1.full_name"
                                        :package_name="child_level_1.package_name"
                                        :user_public_id="child_level_1.user_public_id"
                                        :curator_fullname="child_level_1.curator_fullname"
                                        :created_at="child_level_1.created_at"
                                        :activated_at="child_level_1.activated_at"
                                        :status_name="child_level_1.status_name">
                                </binary-tree-node>
                                <template v-if="child_level_1.root_node_id == tree.current_node_id">
                                    <div class="info">
                                        <div class="info-left">
                                            <br>
                                            {{ child_level_1.pts_left }} / <span class="text-red">{{ child_level_1.pts_missed_left }}</span> pts
                                        </div>
                                        <div class="info-right">
                                            <br>
                                            {{ child_level_1.pts_right }} / <span class="text-red">{{ child_level_1.pts_missed_right }}</span> pts
                                        </div>
                                    </div>
                                </template>
                            </template>

                            <!-- #LEVEL 2 -->
                            <ul>
                                <li v-for="child_level_2 in child_level_1.children">
                                    <template v-if="child_level_2.is_blocked">
                                        <div class="item">
                                            <a href="javascript:void(0)" class="lock"><i class="fa fa-lock"></i></a>

                                            <!--&lt;!&ndash; #DEBUG &ndash;&gt;-->
                                            <!--<br>-->
                                            <!--<small>id: <b>{{ child_level_2.id }}</b>, parent_id: <b>{{ child_level_2.parent_id }}</b></small>-->
                                        </div>
                                    </template>
                                    <template v-else-if="!child_level_2.is_blocked && child_level_2.is_free">
                                        <div class="item">
                                            <template v-if="child_level_1.root_node_id == tree.current_node_id">
                                                <a href="Javascript:void(0);" @click="getLink(child_level_2.id)" class="plus"> + </a>
                                            </template>
                                            <template v-else>
                                                <template v-if="!child_level_2.is_blocked_global && child_level_2.is_free_global">
                                                    <a href="Javascript:void(0);" @click="getLink(child_level_2.id)" class="plus"> + </a>
                                                </template>
                                                <template v-else>
                                                    <a href="javascript:void(0)" class="lock"><i class="fa fa-lock"></i></a>
                                                </template>
                                            </template>

                                            <!--&lt;!&ndash; #DEBUG &ndash;&gt;-->
                                            <!--<br>-->
                                            <!--<small>id: <b>{{ child_level_2.id }}</b>, parent_id: <b>{{ child_level_2.parent_id }}</b></small>-->
                                        </div>
                                    </template>
                                    <template v-else>
                                        <binary-tree-node
                                                :key="child_level_2.id"
                                                :id="child_level_2.id"
                                                :parent_id="child_level_2.parent_id"
                                                :team_id="child_level_2.team_id"
                                                :user_id="child_level_2.user_id"
                                                :root_node_id="child_level_2.root_node_id"
                                                :has_activity_sib="child_level_2.has_activity_sib"
                                                :is_active="child_level_2.is_active"
                                                :is_qualified="child_level_2.is_qualified"
                                                :avatar="child_level_2.avatar"
                                                :full_name="child_level_2.full_name"
                                                :package_name="child_level_2.package_name"
                                                :user_public_id="child_level_2.user_public_id"
                                                :curator_fullname="child_level_2.curator_fullname"
                                                :created_at="child_level_2.created_at"
                                                :activated_at="child_level_2.activated_at"
                                                :status_name="child_level_2.status_name">
                                        </binary-tree-node>
                                    </template>

                                    <!-- #LEVEL 3 -->
                                    <ul>
                                        <li v-for="child_level_3 in child_level_2.children">
                                            <template v-if="child_level_3.is_blocked">
                                                <div class="item">
                                                    <a href="javascript:void(0)" class="lock"><i class="fa fa-lock"></i></a>

                                                    <!--&lt;!&ndash; #DEBUG &ndash;&gt;-->
                                                    <!--<br>-->
                                                    <!--<small>id: <b>{{ child_level_3.id }}</b>, parent_id: <b>{{ child_level_3.parent_id }}</b></small>-->
                                                </div>
                                            </template>
                                            <template v-else-if="!child_level_3.is_blocked && child_level_3.is_free">
                                                <div class="item">
                                                    <a href="Javascript:void(0);" @click="getLink(child_level_3.id)" class="plus"> + </a>

                                                    <!--&lt;!&ndash; #DEBUG &ndash;&gt;-->
                                                    <!--<br>-->
                                                    <!--<small>id: <b>{{ child_level_3.id }}</b>, parent_id: <b>{{ child_level_3.parent_id }}</b></small>-->
                                                </div>
                                            </template>
                                            <template v-else>
                                                <binary-tree-node
                                                        :key="child_level_3.id"
                                                        :id="child_level_3.id"
                                                        :parent_id="child_level_3.parent_id"
                                                        :team_id="child_level_3.team_id"
                                                        :user_id="child_level_3.user_id"
                                                        :root_node_id="child_level_3.root_node_id"
                                                        :has_activity_sib="child_level_3.has_activity_sib"
                                                        :is_active="child_level_3.is_active"
                                                        :is_qualified="child_level_3.is_qualified"
                                                        :avatar="child_level_3.avatar"
                                                        :full_name="child_level_3.full_name"
                                                        :package_name="child_level_3.package_name"
                                                        :user_public_id="child_level_3.user_public_id"
                                                        :curator_fullname="child_level_3.curator_fullname"
                                                        :created_at="child_level_3.created_at"
                                                        :activated_at="child_level_3.activated_at"
                                                        :status_name="child_level_3.status_name">
                                                </binary-tree-node>
                                            </template>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- #LAST CHILDREN -->
            <ul class="last-items">
                <li>
                    <template v-if="tree.last_child_left.id">
                        <div class="pull-left text-left" style="width: 50%; padding-left: 30px">
                            <hr style="margin-right: 15px;">
                            <p>Нижний левый участник</p>
                            <binary-tree-node
                                    :key="tree.last_child_left.id"
                                    :id="tree.last_child_left.id"
                                    :parent_id="tree.last_child_left.parent_id"
                                    :team_id="tree.last_child_left.team_id"
                                    :user_id="tree.last_child_left.user_id"
                                    :root_node_id="tree.last_child_left.root_node_id"
                                    :has_activity_sib="tree.last_child_left.has_activity_sib"
                                    :is_active="tree.last_child_left.is_active"
                                    :is_qualified="tree.last_child_left.is_qualified"
                                    :avatar="tree.last_child_left.avatar"
                                    :full_name="tree.last_child_left.full_name"
                                    :package_name="tree.last_child_left.package_name"
                                    :user_public_id="tree.last_child_left.user_public_id"
                                    :curator_fullname="tree.last_child_left.curator_fullname"
                                    :created_at="tree.last_child_left.created_at"
                                    :activated_at="tree.last_child_left.activated_at"
                                    :status_name="tree.last_child_left.status_name">
                            </binary-tree-node>
                        </div>
                    </template>
                    <template v-if="tree.last_child_right.id">
                        <div class="pull-right text-right" style="width: 50%; padding-right: 40px">
                            <hr style="margin-left: 15px;">
                            <p>Нижний правый участник</p>
                            <binary-tree-node
                                    :key="tree.last_child_right.id"
                                    :id="tree.last_child_right.id"
                                    :parent_id="tree.last_child_right.parent_id"
                                    :team_id="tree.last_child_right.team_id"
                                    :user_id="tree.last_child_right.user_id"
                                    :root_node_id="tree.last_child_right.root_node_id"
                                    :has_activity_sib="tree.last_child_right.has_activity_sib"
                                    :is_active="tree.last_child_right.is_active"
                                    :is_qualified="tree.last_child_right.is_qualified"
                                    :avatar="tree.last_child_right.avatar"
                                    :full_name="tree.last_child_right.full_name"
                                    :package_name="tree.last_child_right.package_name"
                                    :user_public_id="tree.last_child_right.user_public_id"
                                    :curator_fullname="tree.last_child_right.curator_fullname"
                                    :created_at="tree.last_child_right.created_at"
                                    :activated_at="tree.last_child_right.activated_at"
                                    :status_name="tree.last_child_right.status_name">
                            </binary-tree-node>
                        </div>
                    </template>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import BinaryTreeNode from './BinaryTreeNode';

    export default {
        name: "BinaryTree",
        props: ['tree'],
        methods: {
            getLink: function (nodeId) {
                axios.get('/me-and-my-team/links/'+nodeId)
                    .then((response) => {
                        var m = $('#modalRefLink');
                        m.find('.media-body textarea').html(response.data.link);
                        m.modal('show');
                    });
            },
        },
        components: {
            BinaryTreeNode
        },
    }
</script>

<style>
    textarea {
        width: 100%;
        color: black;
    }
    .alert {
        margin-bottom: 1px;
        text-align: left;
    }
    small {
        background-color: darkseagreen;
        font-size: 0.8em;
    }
    .last-items img {
        width: 44px;
        height: 44px;
    }
</style>