<template>
    <div>
        <div class="oss-tree tree smart-form">
            <ul>
			
                <tree-node :node="treeData" :level="1" :d="dataTime" ></tree-node>
            </ul>
        </div>
    </div>
</template>

<script>
    import TreeNode from "./TreeNode";

    export default {
        name: "OssTree",
        props: {
            treeData: Object,
			dataTime:'',
        },
        components: {
            TreeNode
        }
		
    }
</script>