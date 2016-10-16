@extends('layouts.full_width')

@section('title')
{{ $event->title }} | Events
@endsection

@section('content')

<div class="event-wrapper" itemscope itemtype="http://schema.org/Event">

  <header class="event-header" v-pre>
    <div class="header-bg"></div>
    <div class="container header-content">
      <div class="row no-gutter">
        <div class="col-md-8 event-image">
          <img src="{{ $event->featured_image }}" alt="">
        </div>
        <div class="col-md-4 event-title">
          <h1 itemprop="name">{{ $event->title }}</h1>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </header>

  <section class="container" v-pre>


    <div class="row">
      <article class="description col-sm-8" itemprop="description">
        @if($event->hasEnded())
        <alert style="margin-top:20px;">This event has ended</alert>
        @endif
        <h3>Description</h3>
        {!! $event->getDescriptionHtml() !!}
      </article>

      <aside class="col-sm-4">
        <section>

          <h3>Details</h3>
          <p><strong>Start: </strong>{{ $event->present()->startDate() }}</p>
          <p><strong>End: </strong>{{ $event->present()->endDate() }}</p>

          @if($event->website)
          <p>
            <strong>Website: </strong>
            <a href="{{ $event->website }}" target="_blank">{{ $event->website }}</a>
          </p>
          @endif

          @if($event->organiser)
          <p>
            <strong>Organiser: </strong>
              {{ $event->organiser }}
          </p>
          @endif
        </section>

        <section itemprop="location" itemscope itemtype="http://schema.org/Place">
          <h3>Venue</h3>
          @include('partials.address', ['address' => $event->venue])
        </section>

        <section>
          <h3>Add to Calendar</h3>
          <p>
            <a href="{{ $event->present()->googleCalendarLink() }}" title="Add to Google Calendar" target="_blank" class="btn btn-primary btn-xs">
              <i class="fa fa-google"></i>
            </a>

{{--             <a href="#" title="iCal" target="_blank" class="btn btn-primary btn-xs">
              <i class="fa fa-calendar"></i>
            </a> --}}
          </p>
        </section>

        <section class="share-links">
          <h3>Share</h3>
          <p class="">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ '$shareUrl' }}" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>
            <a href="https://twitter.com/intent/tweet/?text={{ urlencode($event->title) }}&url={{ '$shareUrl' }}&via=samarkanddesign" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
          </p>
        </section>
      </asidie>


    </div>
  </section>

  @if($event->venue->lat)
  <div>
    <div itemprop="map" itemtype="https://schema.org/Map">
      <google-map :lat="{{ $event->venue->lat }}" :lng="{{ $event->venue->lng }}"></google-map>
    </div>
  </div>
  @endif
</div>

@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.browser_key') }}&callback=vm.initMaps" async defer></script>

<style>
  .header-bg {
    background-image: url('{{ $event->featured_image }}');
  }
</style>
@endsection
