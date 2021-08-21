<template>
    <div class="col-sm-12 col-md-12 col-lg-12">

        <!-- #MODAL PRICE -->
        <div class="modal fade" id="modal-price">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">
                            Прайс-лист <small><i>(можно скачать по кнопке внизу документа)</i></small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="text-center">
                                <img src="/storage/photos/price.jpg" class="img img-responsive" alt="price">
                            </div>
                            <hr/>
                            <div class="text-center">
                                <a href="/storage/photos/price.jpg" class="btn btn-primary" download target="_parent">
                                    Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- #MODAL SUBSCRIPTION PROLONGATION WIZARD -->
        <prolongation :product="product" :curator="this.$parent.curator"></prolongation>

        <!-- #MODAL SUBSCRIPTION PURCHASE WIZARD -->
        <purchase :product="product" :curator="this.$parent.curator"></purchase>

        <!-- #PRODUCT DETAIL -->
        <div class="product-content product-wrap clearfix">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="product-image">
                        <img :src="product.photo" alt="product" class="img-responsive">
                        <span v-if="product.discount > 0" class="tag2 hot">-{{ product.discount }}%</span>

                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="product-deatil">
                        <h5 class="name">
                            <h2>
                                <b>{{ product.name }}</b>
                            </h2>
                            <i v-if="product.subscriptions.length">
                                        Срок истечения абонемента:
                                    {{ product.subscriptions[product.subscriptions.length -1].expired_at }}
                            </i>
                            <i v-else class="text-red">Абонемент отсутствует</i>
                        </h5>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <p class="price-container">
                                    <template v-if="product.discount > 0">
                                <span>
								
                                    ${{ product.price - ((product.price * product.discount) / 100) }}<sup style="color:lightgrey"><b>*</b></sup>
                                </span>
                                        <del style="color:grey; display:block">
                                            ${{ product.price }}
                                        </del>
                                    </template>
                                    <template v-else>
                                <span>
                                    ${{ product.price }}<sup style="color:lightgrey"><b>*</b></sup>
                                </span>
                                    </template>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="certified text-center">
                                    <ul>
                                        <template v-if="product.subscriptions.length">
                                            <li>
                                                <a href="Javascript:void(0);" @click="showModalSubscriptionProlongation">
                                                    Абонемент<span>Продлить</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="Javascript:void(0);" @click="getLink(product.id)"
                                                   rel="tooltip"
                                                   data-placement="top"
                                                   data-original-title="<i class='text-success'></i> Получить ссылку"
                                                   data-html="true">
                                                    Приглашение<span>Ссылка</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li>
                                                <a href="Javascript:void(0);" @click="showModalSubscriptionPurchase">
                                                    Абонемент<span>Купить</span>
                                                </a>
                                            </li>
                                            <li>
                                                <template v-if="product.has_link">
                                                    <a href="Javascript:void(0);" @click="getLink(product.id)"
                                                       rel="tooltip"
                                                       data-placement="top"
                                                       data-original-title="<i class='text-success'></i> Получить ссылку"
                                                       data-html="true">
                                                        Приглашение<span>Ссылка</span>
                                                    </a>
                                                </template>
                                                <template v-else>
                                                    <a href="Javascript:void(0);"
                                                       rel="tooltip"
                                                       data-placement="top"
                                                       data-original-title="<i class='text-success'></i>Cрок действия абонемента истёк"
                                                       data-html="true">
                                                        Приглашение<del><span>Ссылка</span></del>
                                                    </a>
                                                </template>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <span class="tag1"></span>
                    </div>
                    <div class="description">
                        <p v-html="product.description"></p>
                        <small style="color:grey">
                            <i>
                                * Актуальные цены в местной валюте с учетом партнёрских скидок можно узнать в документе <a href="#modal-price" data-toggle="modal">Прайс-лист</a>.
                            </i>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Prolongation from './wizard/SubscriptionProlongation.vue';
    import Purchase from './wizard/SubscriptionPurchase.vue';

    export default {
        name: "Product",
        props: {
            product: Object,
        },
        data () {
            return {
                errors: '',
                loading: false,
            }
        },
        methods: {
            getLink: function () {
                this.$parent.getLink(this.product);
            },
            showModalSubscriptionProlongation: function () {
                this.$children[0].show();
            },
            showModalSubscriptionPurchase: function () {
                this.$children[1].show();
            },
        },
        components: {
            Prolongation,
            Purchase,
        }
    }
</script>

<style scoped>
    .modal textarea {
        width: 100%;
    }
    .price-container {
        padding-top: 10px;
    }
    .product-content h2 {
        margin: 7px 0;
        font-size: 30px;
    }
    .product-deatil .name h2 {
        font-size: 22px;
    }
    .product-deatil .name span {
        color: #000;
    }
    .product-content .product-deatil p.price-container span {
        font-size: 35px;
    }
    .product-content .product-deatil p.price-container del {
        font-size: 20px;
    }
    .bootstrapWizard li {
        width: 33%;
    }
    .tab-pane p {
        text-align: justify;
    }
    h5 i {
        font-size: 15px;
    }
</style>