<template>
    <div class="container">
        <div v-if="error">
            {{error}}
        </div>
        <form action="#" @submit="checkForm">
            <div class="form-part">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" v-model="title">
            </div>
            <div class="form-part">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" v-model="description">
            </div>
            <div class="form-part">
                <label for="category">Category</label>
                <select name="category" id="category" v-model="category">
                    <option v-for="category in categories" :value="category.id">{{category.name}}</option>
                </select>
            </div>
            <div class="form-part">
                <label for="price">Price</label>
                <input type="number" min="1" step="any" name="price" id="price" v-model="price">
            </div>
            <div class="form-part">
                <label for="price">Image</label>
                <input type="file" name="pic" accept="image/*" id="image-upload">
            </div>
            <div class="form-part">
                <input type="submit">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                categories: [],
                category: '',
                title: '',
                price: 0,
                description: '',
                error: ''
            }
        },
        mounted() {
            this.fetchCategories()
        },
        methods: {
            fetchCategories() {
                let self = this
                axios({
                    url: 'http://localhost:8000/api/v1/categories',
                    method: 'get',
                }).then(results => {
                    self.categories = results.data || []
                })
            },
            checkForm (e) {
                e.preventDefault()
                let fields = [
                    this.category,
                    this.title,
                    this.price,
                    this.description
                ]

                let invalid = fields.filter(field => !field)

                if (invalid.length) {
                    this.error = "make sure all fields are field in"
                    // there's an error
                } else {
                    this.submitForm()
                }
            },
            submitForm () {
                axios({
                    url: 'http://localhost:8000/api/v1/products/upload',
                    method: 'post',
                    data: this.getImage()
                }).then(results => {
                    axios({
                        url: 'http://localhost:8000/api/v1/products',
                        method: 'post',
                        data: {
                            title: this.title,
                            description: this.description,
                            price: this.price,
                            category: this.category,
                            image: results.data.url
                        }
                    }).then(() => {
                        // redirect to product list
                        window.location.href = 'http://localhost:8000'
                    }).catch(err => {
                        this.error = "there was an error";
                        console.log('Error:',err)
                    })
                })
            },
            getImage () {
                let formData = new FormData();
                formData.append( 'product_image', document.querySelector( '#image-upload' ).files[0] );

                return formData;
            }
        }
    }
</script>

<style>
    body, html {
        padding: 0;
        margin: 0;
        outline: 0;
        font-family: arial;
    }

    .container {
        width: 550px;
        margin: 45px auto;
    }

    .clearfix {
        clear: both;
    }

    .form-part {
        margin: 20px 0;
    }

    .form-part label {
        font-size: 25px;
        font-weight: 400;
        font-family: 'Josefin Sans', sans-serif;
        margin-bottom: 15px;
    }

    .text-input,
    .radio-button,
    .check-boxes {
        margin-bottom: 13px;
    }

    label {
        display: block;
        margin-bottom: 3px;
        font-family: 'Josefin Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 7px;
        font-weight: 900;
    }


    input,
    select {
        padding: 10px 10px;
        display: block;
        width: 100%;
        height: 47px;
        border: solid 3px #ccc;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

</style>
