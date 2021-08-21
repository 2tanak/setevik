<template>
    <div>
        <div class="smart-timeline">
            <ul class="smart-timeline-list">
                <li v-for="item in list">
                    <div class="smart-timeline-icon bg-orange">
                        <i class="fa fa-eye"></i>
                    </div>
                    <div class="smart-timeline-content">
                        <p>
                            <a :href="'/info/events/'+item.id">
                                <strong>{{ item.title }}</strong>
                            </a>
                        </p>
                        <div v-html="item.announce"></div>
                        <a :href="'/info/events/'+item.id" class="btn btn-xs btn-default">Подробнее</a>
                    </div>
                </li>
            </ul>
        </div>
        <p v-if="hasNext" class="text-center button-more-wrapper">
            <a href="javascript:void(0)" @click="showMore" class="btn btn-sm btn-default">
                <i class="fa fa-arrow-down text-muted"></i> Загрузить больше
            </a>
        </p>
    </div>
</template>

<script>
    export default {
        name: "Events",
        props: {
            items: Object,
        },
        data () {
            return {
                currentPage: this.items.current_page,
                lastPage: this.items.last_page,
                list: this.items.data || [],
                hasNext: this.items.current_page != this.items.last_page,
            }
        },
        methods: {
            load: function () {
                let th = this;
                axios.get('/info/events/list?page='+(th.currentPage + 1))
                    .then((response) => {
                        response.data.data.forEach(function (item) {
                            th.list.push(item);
                        });

                        th.currentPage = response.data.current_page;

                        if (th.lastPage == th.currentPage) {
                            th.hasNext = false;
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    })
            },
            showMore: function () {
                this.load();
            },
        }
    }
</script>

<style scoped>
    .button-more-wrapper {
        padding-top: 20px;
    }
    .smart-timeline-list:after {
        /*left: 95px;*/
        left: 25px;
    }
    .smart-timeline-icon {
        /*left: 80px;*/
        left: 10px;
    }
    .smart-timeline-content {
        /*margin-left: 123px;*/
        margin-left: 65px;
    }
</style>