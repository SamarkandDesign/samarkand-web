import algoliasearch from 'algoliasearch'
import Vue from 'vue'

const ProductSearch = Vue.extend({
  props: {
    query: {type: String, default: ''},
    appId: {type: String, required: true},
    key: {type: String, required: true},
    indexName: {type: String, default: 'main'}
  },
  ready () {
    let client = algoliasearch(this.appId, this.key)
    this.index = client.initIndex(this.indexName)

    // Empty the results if user click outside them
    window.addEventListener('click', e => {
      if (!this.$el.contains(e.target)) {
        this.query = ''
        this.hits = []
      }
    });
  },
  data: function () {
    return {
      hits: [],
      numberOfHits: 0,
      index: null,
      searching: false
    }
  },
  methods: {
    searchProducts () {
      if (this.query === '') {
        this.hits = []
        return
      }
      this.searching = true

      this.index.search(this.query, {
        hitsPerPage: 10
      }, (err, content) => {
        this.searching = false
        if (err) {
          console.error(err)
          return
        }
        this.hits = content.hits
        this.numberOfHits = content.nbHits
      })
    },

    displayPrice(product) {
      if (!product.sale_price) {
        return '£' + product.price
      }

      return `<del>£${product.price}</del> £${product.sale_price}`
    }
  }
})

export const ProductSearchAdmin = ProductSearch.extend({
  template: require('./templates/product-search-admin.html'),
})

export const ProductSearchVisitor = ProductSearch.extend({
  template: require('./templates/product-search-visitor.html'),
})
