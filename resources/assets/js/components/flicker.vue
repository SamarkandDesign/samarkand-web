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
    const f = new Flickity(this.$refs.carousel)
  }
}
</script>

