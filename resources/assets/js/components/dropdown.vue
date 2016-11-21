<template>
    <li class="dropdown">
        <a href="#" @click.prevent="active = !active">
            <strong><slot></slot></strong>
            <span class="caret"></span>
        </a>
        <transition name="fade">
        <div v-show="active">
            <slot name="dropdown-menu"></slot>
        </div>
        </transition>
    </li>

</template>

<script>
export default {
    data () {
        return {
            active: false
        }
    },
    mounted () {
        this.$nextTick(() => {
            const el = this.$el;
            window.addEventListener('click', e => {
                if (!el.contains(e.target)) {
                    this.active = false;
                }
            })
        })
    }
}
</script>

<style>
.dropdown-menu {
    display:block;
}
</style>
