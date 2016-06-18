// Browserify Entry Point
window.$ = window.jQuery = require('jquery');
//
global.Vue = require('vue');

import dropdown from './components/dropdown.vue'
import slider from './components/slider.vue'
import carousel from './components/carousel.vue'

// import Vue from 'vue'

let vm = new Vue({
    el: '#app',
    ready () {
        console.log('vue is working!')
    },
    components: { dropdown, carousel, slider }
})
