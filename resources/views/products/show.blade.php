@extends('layouts.full_width')

@section('title')
{{ $product->name }} | {{ $product->product_category->term }}
@endsection

@section('head')
<meta name="description" content="{{ $product->name }} {{ $product->price->symbol().$product->price->asMoney() }}">
@endsection

@section('breadcrumb')
@component('components.breadcrumbs', [
'breadcrumbs' => [
['url' => '/shop', 'label' => 'Shop'],
['url' => "/shop/$product->product_category->slug", 'label' => $product->product_category->term],
['label' => $product->name],
]
])
@endcomponent

@endsection

@section('content')

@include('partials.errors')

<div itemscope itemtype="http://schema.org/Product" class="vspace-5">
  <div class="md:flex" v-pre>
    <div class="md:w-1/2 w-full bg-gray-100">

      <div itemprop="image">
        @include('products._product_image')
      </div>

    </div>

    <div class="md:w-1/2 w-full px-10 md:px-12 lg:px-24 vspace-12 py-8">
      <section>

        <header class="serif text-3xl uppercase font-normal tracking-wider">
          <h1 itemprop="name">{{ $product->name }}</h1>
        </header>

        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="text-xl">
          <meta itemprop="priceCurrency" content="{{ config('shop.currency') }}" />
          {{ $product->present()->price() }}
        </div>
        <div>
          <span class="text-gray-500 uppercase text-lg">
            <link itemprop="availability"
              href="http://schema.org/{{ $product->stock_qty > 0 ? 'InStock' : 'OutOfStock' }}" />
            {{ $product->present()->stock() }}
          </span>
        </div>
      </section>

      <div class="vspace-10">
        <div itemprop="description" class="vspace-4 text-sm">
          {!! $product->getDescriptionHtml() !!}
        </div>

        @if ($product->inStock())
        <section>
          <form action="/cart" method="POST" id="add-to-cart-form" class="vspace-10">
            <div class="hidden">
              {{ csrf_field() }}
              <input type="hidden" name="product_id" value="{{ $product->id }}">
            </div>
            <div>
              <div class="flex items-center">
                <label for="quantity" class="text-gray-700 mr-8 font-bold">Quantity</label>
                <div class="w-16">

                  <input type="number" id="quantity" name="quantity" value="1" min="1" step="1"
                    max="{{ $product->stock_qty }}" class="form-control w-16" required>
                </div>


              </div>
            </div>
            <button type="submit" class="btn btn-success w-full md:w-1/2">Add To basket</button>
          </form>
        </section>
        @endif

      </div>

      <section class="text-xs text-gray-500">
        <strong>Categories:</strong>
        @foreach ($product->product_categories as $index => $category)
        <a href="/shop/{{$category->slug}}">{{ $category->term }}</a>@if ($index+1 !==
        $product->product_categories->count() ), @endif
        @endforeach
      </section>

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


  <meta itemprop="url" content="{{ Request::url() }}">
</div>

@stop