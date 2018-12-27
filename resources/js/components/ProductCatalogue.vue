<template>
    <div>
        <div class="toolbar">
            <label>Select Category</label>
            <select name="categories" id="categories" @change="filterProducts" v-model="filterBy">
                <option value="*" selected>All</option>
                <option v-for="category in categories" :value="category.id">{{category.name}}</option>
            </select>
        </div>
        <div class="products">
            <div class="card"  v-for="(product, index) in filteredProducts">
                <img :src="product.image" :alt="product.name" style="width:100%">
                <div class="details">
                    <h2>{{product.title}}</h2>
                    <p class="price">R{{product.price}}</p>
                    <p>{{product.description}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                filterBy: '*',
                products: [],
                categories: []
            }
        },
        mounted() {
            this.fetchProducts()
            this.fetchCategories()
        },
        computed: {
            filteredProducts () {
                if (this.filterBy === '*') {
                    return this.products
                } else {
                    return this.products.filter(({category}) => category == this.filterBy)
                }
            }
        },
        methods: {
            fetchProducts() {
                let self = this
                return axios({
                    url: 'http://localhost:8000/api/v1/products',
                    method: 'get',
                }).then(results => {
                    console.log('results', results)
                    self.products = results.data || []
                })
            },
            fetchCategories() {
                let self = this
                axios({
                    url: 'http://localhost:8000/api/v1/categories',
                    method: 'get',
                }).then(results => {
                    self.categories = results.data || []
                })
            },
            filterProducts(event) {
                this.fetchProducts().then(() => this.filterBy = event.target.value)
            }
        }
    }
</script>

<style>
    body, html {
        padding: 0;
        margin: 0;
        font-family: arial;
    }
    .products {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        padding-top: 100px;
    }
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        width: 240px;
        margin: auto;
        text-align: center;
        font-family: arial;
        margin: 20px;
    }
    .card .details {
        padding: 8px 8px 20px 8px
    }
    .card .details * {
        padding: 4px;
        margin: 0;
    }
    .card .details .price {
        color: grey;
        font-size: 22px;
        padding: 10px 0;
    }
    .toolbar {
        display: flex;
        background: #EEE;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        padding: 20px;
        position:fixed;
        width: calc(100% - 40px);
    }
    .toolbar label {
        font-size: 30px;
        width: 300px;
    }
    .toolbar select {
        height: 40px;
        width: 100%;
    }
</style>
