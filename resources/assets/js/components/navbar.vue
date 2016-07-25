<template>
  <nav>
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" @click="collapsed = !collapsed" class="navbar-toggle" v-bind:class="{'collapsed': collapsed}" aria-expanded="{{ collapsed ? 'false' : 'true' }}">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <slot name="brand"></slot>

      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="navbar-collapse" v-bind:class="{'collapse': collapsed}" v-show="!collapsed">
        <slot></slot>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

</template>

<script>
export default {
  data () {
    return {
      collapsed: true
    }
  }
}
</script>
