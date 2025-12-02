@if (isset($data['url']) && $data['url'])
    <a href="{{ $data['url'] }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $data['text'] }}</a>
@else
    {{ $data['text'] }}
@endif
