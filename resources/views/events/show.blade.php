@extends('layouts.full_width')

@section('title')
{{ $event->title }} | Events
@endsection

@section('content')

<div class="event-wrapper" itemscope itemtype="http://schema.org/Event">



  <header class="h-56 flex items-end bg-cover" style="background-image: url('{{ $event->featured_image }}');">
    <div class="container mx-auto p-4">
      <h1 class="text-white text-5xl font-serif italic" itemprop="name">{{ $event->title }}</h1>
    </div>
  </header>

  <section class="container py-4 mx-auto" v-pre>
    <div class="md:flex ">
      <article class="md:w-4/5 p-4 w-full" itemprop="description">
        @if($event->hasEnded())
        <p class="italic">This event has ended</p>
        @endif

        {!! $event->getDescriptionHtml() !!}
      </article>

      <aside class="md:w-1/5 p-4 w-full vspace-5 text-sm">
        <section>

          <h3 class="text-xl">Details</h3>
          <p><strong class="font-bold">Start: </strong>{{ $event->present()->startDate() }}</p>
          <p><strong class="font-bold">End: </strong>{{ $event->present()->endDate() }}</p>

          @if($event->website)
          <p>
            <strong class="font-bold">Website: </strong>
            <a href="{{ $event->website }}" target="_blank">{{ $event->website }}</a>
          </p>
          @endif


          @if($event->organiser)
          <p>
            <strong class="font-bold">Organiser: </strong>
            {{ $event->organiser }}
          </p>
          @endif
        </section>

        <section itemprop="location" itemscope itemtype="http://schema.org/Place">
          <h3 class="text-xl">Venue</h3>
          @include('partials.address', ['address' => $event->venue])
        </section>

        <section>
          <h3 class="text-xl">Add to Calendar</h3>
          <p>
            <a href="{{ $event->present()->googleCalendarLink() }}" title="Add to Google Calendar" target="_blank"
              class="btn btn-primary btn-xs">
              <i class="fa fa-google"></i>
            </a>
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
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.browser_key') }}&callback=vm.initMaps"
  async defer></script>


@endsection