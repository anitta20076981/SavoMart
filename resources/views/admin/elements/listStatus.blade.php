@if ($data->status == 0 || $data->status == 'inactive')
    <div class="badge badge-light-danger">Inactive</div>
@endif
@if ($data->status == 1 || $data->status == 'active')
    <div class="badge badge-light-success">Active</div>
@endif
