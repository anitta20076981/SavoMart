<nav aria-label="breadcrumb" class="py-3 breadcrumb-wrapper mb-4">
    <div class="row">
        <div class="col-md-6">
            <h5 class="breadcrumbs-title mt-0 mb-1"><span>@yield('title') </span></h5>
            @if(isset($breadcrumbs))
            <ol class="breadcrumb breadcrumb-style2 mb-0">
                @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item {{ !isset($breadcrumb['link']) ? 'active' :''}}">
                    @if((!isset($breadcrumb['permission']) || ((is_array($breadcrumb['permission']) && auth()->user()->canany($breadcrumb['permission'])) || auth()->user()->can($breadcrumb['permission']))) && isset($breadcrumb['link']) &&
                    ($breadcrumb['link'] !== 'javascript:void(0)'))
                    <a href="{{route($breadcrumb['link'])}}">
                        @endif
                        {{$breadcrumb['name']}}
                        @if(isset($breadcrumb['link']))
                    </a>
                    @endif
                </li>
                @endforeach
            </ol>
            @endif
        </div>
        <div class="col-md-6">
            {{ $slot }}
        </div>
    </div>
</nav>