@if ($data == 'pending')
    <div class="badge badge-light-info">Pending</div>
@endif
@if ($data == 'processing')
    <div class="badge badge-light-warning">Processing</div>
@endif
@if ($data == 'dispatched')
    <div class="badge badge-light-dark">Dispatched</div>
@endif
@if ($data == 'delivered')
    <div class="badge badge-light-success">Delivered</div>
@endif
@if ($data == 'canceled')
    <div class="badge badge-light-danger">Canceled</div>
@endif
@if ($data == 'rejected')
    <div class="badge badge-light-danger">Rejected</div>
@endif
@if ($data == 'returned')
    <div class="badge badge-light-danger">Returned</div>
@endif
