<div id="product-image-carousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    @foreach($images as $key => $image)
    <li data-target="#product-image-carousel" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
    @endforeach
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

    @foreach($images as $key => $image)
    <div class="item {{ $key === 0 ? 'active' : '' }}">
      <img src="{{ $image }}" alt="...">
    </div>
    @endforeach

  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#product-image-carousel" role="button" data-slide="prev">
    <span class="icon-prev fa fa-chevron-left xglyphicon xglyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#product-image-carousel" role="button" data-slide="next">
    <span class="icon-next fa-chevron-right xglyphicon xglyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
