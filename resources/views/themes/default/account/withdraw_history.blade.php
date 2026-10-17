@extends(themeView().'layout')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}



  <div class="container mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-sm-9">
      <div class="card bg-light p-3 mt-3">
        <div class="table-responsive mt-3 er-table">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-4">Withdraw History</h4>
          </div>
          <table class="table table-hover table-bordered radius">
            <thead>
              <tr>
                <th>#</th>
                <th><i class="bi bi-person"></i> Amount</th>
                <th><i class="bi bi-calendar-date"></i> Date</th>
                <th><i class="bi bi-clock"></i> Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($withdrawHistories as $key => $item)
                <tr>
                  <td>#</td>
                  <td>₹ {{$item->amount}}</td>
                  <td>{{date('d-m-Y', strtotime($item->created_at))}}</td>
                  <td>₹ {{$item->status}}</td>
                </tr>
              @empty
              <tr>
                <td colspan="7">NO Item</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="modal fade" id="exampleModalredeem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Redeem Balance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modal-close"></button>
        </div>

    <div class="modal-body">
      <form class="" action="" method="post" onsubmit="return submitWithrawRequest(this)">
        <div class="row">
          <div class="form-group col-sm-12 mb-3">
            <label>Total Balance</label>
            <input type="number" placeholder="1000" value="{{$withdrawAmount}}" name="" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label> Hold Balanace</label>
            <input type="number" placeholder="" name="" value="{{$hold_withraw_amount}}" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label>Available Balance</label>
            <input type="number" placeholder="" name="" value="{{$availableAmount}}" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label>Redeemed Balance<em class="text-danger"> *</em></label>
            <input type="number" placeholder="{{'amount between. ₹ '.$minumum_withraw_amount.' - ₹ '.$maximum_withraw_amount }}" id="requested_amount"  name="requested_amount" class="form-control">
            <div class="validation text-danger fs-8" id="requested_amount-validate"></div>
          </div>
          <div class="form-group my-2">
            <button type="submit" class="btn btn-secondary px-4 py-2 profile-save-btn submit_btn" style="height: 100%;">Save</button>
          </div>
        </div>
      </form>
    </div>

    </div>
  </div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">
function submitWithrawRequest(form){
  $('.submit_btn').attr('disabled', 'disabled');
  $('.validation').addClass('d-none');
  let _f = $(form);
  $.ajax({
    method:'POST',
    url: @json(route('astrologer.withdraw.request.submit')),
    data:{
      requested_amount : $('#requested_amount').val(),
    },
    success:function (response){
      $('#requested_amount').val('');
      $('#modal-close').click();
      Swal.fire({
          icon: response.success ? 'success' : 'error',
          title: response.message,
          confirmButtonText: 'Okay'
      });
      if(!response.success){
        $('.submit_btn').removeAttr('disabled');
      }
    },
    error:function (error){
      $('.submit_btn').removeAttr('disabled');
      console.log(error.responseJSON.message)
      if(error.responseJSON && error.responseJSON.message){
        $('#requested_amount-validate').removeClass('d-none')
        $('#requested_amount-validate').text(error.responseJSON.message);
      }
      for (var key in error.responseJSON.errors) {
        $('#'+key+'-validate').removeClass('d-none')
        $('#'+key+'-validate').text(error.responseJSON.errors[key][0]);
      }
      console.log(error);
    }
  });
  return false;
}
</script>
@endpush
