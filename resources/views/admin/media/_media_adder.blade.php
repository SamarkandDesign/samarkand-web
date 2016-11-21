
<cr-imageable-gallery imageable-url="{{ route("api.{$model->getTable()}.images", $model->id) }}"></cr-imageable-gallery>

<div class="box box-primary">
    <div class="box-header">
        Upload Images
    </div>
  <div class="box-body">
    <image-uploader action="{{ route("api.{$model->getTable()}.media.store", $model->id) }}" csrf-token="{{ csrf_token() }}"></image-uploader>
  </div>
</div>
