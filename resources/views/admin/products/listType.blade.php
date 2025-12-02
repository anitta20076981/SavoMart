@switch($data->type)
    @case('configurable_product')
        @php $htmlClass = 'badge badge-light-danger'; @endphp
    @break

    @case('virtual_product')
        @php $htmlClass = 'badge badge-light-warning'; @endphp
    @break

    @case('simple_product')
        @php $htmlClass = 'badge badge-light-info'; @endphp
    @break

    @case('grouped_product')
        @php $htmlClass = 'badge badge-light-success'; @endphp
    @break

    @case('bundle_product')
        @php $htmlClass = 'badge badge-light-primary'; @endphp
    @break

    @case('downloadable_product')
        @php $htmlClass = 'badge badge-light-success'; @endphp
    @break

    @default
        @php $htmlClass = 'badge badge-light-success'; @endphp
@endswitch


<div class="{{ $htmlClass }}">{{ productTypes($data->type) }}</div>
