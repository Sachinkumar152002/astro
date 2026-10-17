@extends(themeView().'layout')

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
        <div class="d-flex align-items-center justify-content-between flex-md-nowrap d-flex-wrap d-box earnings-box">

          <div class="dashbord_card mb-2 mb-md-0 d-flex flex-column align-items-center text-center">
          <div class="card_icon ear-card" style="background:#28a745;">
          <!-- <i class="bi fs-4 text bi-headphones text-black "></i> -->
          <img src="/assets/img/chat.png" alt="">
          </div>
          <div class="card_text">
          <h2 id="earning_rupee">Chat Balance</h2>
          <p class="mb-0">₹ {{$totalChatAmount}}</p>
          </div>
          </div>

          <div class="dashbord_card ms-0 ms-md-3 mb-2 mb-md-0 d-flex flex-column align-items-center text-center">
          <div class="card_icon ear-card" style="background:#5bc0de;">
          <!-- <i class="bi fs-4 text bi-headphones text-black "></i> -->
          <img src="/assets/img/phone.png" alt="">
          </div>
          <div class="card_text">
          <h2 id="earning_rupee">Call Balance</h2>
          <p class="mb-0">₹ {{$totalCallAmount}}</p>
          </div>
          </div>

          <div class="dashbord_card ms-0 ms-md-3 mb-2 mb-md-0 d-flex flex-column align-items-center text-center">
          <div class="card_icon ear-card" style="background:#ff4444;">
          <!-- <i class="bi fs-4 text bi-headphones text-black "></i> -->
          <img src="/assets/img/video-camera.png" alt="">

          </div>
          <div class="card_text">
          <h2 id="earning_rupee">Video Balance</h2>
          <p class="mb-0">₹ {{$totalVideoAmount}}</p>
          </div>
          </div>

          <div class="dashbord_card ms-0 ms-md-3 mb-2 mb-md-0 d-flex flex-column align-items-center text-center">
          <div class="card_icon ear-card" style="background:#6f42c1;">
          <!-- <i class="bi fs-4 text bi-headphones text-black "></i> -->
          <img src="/assets/img/savings.png" alt="">

          </div>
          <div class="card_text">
          <h2 id="earning_rupee">Total Revenue</h2>
          <p class="mb-0">₹ {{$totalAmount}}</p>
          </div>
          </div>

          <div class="dashbord_card ms-0 ms-md-3 mb-2 mb-md-0 d-flex flex-column align-items-center text-center">
          <div class="card_icon ear-card" style="background:#8e24aa;">
          <!-- <i class="bi fs-4 text bi-headphones text-black "></i> -->
          <img src="/assets/img/redeem.png" alt="">

          </div>
          <div class="card_text">
          <h2 id="earning_rupee">Redeem Balance</h2>
          <p class="mb-0">₹ {{$withdrawAmount}}</p>
          </div>
          </div>
        </div>
<!-- table-start -->
<div class="card bg-light p-3 mt-3 border-n">

<div class="table-responsive er-table">
  <div class="d-flex justify-content-between align-items-center">
  <h4 class="mb-0 dash-b-heading">Earning Info</h4>
  <div class="d-flex mb-2">
    @php
      $redictToUrl=route('astrologer.withdraws');
      if($_token){
        $redictToUrl=route('astrologer.withdraws', ["_token"=>$_token]);
      }
    @endphp
    <a type="button" class="main_button2" href="{{ $redictToUrl }}">Payouts</a>
    @php
      $canRedeem = false;
    @endphp
    @if($canRedeem)
      <button type="button" class="main_button2 ms-1" data-bs-toggle="modal" data-bs-target="#exampleModalredeem">Redeem</button>
    @endif
  </div>
  </div>
  <div class="container my-4">
    <div class="row">
      @forelse($transitions as $key => $item)
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="earning-card shadow-sm py-3 px-2 bg-light rounded earning_hieght">
            <h6 class="mb-2 text-dark">{{ $item->remark }}</h6>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="small">{{ date('d-m-Y h:i a', strtotime($item->created_at)) }}</small>
              @if($item->action)
                <span class="fw-bold text-success fs-11">+ ₹{{$item->amount}}</span>
              @else
                <span class="fw-bold text-dark fs-11">- ₹{{$item->amount}}</span>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="no-data text-center">
            <img src="{{ asset('assets/images/earning.webp') }}" class="nodata">
            <h5 class="mt-3 text-dark"> No Earning Found!</h5>
            <p class="text-secondary"> Earning history will be displayed here.</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>
</div>
<div class="mt-3">
  {!! $transitions->links('vendor.pagination.bootstrap-5') !!}
</div>
    </div>
    </div>
  </div>
</section>

@if($canRedeem)
  <div class="modal fade redeem-modal" id="exampleModalredeem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <input type="number" placeholder="1000" value="{{ $totalAmount }}" name="" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label> Hold Balance</label>
            <input type="number" placeholder="" name="" value="{{ $hold_withraw_amount }}" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label>Available Balance</label>
            <input type="number" placeholder="" name="" value="{{ $availableAmount }}" class="form-control" disabled>
          </div>
          <div class="form-group col-sm-12 mb-3">
            <label>Redeemed Balance<em class="text-danger"> *</em></label>
            <input type="number" placeholder="{{ 'amount between. ₹'.$minumum_withraw_amount.' - ₹'.$maximum_withraw_amount }}" id="requested_amount"  name="requested_amount" class="form-control">
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
@endif

@endsection
@push('scripts')

@if($canRedeem)
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
        toastr.success(response.message);
      },
      error:function (error){
        if(errors = error.responseJSON.errors){
          for (var key in errors) {
            toastr.error(errors[key][0]);
          }
        }else if(errors = error.responseJSON.message){
          toastr.error(errors);
        }
        $('.submit_btn').prop('disabled', false);
        console.log(error);
      }
    });
    return false;
  }
  </script>
@endif

<script type="text/javascript">
  let _token='{{ $_token }}';
  if(_token!=""){
    $(".pagination a").on("click",function(e){
      e.preventDefault();
      let url = $(this).attr("href");
      url=`${url}&_token=${_token}`;
      window.location.href=url;
    });
  }
</script>
@endpush
