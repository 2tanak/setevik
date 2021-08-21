<template>
    <li :class="classObject">

        <template v-if="typeof menuItemData.children != 'undefined'">
            <a href="#" :title="menuItemData.name" :class="classObject">
                <i :class="menuItemData.icon"></i>
                <span class="menu-item-parent">{{ menuItemData.name }}</span>
            </a>
            <ul>
                <menu-item v-for="item in menuItemData.children" :key="item.id" :menu-item-data="item"></menu-item>
            </ul>
        </template>

        <template v-else>
            <a :href="menuItemData.length > 0 ? '#' : menuItemData.link" :title="menuItemData.name" :class="classObject">
                <i :class="menuItemData.icon"></i>
                <span class="menu-item-parent">{{ menuItemData.name }}</span>
            </a>
        </template>


    </li>
</template>

<script>
    export default {
        name: "MenuItem",
        props: {
            menuItemData: Object,
        },
        data () {
            return {
                classObject: {
                    //'text-primary': this.menuItemData.is_active,
                    'active': typeof this.menuItemData.is_active != 'undefined'
                }
            }
        },
        mounted() {
            console.info(this.menuItemData)
        }
    }
</script>

<style scoped>

</style>