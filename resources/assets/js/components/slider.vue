<template>
  <div class="item">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        index: 0,
        show: false,
      }
    },
    computed: {
      show () {
        return this.$parent.activeIndex === this.index
      }
    },
    mounted () {
      for (var c in this.$parent.$children) {
        if (this.$parent.$children[c].$el == this.$el) {
            this.index = parseInt(c,10);
            break;
        }
      }
      //this.index = [...this.$el.parentNode.children].indexOf(this.$el)
      this.$parent.indicator.push(this.index)
      if (this.index === 0)  {
        this.$el.classList.add('active')
      }
    }
  }
</script>

<style>
  .item img {
    width: 100%;
  }
</style>
