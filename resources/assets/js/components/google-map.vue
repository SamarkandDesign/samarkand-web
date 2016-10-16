<template>
	<div>
		<div ref="map" class="googlemap"></div>
	</div>
</template>

<script>
import eventHub from '../eventHub'

	export default {
		props: ['lat', 'lng'],
		data () {
			return {
				map: null
			}
		},

		created () {
			eventHub.$on('maps-api-loaded', this.createMap.bind(this))
		},

		methods: {
			createMap () {
				const location = {
						lat: this.lat,
						lng: this.lng
					}

				this.map = new google.maps.Map(this.$refs.map, {
					zoom: 14,
					center: location,
					scrollwheel: false
				})

				new google.maps.Marker({
					map: this.map,
					position: location

				})
			}
		}
	}
</script>

<style scoped>
	.googlemap {
		height: 400px;
	}
</style>