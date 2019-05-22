@extends('layouts.main')

@section('title')
{{ $product->name }} | {{ $product->product_category->term }}
@endsection

@section('head')
<meta name="description" content="{{ $product->name }} {{ $product->price->symbol().$product->price->asMoney() }}">
@endsection

@section('breadcrumb')
<div class="sections">
  <ol class="breadcrumb content-breadcrumb">
    <li><a href="/shop">Shop</a></li>
    <li><a href="/shop/{{ $product->product_category->slug }}">{{ $product->product_category->term }}</a></li>
    <li class="active">{{ $product->name }}</li>
  </ol>
</div>
@endsection

@section('content')

@include('partials.errors')

<div itemscope itemtype="http://schema.org/Product" class="vspace-5">
  <div class="md:flex -mx-2" v-pre>
    <div class="md:w-3/5 w-full px-2">

      <div itemprop="image">
        @include('products._product_image')
      </div>

    </div>


    <div class="md:w-2/5 w-full px-2 vspace-5">
      <header class="serif text-3xl">
        <h1 itemprop="name">{{ $product->name }}</h1>
      </header>


      <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="col-xs-6">
        <meta itemprop="priceCurrency" content="{{ config('shop.currency') }}" />
        {{ $product->present()->price() }}
      </div>





      @if ($product->inStock())
      <section>
        <form action="/cart" method="POST" id="add-to-cart-form" class="vspace-5">
          <div class="hidden">
            {{ csrf_field() }}
            <input type="hidden" name="product_id" value="{{ $product->id }}">
          </div>
          <div>
            <label for="quantity" class="col-xs-f6 control-label">Quantity</label>
            <div class="flex items-center -mx-2">
              <div class="w-1/2 px-2">
                <input type="number" id="quantity" name="quantity" value="1" min="1" step="1"
                  max="{{ $product->stock_qty }}" class="form-control" required>
              </div>
              <div class="w-1/2 px-2">
                <span class="stock {{ $product->stock_qty > 0 ? 'in-stock' : '' }}">
                  <link itemprop="availability"
                    href="http://schema.org/{{ $product->stock_qty > 0 ? 'InStock' : 'OutOfStock' }}" />
                  {{ $product->present()->stock() }}
                </span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-full">Add To basket</button>
        </form>
      </section>
      @else
      <div>
        <span class="stock {{ $product->stock_qty > 0 ? 'in-stock' : '' }}">
          <link itemprop="availability"
            href="http://schema.org/{{ $product->stock_qty > 0 ? 'InStock' : 'OutOfStock' }}" />
          {{ $product->present()->stock() }}
        </span>
      </div>
      @endif



      <section class="share-links">
        <p class="text-center">
          Share this product
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"><i
              class="fa fa-fw fa-facebook"></i></a>
          <a href="https://twitter.com/intent/tweet/?text={{ urlencode($product->name) }}&url={{ $shareUrl }}&via=samarkanddesign"
            target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
          <a href="https://www.pinterest.com/pin/create/button/?url={{ $shareUrl }}&media={{ urlencode($product->present()->thumbnail_url()) }}&description={{ $product->name }}"
            target="_blank"><i class="fa fa-fw fa-pinterest"></i></a>
        </p>
      </section>
    </div>
  </div>

  <div class="md:w-3/5 w-full">
    <section class="vspace-5">
      <header>
        <h4>Description</h4>
      </header>
      <div itemprop="description">
        {!! $product->getDescriptionHtml() !!}
      </div>
    </section>

    <section>
      <strong>Categories:</strong>
      @foreach ($product->product_categories as $index => $category)
      <a href="/shop/{{$category->slug}}">{{ $category->term }}</a>@if ($index+1 !==
      $product->product_categories->count() ), @endif
      @endforeach
    </section>
  </div>
  <meta itemprop="url" content="{{ Request::url() }}">
</div>

@stop