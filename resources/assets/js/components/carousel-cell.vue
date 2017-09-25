<template>
  <div class="carousel-cell" :style="cellStyle">
  <div class="carousel-copy" v-if='headline || true'>
      <h3 class="headline-text" v-if='headline'>{{headline}}</h3>
      <div class="body-text"><slot></slot></div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      image: {type: String},
      headline: {type: String},
      cstyle: {type: Object, default: () => ({})},
    },
    computed: {
      cellStyle () {
        return Object.assign({}, {
          backgroundImage: this.image ? `url('${this.image}')` : undefined,
          height: '320px',
         }, this.cstyle)
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
.carousel-cell .carousel-copy {
    position: absolute;
    top: 10%;
    left: 10%;

}
  .carousel-cell .headline-text {
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 50px;
  }
  .carousel-cell .body-text {
    color: #fff;

  }

</style>
