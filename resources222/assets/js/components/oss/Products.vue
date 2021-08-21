<template>
    <section id="widget-grid">
        <div class="row">

            <!-- #MODAL INVITATION -->
            <div id="modal-oss-products-link" class="modal fade">
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

            <!-- #PRODUCT ROWS -->
            <product v-for="item in products" :key="item.id" :product="item"></product>

        </div>
    </section>
</template>

<script>
    import Product from './Product.vue';

    export default {
        name: "Products",
        props: {
            products: Array,
            curator: Object,
        },
        data () {
            return {
                loading: false,
                errors: '',
                selectedProduct: {},
            }
        },
        methods: {
            refresh: function () {
                this.loading = false;
                this.errors = '';
                this.selectedProduct = {};
                this.curator = {};
            },
            getLink: function (product) {
                axios.get('/oss/products/'+product.id+'/link')
                    .then((response) => {
                        let m = $('#modal-oss-products-link');
                        m.find('.media-body textarea').html(response.data.link);
                        m.modal('show');
                    });
            },
        },
        components: {
            Product,
        }
    }
</script>

<style scoped>
    .modal textarea {
        width: 100%;
    }
</style>