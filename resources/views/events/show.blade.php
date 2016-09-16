@extends('layouts.full_width')

@section('title')
{{ $event->title }}
@endsection

@section('content')

<div class="event-wrapper" itemscope itemtype="http://schema.org/Event">

  <header class="event-header">
    <div class="header-bg"></div>
    <div class="container header-content">
      <div class="row no-gutter">
        <div class="col-md-8 event-image">
          <img src="https://unsplash.it/800/600?random" alt="">
        </div>
        <div class="col-md-4 event-title">
          <h1 itemprop="name">{{ $event->title }}</h1>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>


  </header>

  <section class="container">



    <div class="row">
      <article class="description col-sm-8" itemprop="description">
        <h3>Description</h3>
        {{ $event->description }}
      </article>

      <aside class="col-sm-4">
        <section>

          <h3>Details</h3>
          <p>
            <strong>Start:</strong>
            <time itemprop="startDate" content="{{ $event->start_date->toIso8601String() }}">{{  $event->start_date->format($event->all_day ? 'j M Y' : 'j M Y, H:i') }}
            </time>
          </p>

          <p>
            <strong>End:</strong>
            <time itemprop="endDate" content="{{ $event->end_date->toIso8601String() }}">{{  $event->end_date->format($event->all_day ? 'j M Y' : 'j M Y, H:i') }}
            </time>
          </p>


          @if($event->website)
          <p>
            <strong> Website: </strong>
            <a href="{{ $event->website }}" target="_blank">{{ $event->website }}</a>
          </p>
          @endif
        </section>


        <section itemprop="location" itemscope itemtype="http://schema.org/Place">
          <h3>Venue</h3>
          @include('partials.address', ['address' => $event->venue])
        </section>

        <section class="share-links">
          {{-- <h3>Share this event</h3> --}}
          <p class="">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ '$shareUrl' }}" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>
            <a href="https://twitter.com/intent/tweet/?text={{ urlencode($event->title) }}&url={{ '$shareUrl' }}&via=samarkanddesign" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
          </p>
        </section>
      </asidie>


    </div>
  </section>

  <div>
    <div itemprop="map" itemtype="https://schema.org/Map">
      <google-map :lat="{{ $event->venue->lat }}" :lng="{{ $event->venue->lng }}"></google-map>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.browser_key') }}&callback=vm.initMaps" async defer></script>
@endsection
