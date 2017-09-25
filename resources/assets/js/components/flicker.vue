<template>
  <div ref='carousel'>
<slot></slot>
</div>
</template>

<script>
import Flickity from 'flickity'

export default {
  props: {

  },
  mounted () {
    const f = new Flickity(this.$refs.carousel, {
      imagesLoaded: true,
      wrapAround: true,
      pageDots: false,
    })
  }
}
</script>

