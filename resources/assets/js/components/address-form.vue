<template>
  <div class="sm:flex -mx-4">
    <div class="sm:w-1/2 px-4">
      <h3 class="mb-5 text-xl">Billing Address</h3>
      <slot name="billing-address"></slot>
    </div>

    <div class="sm:w-1/2 px-4 sm:mt-0 mt-8">
      <div class="flex">
        <div class="w-1/2">
          <h3 class="mb-5 text-xl">Shipping Address</h3>
        </div>
        <div class="w-1/2 text-right">
          <label>
            <input type="checkbox" name="different_shipping_address" v-model="differentshipping"> Use a different address
          </label>
        </div>
      </div>
      <p v-show="!differentshipping">Same as billing</p>
      <transition name="fade">
        <div v-show="differentshipping">
          <slot name="shipping-address"></slot>
        </div>
      </transition>
      <slot name="delivery-note"></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    differentshipping: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
