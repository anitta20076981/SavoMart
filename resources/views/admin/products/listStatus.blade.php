@if ($data['status'] == 'suspend')
<div class="badge badge-light-dark">Suspend</div>
@endif
@if ($data['status'] == 'publish')
<div class="badge badge-light-success">Publish</div>
@endif
@if ($data['status'] == 'draft')
<div class="badge badge-light-info">Draft</div>
@endif
@if ($data['status'] == 'pending')
<div class="badge badge-light-warning">Pending</div>
@endif
@if ($data['status'] == 'rejected')
<div class="badge badge-light-danger">Rejected</div>
@endif
@if ($data['status'] == 'active')
<div class="badge badge-light-success">Active</div>
@endif
@if ($data['status'] == 'inactive')
<div class="badge badge-light-danger">Inactive</div>
@endif