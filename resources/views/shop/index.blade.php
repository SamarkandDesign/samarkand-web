@extends('layouts.main')

@section('title')
  @if ($product_category->id)
    {{ $product_category->term }} |
  @elseif (Request::has('query'))
    "{{ Request::get('query') }}" |
  @endif
  Shop
@endsection

@section('head')
  @if ($product_category->id)
    <meta name="description" content="Shop {{ $product_category->term }} sourced from around the world">
  @else
    <meta name="description" content="Shop vintage lighting, lampshades and home accessories">
  @endif
@endsection

@section('section-header')
@if($product_category->id)
<header class="section-header" style="background-image: url('/img/product-cat-bg.jpg')">
<div class="container">
<h1 class="category-header">{{ $product_category->term }}</h1>
</div>
</header>
@endif
@endsection

@section('breadcrumb')
    @if ($product_category->id or 'uncategorised' == $product_category->slug)
  <ol class="breadcrumb">
      <li><a href="/shop">Shop</a></li>
      <li class="active">{{ $product_category->term }}</li>
  </ol>
    @endif
@endsection

@section('content')


  <section class="shop-filters row">
    <div class="col-md-6">
      @include('shop._category_filter')
      @include('shop._product_search')
    </div>

    <div class="col-md-6">
      @include('shop._attribute_filter')
    </div>

  </section>

  <section class="products">
    @if(Request::has('query'))
      <h2>Results for "{{ Request::get('query') }}"</h2>
    @elseif($product_category->id)
      <h2>{{ $product_category->term }}</h2>
    @endif

    @if($products->total() > 0)
      <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} items</p>
    @else
      No items
    @endif

    @foreach ($products->chunk($products_per_row) as $product_group)
      <div class="row">

        @foreach ($product_group as $i => $product)
          <div class="product col-md-{{ (int) 12 / $products_per_row }} col-xs-{{ (int) 24 / $products_per_row }} top-buffer">
            @include('shop._product_tile', compact('product'))
          </div>

          @if( ($i + 1) % ($products_per_row / 2) === 0 )
            <div class="clearfix visible-xs-block"></div>
          @endif

        @endforeach

      </div>
    @endforeach
  </section>
  <div class="text-center">
    {!! $products->appends(Request::query())->links() !!}
  </div>

@stop
