<template>
	<div>
		<h3 class="collapser-title" role="button" href="#" @click.prevent="visible = !visible" aria-expanded="{{ visible ? 'true' : 'false' }}">
			<slot name="title"></slot> <i class="fa {{ icon }} pull-right"></i>
		</h3>
		<div v-show="visible" transition="fade">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['title'],
		data () {
			return {
				visible: false
			}
		},
		computed: {
			icon () {
				return this.visible ? 'fa-chevron-up' : 'fa-chevron-down'
			}
		}
	}
</script>