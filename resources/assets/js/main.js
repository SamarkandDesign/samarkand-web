// Browserify Entry Point

import Vue from 'vue';
import 'flickity-imagesloaded';


// Components
import dropdown from './components/dropdown.vue';
import addressForm from './components/address-form.vue';
import customerForm from './components/customer-form.vue';
import cardForm from './components/card-form.vue';
import navbar from './components/navbar.vue';
import alert from './components/alert.vue';
import { ProductSearchVisitor as productSearch } from './components/product-search.js';
import googleMap from './components/google-map.vue';
import collapser from './components/collapser.vue';
import flicker from './components/flicker.vue';
import carouselCell from './components/carousel-cell.vue';
import carouselCopy from './components/carousel-copy.vue';
import skdFeedback from './components/skd-feedback.vue';

import eventHub from './eventHub';

window.vm = new Vue({
  el: '#app',
  components: {
    dropdown,
    addressForm,
    customerForm,
    cardForm,
    navbar,
    alert,
    productSearch,
    googleMap,
    collapser,
    flicker,
    carouselCell,
    carouselCopy,
    skdFeedback,
  },
  methods: {
    initMaps() {
      console.log('Maps loading for the VM');
      eventHub.$emit('maps-api-loaded');
    },
  },
});
