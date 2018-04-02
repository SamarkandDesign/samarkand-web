<template>
  <div class="carousel-cell" :style="cellStyle">
  <slot></slot>
  </div>
</template>

<script>
  export default {
    props: {
      image: {type: String},
      cellHeight: {type: String, default: undefined},
    },
    computed: {
      cellStyle () {
        return {
          backgroundImage: this.image ? `url('${this.image}')` : undefined,
          height: this.cellHeight
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
    height: 320px;
  }
  @media (max-width: 768px) {
    .carousel-cell {
      height: 360px;
    }
  }
</style>
