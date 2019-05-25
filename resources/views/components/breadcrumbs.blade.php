<ol class="flex hspace-4">
  @foreach ($breadcrumbs as $crumb)
  @if(isset($crumb['url']))
  <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
  @else
  <li>{{ $crumb['label'] }}</li>
  @endif
  @endforeach
</ol>