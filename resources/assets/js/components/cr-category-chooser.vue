<template>
    <div class="box box-primary" id="postCategories">
        <div class="box-header">
            {{ heading || 'Categories' }}
        </div>


        <div class="box-body">
            <div id="category-checkboxes">
                <div class="checkbox" v-for="category in categories">
                    <label>
                        <input type="checkbox" name="terms[]" value="{{ category.id }}" v-model="category.checked"> {{ category.term }}
                    </label>
                </div>
            </div>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" v-model="newCategory" @keydown.enter.prevent="addCategory" placeholder="New {{ taxonomy }}">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" @click.prevent="addCategory"><i class="fa fa-fw {{ addCatButtonClass }}"></i></button>
                </span>
            </div>
            <div class="alert alert-danger top-buffer" v-show="addCategoryErrors.length"><p v-for="error in addCategoryErrors" v-text="error"></p></div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: ['checkedcategories', 'taxonomy', 'heading'],

        data: function() {
            return {
                categories: [],
                newCategory: '',
                addCatButtonClass: 'fa-plus',
                addCategoryErrors: [],
            };
        },

        computed: {
            isLoadingCategories: function() {
                return this.addCatButtonClass !== 'fa-plus';
            }
        },

        ready: function() {
            this.taxonomy = this.taxonomy || 'category'
            this.fetchCategories();
        },

        filters: {
            unsluggify: require('../filters/unsluggify.js'),
        },

        methods: {
            fetchCategories: function() {

                this.$http.get('/api/terms/' + this.taxonomy).then(categories => {
                    this.categories = categories.data.map(category => {
                        category.checked = this.checkedcategories.indexOf(parseInt(category.id)) > -1
                        return category;
                    });                  
                }).catch(err => console.warn(err));         
            },      

            addCategory: function() {

                this.addCategoryErrors = [];

                if (!this.newCategory) {
                    const unsluggify = require('../filters/unsluggify.js');
                    this.displayErrors(["Please provide a " + unsluggify(this.taxonomy)]);
                    return false;
                }

                this.addCatButtonClass = 'fa-circle-o-notch fa-spin';

                const postData = {
                    term: this.newCategory,
                    taxonomy: this.taxonomy,
                };

                this.$http.post('/api/terms', postData).then(newCategory => {
                    const category = newCategory.data
                    // On success get the returned newly created term and append to the existing
                    this.categories.unshift({
                        id: category.id,
                        term: category.term,
                        slug: category.slug,
                        checked: true
                    });

                    this.addCatButtonClass = 'fa-plus';
                }).catch(response => {
                    this.displayErrors(response.term)
                });

                this.newCategory = '';

                return false;
            },

            displayErrors: function(messages)
            {
                const errorDisplayTime = 5000;

                // On failure catch the error response and display it
                this.addCategoryErrors = messages;
                this.addCatButtonClass = 'fa-plus';

                // Wait a bit and reset the errors
                setTimeout(() => {
                    this.addCategoryErrors = [];
                }, errorDisplayTime);
            }
        }
    }
</script>