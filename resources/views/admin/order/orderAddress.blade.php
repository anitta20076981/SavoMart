<div class="row row-cols-1 row-cols-xl-2 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-400px overflow-scroll">
    <span class="w-100 text-muted d-none">Select any saved address or add new address.</span>
    <?php $i = 0; ?>
    @foreach ($customerAddress as $address)
        <div class="col my-2">
            <div class="d-flex align-items-center border border-dashed border-primary p-3 rounded bg-light-primary order-address-cont">
                <div class="symbol symbol-25px">
                    <input class="form-check-input radio-btn customer-address-radio" type="radio" name="{{ $address_type }}_choice" value="{{ $address->id }}" data-address='@json($address)' @if ($i == 0) checked @endif>
                </div>
                <div class="ms-5">
                    <div class="fw-semibold fs-7">
                        {{ $address->name }} <br>
                        {{ $address->street_address }} {{ $address->city }}, <br>
                        {{ $address->state ? $address->state->name : '' }}, {{ $address->postel_code }}
                        Ph: {{ $address->contact }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="col my-2">
    <button type="button" class="btn btn-sm btn-light-primary new-address-btn">
        <span class="svg-icon svg-icon-2">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
            </svg>
        </span>
        Add New Address
    </button>
</div>
