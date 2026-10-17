@if($results->count())
@foreach($results as $index=>$withdrawal)
<tr>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if($withdrawal->astrologer)
      {{$withdrawal->astrologer->display_name ?? ($withdrawal->astrologer->firstname . ' ' . $withdrawal->astrologer->lastname) ?? 'N/A'}}
      <br>
      <small class="text-muted">{{$withdrawal->astrologer->email ?? 'N/A'}}</small>
    @else
      <span class="text-muted">N/A</span>
    @endif
  </td>
  <td>
    <span class="badge bg-info">{{ucfirst($withdrawal->wallet_type)}}</span>
  </td>
  <td>₹{{number_format($withdrawal->referral_amount, 2)}}</td>
  <td>₹{{number_format($withdrawal->commission_amount, 2)}}</td>
  <td><strong class="text-primary">₹{{number_format($withdrawal->total_amount, 2)}}</strong></td>
  <td>
    @if($withdrawal->status == 'processed')
      <span class="badge bg-success">Processed</span>
    @elseif($withdrawal->status == 'approved')
      <span class="badge bg-info">Approved</span>
    @elseif($withdrawal->status == 'rejected')
      <span class="badge bg-danger">Rejected</span>
    @else
      <span class="badge bg-warning">Pending</span>
    @endif
  </td>
  <td>{{$withdrawal->created_at ? $withdrawal->created_at->format('d-m-Y H:i') : 'N/A'}}</td>
  <td class="text-right">
    @if($withdrawal->status == 'pending')
      @if(adminCan('referral_withdrawals', 'update'))
        <a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="approveWithdrawal({{$withdrawal->id}})" title="Approve">
          <i class="bi bi-check-circle"></i>
        </a>
        <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="rejectWithdrawal({{$withdrawal->id}})" title="Reject">
          <i class="bi bi-x-circle"></i>
        </a>
      @endif
    @elseif($withdrawal->status == 'approved')
      @if(adminCan('referral_withdrawals', 'update'))
        <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="processWithdrawal({{$withdrawal->id}})" title="Process">
          <i class="bi bi-cash-stack"></i>
        </a>
      @endif
    @endif
    <a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="viewWithdrawal({{$withdrawal->id}})" title="View Details">
      <i class="bi bi-eye"></i>
    </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif

<script>
function approveWithdrawal(id) {
  if(confirm('Are you sure you want to approve this withdrawal request?')) {
    $.ajax({
      url: '{{route('admin.referral_withdrawals.approve')}}',
      type: 'POST',
      data: {id: id, _token: window.Laravel.csrfToken},
      success: function(response) {
        toastr.success(response.message);
        listTable();
      },
      error: function(response) {
        toastr.error(response.responseJSON.message || 'Error approving withdrawal');
      }
    });
  }
}

function rejectWithdrawal(id) {
  var notes = prompt('Enter rejection reason (optional):');
  $.ajax({
    url: '{{route('admin.referral_withdrawals.reject')}}',
    type: 'POST',
    data: {id: id, admin_notes: notes, _token: window.Laravel.csrfToken},
    success: function(response) {
      toastr.success(response.message);
      listTable();
    },
    error: function(response) {
      toastr.error(response.responseJSON.message || 'Error rejecting withdrawal');
    }
  });
}

function processWithdrawal(id) {
  if(confirm('Are you sure you want to mark this withdrawal as processed?')) {
    $.ajax({
      url: '{{route('admin.referral_withdrawals.process')}}',
      type: 'POST',
      data: {id: id, _token: window.Laravel.csrfToken},
      success: function(response) {
        toastr.success(response.message);
        listTable();
      },
      error: function(response) {
        toastr.error(response.responseJSON.message || 'Error processing withdrawal');
      }
    });
  }
}

function viewWithdrawal(id) {
  // Implement view modal or redirect
  alert('View withdrawal details for ID: ' + id);
}
</script>
