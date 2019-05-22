<template>
  <nav class="p-4 border-b border-gray-300">
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <div class="flex items-center flex-no-shrink">
          <slot name="image"></slot>
        </div>
        <div class="hidden lg:block">
          <slot name="items"></slot>
        </div>
      </div>
      <slot name="search"></slot>
      <div class="hidden lg:block">
        <slot name="right-items"></slot>
      </div>
      <div class="block lg:hidden">
        <button
          type="button"
          @click="collapsed = !collapsed"
          v-bind:aria-expanded="collapsed ? 'false' : 'true'"
          class="flex items-center px-3 py-2 border rounded text-grey-darker border-grey-darker hover:text-dark hover:border-grey-dark"
        >
          <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
          </svg>
        </button>
      </div>
    </div>
    <div
      class="w-full block lg:flex lg:items-center lg:w-auto lg:hidden"
      v-bind:class="{hidden: collapsed}"
    >
      <slot name="items"></slot>
      <slot name="right-items"></slot>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      collapsed: true,
    };
  },
};
</script>
