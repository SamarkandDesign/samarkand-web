<div class="panel panel-default" id="postContent">
  <div class="panel-body">
    <cr-title-slugger initial-value="{{ $page->title }}" initial-slug="{{ $page->slug }}" name="title"></cr-title-slugger>
    @if ($page->exists())
    <small>
        <strong>Permalink:</strong> <code>/{{ $page->path }}</code> <a href="/{{ $page->path }}" target="_blank"><i class="fa fa-link"></i></a>
    </small>
    @endif
    <div class="form-group top-buffer" id="postContent">

      <cr-markarea initial-value="{{ $page->content }}" name="content" title="Content"></cr-markarea>

    </div>
  </div>
</div>
