<template>
	<div>
		<div class="form-group">
			<label for="title" class="sr-only">{{ capitalizedName }}</label>
			<input 
			type="text" 
			v-bind:name="name" 
			class="post-title-input" 
			v-bind:placeholder="capitalizedName" 
			v-model="value" 
			@blur="setNewSlug">                 
		</div>
		<div class="form-group">
			<label for="slug" class="sr-only">Slug</label>
			<div class="input-group input-group-sm">
		  
				<input type="text" name="slug" class="form-control" placeholder="slug" v-model="slug">

				<span class="input-group-btn refresh-slug">
					<button class="btn btn-default" v-bind:value="slug" type="button" @click.prevent="sluggifyTitle"><i class="fa fa-refresh"></i></button>
				</span>
			</div>
		</div>
	</div>
</template>

<script>
import {sluggify, capitalize} from '../filters'

export default {
	props: ['initial-value', 'initial-slug', 'name'],
	data () {
		return {
			value: '',
			slug: ''
		}
	},
	mounted () {
		this.value = this.initialValue || ''
		this.slug = this.initialSlug || ''
	},
	methods: {
		sluggifyTitle () {
			this.slug = sluggify(this.value);
		},
		setNewSlug () {
			if (this.slug === '') {
				this.sluggifyTitle();
			}
		}
	},

	computed: {
		capitalizedName () {
			return capitalize(this.name)
		}
	}
}
</script>