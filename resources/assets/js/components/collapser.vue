<template>
	<div>
		<h3 class="collapser-title" role="button" href="#" @click.prevent="visible = !visible" v-bind:aria-expanded="visible ? 'true' : 'false'">
			<slot name="title"></slot> <i class="fa pull-right" v-bind:class="icon"></i>
		</h3>
		<transition name="fade">
			<div v-show="visible">
				<slot></slot>
			</div>
		</transition>
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