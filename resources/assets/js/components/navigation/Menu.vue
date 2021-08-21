<template>
    <nav>
        <ul>
            <menu-item v-for="item in menuData" :key="item.id" :menu-item-data="item"></menu-item>
        </ul>
    </nav>
</template>

<script>
    import MenuItem from './MenuItem.vue';

    export default {
        name: "Menu",
        props: {
            menuData: Array,
        },
        components: {
            MenuItem,
        }
    }
</script>