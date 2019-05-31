<template>
  <div>
    <div
      class="text-white text-sm font-bold px-4 py-3 mb-4"
      v-bind:class="alertClasses"
      role="alert"
      v-if="!dismissed"
    >
      <button
        type="button"
        class="float-right"
        aria-label="Close"
        @click="dismissed = true"
        v-if="dismissable"
      >
        <span aria-hidden="true">&times;</span>
      </button>
      <slot></slot>
    </div>
  </div>
</template>
<script>
const types = {
  info: 'blue',
  danger: 'red',
  success: 'green',
  warning: 'orange',
};

export default {
  props: {
    type: { type: String, default: 'info' },
    dismissable: { type: Boolean, default: true },
    block: { type: Boolean, default: false },
  },
  data() {
    return {
      dismissed: false,
    };
  },
  computed: {
    alertClasses() {
      const colour = types[this.type] || 'blue';
      return `bg-${colour}-500 ` + (this.block ? 'block' : '');
    },
  },
};
</script>
