<template lang="html">
  <div class="product-search">
    <form class="form" action="/shop/search" method="get">

      <div class="input-group">
        <input type="search"
        name="query"
        value="{{ query }}"
        class="form-control"
        placeholder="Search Products"
        v-model="query"
        @keyup="searchProducts | debounce 350"
        >
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
        </span>
      </div><!-- /input-group -->

    </form>

    <div class="hits" v-show="hits.length">
      <a class="clear-hits" href="#" @click.prevent="hits = []" title="Clear results"><i class="fa fa-times fa-fw"></i></a>
      <div v-for="hit in hits" class="product-search-result" transition="fade">
        <a href="{{ hit.url }}">
          <div class="image-search-thumb">
            <img :src="hit.image_url" clas="img-responsive" alt="" />
          </div>
          <div class="">

            <h4>{{ hit.name }}</h4>

            <p class="text-muted">
              {{ hit.categories.join(', ')}}
            </p>

            <div class="clearfix"> </div>
          </div>
        </a>
      </div>
      <div class="alogia-logo">
        Powered by <a href="http://www.algolia.com/?utm_source=samarkand&utm_medium=link" target="_blank"><img src="/img/Algolia_logo_bg-white.svg" alt="Powered by Algolia" /></a>
      </div>
    </div>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch'

export default {
  props: {
    query: {type: String, default: ''},
    appId: {type: String, required: true},
    key: {type: String, required: true},
    indexName: {type: String, default: 'main'}
  },
  ready () {
    let client = algoliasearch(this.appId, this.key)
    this.index = client.initIndex(this.indexName);

    // Empty the results if user click outside them
    window.addEventListener('click', e => {
      if (!this.$el.contains(e.target)) {
        this.hits = [];
      }
    });
  },
  data: function () {
    return {
      hits: [],
      index: null
    }
  },
  methods: {
    searchProducts () {
      if (this.query === '') {
        this.hits = []
        return
      }

      this.index.search(this.query, (err, content) => {
        if (err) {
          console.error(err);
          return;
        }
        this.hits = content.hits
      });
    }
  },
  components: {}
}
</script>

<style lang="scss" scoped>
@import '../../sass/main/_variables';

.product-search {
  position: relative;
}

.hits {
  background: #fff;
  border-width: 3px;
  border-color: $btn-default-border;
  border-style: none solid;
  position: absolute;
  width: 100%;
  z-index: 9;
  border-bottom-right-radius: 2px;
  border-bottom-left-radius: 2px;
  box-shadow: 0 1px 0 0 rgba(0,0,0,.2),0 2px 3px 0 rgba(0,0,0,.1);
}

.product-search-result {
  a {
    padding: 10px;
    clear: both;
    border-bottom: 3px $btn-default-border solid;
    display: block;
  }

  a:hover {
    text-decoration: none;
    background: darken(rgba(255,255,255,.9), 8%);
  }
}
.image-search-thumb {
  float: left;
  max-width:50px;
  margin-right: 10px;

  img {
    max-width: 100%;
    height: auto;
  }
}

.clear-hits {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 120%;
}

.alogia-logo {
font-size: 9px;
  position: absolute;
    bottom: 10px;
    right: 10px;
  img {
    width:50px;
    height:auto;
  }
}
</style>
