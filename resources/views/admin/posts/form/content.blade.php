<div class="panel panel-default" id="postContent">
  <div class="panel-body">
    <cr-title-slugger initial-value="{{ $post->title }}" slug="{{ $post->slug }}" name="title"></cr-title-slugger>

    <div class="form-group top-buffer" id="postContent">

      <cr-markarea initial-value="{{ $post->content }}" name="content" title="Content"></cr-markarea>                

    </div>
  </div>
</div>