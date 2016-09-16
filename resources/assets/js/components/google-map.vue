<template>
	<div>
		<div v-el:map class="googlemap"></div>
	</div>
</template>

<script>
	export default {
		props: ['lat', 'lng'],
		data () {
			return {
				map: null
			}
		},

		methods: {
			createMap () {
				const location = {
						lat: this.lat,
						lng: this.lng
					}

				this.map = new google.maps.Map(this.$els.map, {
					zoom: 14,
					center: location,
					scrollwheel: false
				})

				new google.maps.Marker({
					map: this.map,
					position: location

				})
			}
		},

		events: {
			MapsApiLoaded () {
				console.log('maps loaded for component')
				this.createMap()
			}
		}
	}
</script>

<style scoped>
	.googlemap {
		height: 400px;
	}
</style>