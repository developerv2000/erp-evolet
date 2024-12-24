@props(['crumbs'])

<ul class="breadcrumbs">
    @foreach ($crumbs as $crumb)
        <li class="breadcrumbs__item">
            @if ($crumb['link'])
                <a href="{{ $crumb['link'] }}" class="breadcrumbs__item-link">{{ $crumb['text'] }}</a>
            @else
                {{ $crumb['text'] }}
            @endif
        </li>
    @endforeach
</ul>
