
<cr-imageable-gallery imageable-url="{{ route("api.{$model->getTable()}.images", $model->id) }}" v-ref:gallery></cr-imageable-gallery>

<div class="box box-primary">
    <div class="box-header">
        Upload Images
    </div>
  <div class="box-body">
    {!! Form::open(['route' => ["api.{$model->getTable()}.media.store", $model->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'attachImagesForm']) !!}
    {{ csrf_field() }}
    {!! Form::close() !!}
  </div>
</div>


@section('admin.scripts')
@parent
<script>
  Dropzone.options.attachImagesForm = {
    paramName: 'image',
    maxFilesize: 5, //5MB limit
    acceptedFiles: '.jpeg, .jpg, .png, .bmp, .gif, .svg',
    init: function()
    {
      this.on("complete", function(file)
      {
        this.removeFile(file);
        vm.$refs.gallery.fetchImages();

      });
    }
  }
</script>
@stop
