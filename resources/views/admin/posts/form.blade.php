

<div class="col-md-8">
@include('admin.posts.form.content')
@include('admin.media._media_adder', ['model' => $post])
</div>

<div class="col-md-4"  id="postMeta">
    <div>@include('admin.posts.form.meta')</div>

    <div>
    	<cr-category-chooser :checkedcategories="{{ $post->categories->pluck('id')->toJson(JSON_NUMERIC_CHECK) }}"></cr-category-chooser>
    </div>
    <div>@include('admin.posts.form.tags')</div>
</div>




   