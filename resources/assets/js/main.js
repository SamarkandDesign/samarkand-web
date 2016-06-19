// Browserify Entry Point

//
import Vue from 'vue'
import VueValidator from 'vue-validator'

import dropdown from './components/dropdown.vue'
import slider from './components/slider.vue'
import carousel from './components/carousel.vue'
import addressForm from './components/address-form.vue'
import customerForm from './components/customer-form.vue'
import cardForm from './components/card-form.vue'


Vue.use(VueValidator)

let vm = new Vue({
    el: '#app',
    ready () {
        console.log('vue is working!')
    },
    components: { dropdown, carousel, slider, addressForm, customerForm, cardForm }
})
