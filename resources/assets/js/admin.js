// Browserify Entry Point

window.$ = window.jQuery = require('jquery');

global.Vue = require('vue');
global.Dropzone = require('dropzone');

global.AdminLTEOptions = {
  animationSpeed: 100,
  };

require('bootstrap');

require('vue-resource');

Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name=csrf-token]').prop('content');

// Custom interceptor to attach lower case content type headers to response
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});

String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

// Larail allows sending POST/PUT/DELETE requests using an a tag
var larail = require('./plugins/larail.js');

import graph from './components/graph.js'
import alert from './components/alert.vue'
import {ProductSearchAdmin as productSearch} from './components/product-search.js'
import markArea from './components/cr-markarea.vue'
import titleSlugger from './components/cr-title-slugger.vue'
import categoryChooser from './components/cr-category-chooser.vue'
import imageableGallery from './components/cr-imageable-gallery.vue'
import imageChooser from './components/cr-image-chooser.vue'
import attributeForm from './components/cr-attribute-form.vue'
import imageUploader from './components/image-uploader.vue'

window.vm = new Vue({
	el: '#admin',

	components: {
		'cr-markarea': markArea,
		'cr-title-slugger': titleSlugger,
		'cr-category-chooser': categoryChooser,
		'cr-imageable-gallery': imageableGallery,
		'cr-image-chooser': imageChooser,
        'cr-attribute-form': attributeForm,
        'product-search': productSearch,
        'graph': graph,
        'alert': alert,
        'image-uploader': imageUploader
	}
})

// Activate select2 for multi-select
var select2 = require('select2');

jQuery(function(){
	$('.select2').select2({
        tags: true
    });
});

require('admin-lte')
