@foreach ($items as $item)
    @if (!$loop->last)
        <div class="breadcrumb-item active">
            <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
        </div>
    @else
        <div class="breadcrumb-item text-dark">{{ $item['name'] }}</div>
    @endif
@endforeach