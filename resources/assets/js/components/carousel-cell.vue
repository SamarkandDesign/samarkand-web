<template>
  <div class="carousel-cell" :style="cellStyle">
  <slot></slot>
  </div>
</template>

<script>
  export default {
    props: {
      image: {type: String},
      cellHeight: {type: String, default: '320px'},
    },
    computed: {
      cellStyle () {
        return {
          backgroundImage: this.image ? `url('${this.image}')` : undefined,
          height: this.cellHeight,
         }
        }
      }
  }
</script>

<style>
  .carousel-cell {
    width: 100%;
    background-position: left top;
    background-size: cover;
  }
</style>
