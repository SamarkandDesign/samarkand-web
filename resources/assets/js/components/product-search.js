import algoliasearch from 'algoliasearch';
import Vue from 'vue';
import debounce from 'lodash/debounce';

const ProductSearch = Vue.extend({
  props: {
    initialQuery: { type: String, default: '' },
    appId: { type: String, required: true },
    apiKey: { type: String, required: true },
    indexName: { type: String, default: 'main' },
  },

  mounted() {
    this.query = this.initialQuery;
    const client = algoliasearch(this.appId, this.apiKey);
    this.index = client.initIndex(this.indexName);

    // Empty the results if user click outside them
    this.$nextTick(() => {
      window.addEventListener('click', e => {
        if (!this.$el.contains(e.target)) {
          this.query = '';
          this.hits = [];
        }
      });
    });
  },
  data() {
    return {
      hits: [],
      numberOfHits: 0,
      index: null,
      searching: false,
      query: '',
    };
  },
  methods: {
    searchProducts: debounce(function() {
      if (this.query === '') {
        this.hits = [];
        return;
      }
      this.searching = true;

      this.index.search(
        this.query,
        {
          hitsPerPage: 5,
        },
        (err, content) => {
          this.searching = false;
          if (err) {
            console.error(err);
            return;
          }
          this.hits = content.hits;
          this.numberOfHits = content.nbHits;
        },
      );
    }, 500),

    displayPrice(product) {
      if (!product.sale_price) {
        return '£' + product.price;
      }

      return `<del>£${product.price}</del> £${product.sale_price}`;
    },
  },
});

export const ProductSearchAdmin = ProductSearch.extend({
  template: require('./templates/product-search-admin.html'),
});

export const ProductSearchVisitor = ProductSearch.extend({
  template: require('./templates/product-search-visitor.html'),
});
