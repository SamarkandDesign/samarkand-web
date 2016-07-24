<template>
	<!-- Post images -->
	<div class="box box-primary" id="post-images">
		<div class="box-header">
			Attached Images
		</div>

		<div class="box-header" v-if="selectedImage > -1">

			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" @click="selectedImage = -1">&times;</span></button>

			<div class="row">
				<div class="col-md-4">
					<img class="img-thumbnail img-responsive" v-bind:src="images[selectedImage].thumbnail_url" alt="">
				</div>
				<div  class="col-md-8">
					<div class="form-group">
						<label>URL: </label>
						<input type="text" class="form-control input-sm" readonly value="{{ images[selectedImage].url }}">
					</div>

					<div class="form-group">
						<label>Thumbnail: </label>
						<input type="text" class="form-control input-sm" readonly value="{{ images[selectedImage].thumbnail_url }}">
					</div>
					<div class="row">
						<div class="form-group col-sm-9">
							<label>Image Title</label>
							<input type="text" v-model="customProperties.title" class="form-control" @keyup.enter.prevent="updateImage">
						</div>
						<div class="form-group col-sm-3">
							<label>Order</label>
							<input type="number" v-model="images[selectedImage].order_column" class="form-control" @keyup.enter.prevent="updateImage">
						</div>
					</div>

					<div class="form-group">
						<label>Caption</label>
						<textarea v-model="customProperties.caption" class="form-control"></textarea>
					</div>

					<button type="button" @click="updateImage" class="btn btn-primary">Update Image</button>
					<button type="button" @click="deleteImage" class="btn btn-danger">Delete Image</button>
					<button type="button" @click="selectedImage = -1" class="btn btn-link">Cancel</button>

					<p class="form-group top-buffer">
					<span v-show="imageUpdating"><i class="fa fa-circle-o-notch fa-spin"></i> Working...</span>
					<span class="text-success" v-show="imageUpdatedMessage"> <i class="fa fa-check"></i> {{ imageUpdatedMessage }}</span>
					</p>
				</div>
			</div>
		</div>

		<div class="box-body">

			<span v-show="imagesLoading"><i class="fa fa-circle-o-notch fa-spin"></i> Loading images...</span>

			<div class="row" v-show="hasImages">
			    <div class="col-md-3 col-sm-4 col-xs-6 top-buffer" v-for="image in images">
			    	<a href="#" class="thumbnail"  @click.prevent="selectImage($index)">
					<img v-bind:src="image.thumbnail_url" alt="" class="img-responsive selectable" v-bind:class="{'selected': isSelected(image.id)}">
					</a>
				</div>
			</div>

			<span v-show="!hasImages">No Images yet</span>

		</div>
	</div>
</template>


<script>
	module.exports = {
		props: ['imageableUrl'],

		data () {
			return {
				images: [],
				selectedImage: -1,
				imagesLoading: false,
				imageUpdating: false,
				imageUpdatedMessage: false,
                customProperties: {
                    title: '',
                    caption: ''
                }
			}
		},

		computed: {
			hasImages ()
			{
				return this.images.length > 0;
			}
		},

		ready ()
		{
			this.fetchImages();
		},

		methods: {
			fetchImages () {
				this.imagesLoading = true;

				this.$http.get(this.imageableUrl).then(response => {
					this.images = response.data;
					this.imagesLoading = false;
				});

			},

			updateImage (e) {
                var selectedImage = this.selectedImage;

				this.imageUpdating = true;
                this.images[selectedImage].custom_properties = this.customProperties;
				this.$http.patch('/api/media/' + this.images[selectedImage].id, this.images[selectedImage])
				.then(response => {
                    this.images[selectedImage] = response.data;
					this.imageUpdating = false;
					this.showMessage('Done');
				});
			},

			deleteImage (e)
			{
				if (confirm("Are you sure?")) {
                    var selectedImage = this.selectedImage;

					this.$http.delete('/api/media/' + this.images[selectedImage].id).then(response => {
						this.showMessage(response.data);
						this.fetchImages();
						this.selectedImage = -1;
					});
				}
			},

			showMessage (message) {
				this.imageUpdatedMessage = message;
				setTimeout(() => { this.imageUpdatedMessage = false }, 5000);
			},

			selectImage (index) {
				this.selectedImage = index;
                this.customProperties.title = this.images[index].custom_properties.title || '';
                this.customProperties.caption = this.images[index].custom_properties.caption || '';
			},

			isSelected (id) {
				return this.selectedImage > -1 && this.images[this.selectedImage].id == id;
			},

			url (image, thumbnail) {
				thumbnail = thumbnail || false;

				var url = '/images/' + image.id;
				if (thumbnail) {
					url += '?thumbnail=1';
				}
				return url;
			}
		}
	};
</script>
