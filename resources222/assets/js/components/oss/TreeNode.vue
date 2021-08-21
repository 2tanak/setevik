<template>
    <li v-show="isExpanded">
        <span>
            <i :class="classObject"></i>
            <template v-if="node.user.cabinet_id == 2">
                <img v-show="node.user.is_oss_ever" :class="{active: active_subscriptions(node)}" src="/img/avatars/oss-cut.png" alt="oss">
                <img :class="{active: node.user.has_activity_sib}" src="/img/avatars/sib.png" alt="sib">
            </template>
            <template v-else>
                <img :class="{active: active_subscriptions(node)}" src="/img/avatars/oss-cut.png" alt="oss">
            </template>
            {{ node.user.name }} {{ node.user.last_name }}
            <span class="badge inbox-badge">
                {{ node.invited_cnt }} / {{ node.total_cnt }}
            </span>
        </span>
        <ul v-if="node.children && node.children.length">
            <tree-node v-for="child in node.children" :key="child.id" :node="child" :level="level + 1" :d="d"></tree-node>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "TreeNode",
        props: {
            node: Object,
            level: Number,
			d:'',
        },
        data () {
            return {
                isExpanded: this.level < 3,
                classObject: {
                    'fa fa-lg fa-plus-circle':  this.level > 1 && this.node.total_cnt > 0,
                    'fa fa-lg fa-minus-circle': this.level == 1 && this.node.total_cnt > 0,
                    'icon-leaf':                this.node.total_cnt == 0,
                }
            }
        },
		methods: {
			 
            active_subscriptions: function (node) {
				let th =this;
				let expired = node.user.subscriptions.find(c => th.d < c.expired_at);
				if(expired){
				if(expired.id){return true;}else{return false;}
				}else{return false;}
                 
             
            }
		  }
    }
</script>

<style scoped>
    img {
        border-radius: 50%;
        border: 1px solid red;
    }
    img.active {
        border: 1px solid green;
    }
</style>