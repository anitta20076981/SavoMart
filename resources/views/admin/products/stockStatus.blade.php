@if ($data['stock_status'] == 'outofstock')
    <div class="badge badge-light-danger">Out Of Stock</div>
@endif
@if ($data['stock_status'] == 'instock')
    <div class="badge badge-light-success">In-Stock</div>
@endif
@if ($data['stock_status'] == 'lowstock')
    <div class="badge badge-light-warning">Low Stock</div>
@endif
