<label class="switch">
    <input class="switch-input update-list-approval" data-url="{{ route('admin_customer_approve') }}" type="checkbox" data-id="{{$data->id}}"
           {{ $data->approve == 1 ? 'checked' : '' }}>
    <span class="switch-label" data-on="Approved" data-off="Unapproved"></span>
    <span class="switch-handle"></span>
</label>
