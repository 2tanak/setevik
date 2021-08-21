<template>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th v-for="col in cols">
                        {{ col.name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in rows" :key="index">
                    <td v-for="item in row" :style="'text-align:'+item.align">
                        {{ item.value }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "BaseGrid",
        props: {
            cols: Array,
            rows: Array,
        },
        data () {
            return {}
        }
    }
</script>

<style scoped>
    table tr th {
        text-align: center;
    }
    table tr td {
        text-align: center;
    }
</style>