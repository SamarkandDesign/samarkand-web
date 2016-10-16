<template>
	<div>
		<div>
			<label v-bind:for="name" class="sr-only">Content</label>
			<textarea class="form-control" v-bind:name="name" placeholder="Content (Markdown/HTML)" v-model="value" cols="50" rows="10">
			</textarea>
		</div>

		<div class="panel panel-default top-buffer">                    
			<div class="panel-body" v-html="parsedContent"></div>
		</div>
	</div>
</template>

<script>
	import marked from 'marked'
	
	export default {
		props: ['name', 'title', 'initial'],
		data () {
			return {
				value: ''
			}
		},
		mounted () {
			this.value = this.initial || ''
		},
		computed: {
			parsedContent () {
				return marked(this.value)
			}
		}
	}
</script>