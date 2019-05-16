<template>
  <div>
    <div
      class="top-buffer alert alert-dismissable clearfix"
      v-bind:class="alertClasses"
      role="alert"
      v-if="!dismissed"
    >
      <button
        type="button"
        class="close"
        aria-label="Close"
        @click="dismissed = true"
        v-if="dissmissable"
      >
        <span aria-hidden="true">&times;</span>
      </button>
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    type: { type: String, default: 'info' },
    dissmissable: { type: Boolean, default: true },
    block: { type: Boolean, default: false },
  },
  data() {
    return {
      dismissed: false,
    };
  },
  computed: {
    alertClasses() {
      return `alert-${this.type} ` + (this.block ? 'alert-block' : '');
    },
  },
};
</script>
