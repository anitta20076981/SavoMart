 @if ($data['status'] == 'pending')
     <div class="badge badge-light-warning">Pending</div>
 @endif
 @if ($data['status'] == 'confirmed')
     <div class="badge badge-light-info">Confirmed</div>
 @endif
 @if ($data['status'] == 'completed')
     <div class="badge badge-light-success">Completed</div>
 @endif
 @if ($data['status'] == 'rejected')
     <div class="badge badge-light-danger">Rejected</div>
 @endif
