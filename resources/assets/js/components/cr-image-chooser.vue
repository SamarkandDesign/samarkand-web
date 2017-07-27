<template>
<div>
	<div class="box box-primary" id="featuredImageChooser">
		<div class="box-header">
			Featured Image
		</div>
		<div class="box-body">
			<p v-if="!selectedImage" class="text-center">None Chosen</p>

			<img
				v-if="selectedImage"
				v-bind:src="selectedImage.thumbnail_url"
				v-bind:alt="selectedImage.name"
				class="img-responsive thumbnail"
				style="width:100%;"
			>

			<!-- Button trigger modal -->
			<button
				type="button"
				class="btn btn-default"
				data-toggle="modal"
				data-target="#imagesModal"
				@click="fetchImages()"
			>Choose</button>

			<button
				type="button"
				class="btn btn-link text-danger"
				v-if="selectedImage"
				@click="selectedImage = null"
			>Remove Image</button>

			<input
				type="hidden"
				name="media_id"
				v-bind:value="selectedImage ? selectedImage.id : null"
			>
		</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="imagesModal" tabindex="-1" role="dialog" aria-labelledby="modal-title">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Choose Featured Image</h4>
				</div>
				<div class="modal-body">

					<span v-if="!images"><i class="fa fa-circle-o-notch fa-spin"></i> Fetching Images</span>

					<div class="row" v-if="images">
						<p class="col-xs-12" v-if="images">
							Page {{ page }} of {{ lastPage }}
						</p>
						<div class="col-xs-3 top-buffer" v-for="image in images">
							<a href="#" @click="selectImage(image, $event)" data-dismiss="modal">
							<img
								class="img-responsive img-thumbnail selectable"
								v-bind:src="image.thumbnail_url"
								v-bind:alt="image.title"
							>
							</a>

						</div>
						<p class="clearfix col-xs-12">
							<button type="button" class="btn btn-link pull-left" @click="prevPage"><i class="fa fa-chevron-left"></i> Prev</button>
							<button type="button" class="btn btn-link pull-right" @click="nextPage">Next <i class="fa fa-chevron-right"></i></button>
						</p>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	</div>
</template>

<script>
	export default {
		props: ['image'],

		mounted (){
			this.fetchChosenImage()
		},

		data () {
			return {
				selectedImage: null,
				chosenImage: null,
				page: 1,
				lastPage: null,
				images: null,
			}
		},

		methods: {
			fetchImages () {
				this.images = null;
				this.$http.get('/api/media', {page: this.page}).success(response => {
					this.images = response.data
					this.lastPage = response.last_page;
				})
			},

			fetchChosenImage () {
				if (this.image) {
					this.$http.get('/api/media/' + this.image).success(response => {
						this.selectedImage = response;
					})
				}
			},

			selectImage (image, e) {
				if (e) e.preventDefault();
				this.selectedImage = image;
			},

			isSelected (image) {
				return this.selectedImage ? this.selectedImage.id == image.id : false;
			},

			nextPage () {
				if (this.page < this.lastPage) {
					this.page++;
					this.fetchImages();
				}
			},
			prevPage () {
				if(this.page > 1) {
					this.page--;
					this.fetchImages();
				}
			}
		}
	};
</script>
