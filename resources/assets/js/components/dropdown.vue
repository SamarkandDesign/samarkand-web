<template>
    <li class="dropdown">
        <a href="#" @click.prevent="active = !active">
            <strong><slot></slot></strong>
            <span class="caret"></span>
        </a>
        <div v-show="active">
            <slot name="dropdown-menu"></slot>
        </div>
    </li>

</template>

<script>
export default {
    props: {
        active: {
            type: Boolean,
            default: false
        }
    },
    ready () {
        var el = this.$el;
        window.addEventListener('click', e => {
            if (!el.contains(e.target)) {
                this.active = false;
            }
        });
    },
}
</script>

<style>
.dropdown-menu {
    display:block;
}
</style>
