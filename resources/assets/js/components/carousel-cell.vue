<template>
  <div class="carousel-cell" :style="{backgroundImage: `url('${image}')`}">
  <div class="carousel-copy" v-if='headline || copy'>
      <h3 class="headline-text" v-if='headline'>{{headline}}</h3>
      <div class="body-text"><slot></slot></div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['image', 'headline'],
  }
</script>

<style>

  .carousel-cell {
    height: 320px;
    width: 100%;
    background-position: left top;
    background-attachment: cover;
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
