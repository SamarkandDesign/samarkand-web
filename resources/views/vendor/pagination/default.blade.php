@php
$listItemClass = 'w-10 h-10 flex justify-center items-center bg-gray-100';
@endphp

<ul class="flex justify-center">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())

    @else
    <li class="{{ $listItemClass }} "><a href="{{ $paginator->previousPageUrl() }}" rel="prev"
            class="w-full h-full flex justify-center items-center hover:bg-gray-200">&larr;</a></li>
    @endif

    <!-- Pagination Elements -->
    @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
    @if (is_string($element))
    <li class="{{ $listItemClass }} text-gray-600"><span>{{ $element }}</span></li>
    @endif

    <!-- Array Of Links -->
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="{{ $listItemClass }} bg-gray-200"><span>{{ $page }}</span></li>
    @else
    <li class="{{ $listItemClass }} "><a href="{{ $url }}"
            class="w-full h-full flex justify-center items-center hover:bg-gray-200">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
    <li class="{{ $listItemClass }} "><a href="{{ $paginator->nextPageUrl() }}" rel="next"
            class="w-full h-full flex justify-center items-center hover:bg-gray-200">&rarr;</a></li>
    @else

    @endif
</ul>