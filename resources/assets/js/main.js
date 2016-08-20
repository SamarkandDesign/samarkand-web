// Browserify Entry Point

import Vue from 'vue'
import VueValidator from 'vue-validator'

// Components
import dropdown from './components/dropdown.vue'
import slider from './components/slider.vue'
import carousel from './components/carousel.vue'
import addressForm from './components/address-form.vue'
import customerForm from './components/customer-form.vue'
import cardForm from './components/card-form.vue'
import navbar from './components/navbar.vue'
import alert from './components/alert.vue'
import {ProductSearchVisitor as productSearch} from './components/product-search.js'

Vue.use(VueValidator)

let vm = new Vue({
    el: '#app',
    components: { dropdown, carousel, slider, addressForm, customerForm, cardForm, navbar, alert, productSearch }
})
