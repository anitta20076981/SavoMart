@if ($data == 0)
    <div class="badge badge-light-warning">Pending</div>
@endif
@if ($data == 1)
    <div class="badge badge-light-warning">Cash on Delivery</div>
@endif
@if ($data == 'paid')
    <div class="badge badge-light-success">Paid</div>
@endif
@if ($data == 'failed')
    <div class="badge badge-light-warning">Failed</div>
@endif
