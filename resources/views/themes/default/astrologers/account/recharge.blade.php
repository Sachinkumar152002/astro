@extends(themeView().'layout')
@section('title', 'Recharge Pack')
@section('keywords', '')
@section('description','')

@section('content')
<div class="container mt-3 mb-3 px-0" id="recharge-app">
  @if($option)
    <div class="container mb-3">
      <div class="modal fade" id="rechargemodal" tabindex="-1" role="dialog" aria-labelledby="rechargemodalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card">
                <div class="card-body pt-3 pb-3 border">
                    @if($option=='thankyou')
                    <h4 class="text-success text-center">Thank you!, your recharge is successfully done</h4>
                    @elseif($option=='failed')
                    <h4 class="text-danger text-center">Oops!, your recharge is not completed</h4>
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
    @endif
    <div class="row">

      <div class="col-sm-12 d-flex mobile-flex">
        <div class="col-sm-8 mt-2">
          <div class="row">
            <div class="col-sm-12">
              <div class="card balance p-3 btn-bg mb-3">
                <div class="row align-items-center">
                    <div class="col-7 d-flex gap-2 align-items-center">
                        <span class="bg-wallete rounded-circle p-3 d-flex justify-content-center align-items-center">
                        <i class="bi bi-wallet text-white fs-5"></i>
                        </span>
                        <h5 class="fs-5">Wallet Balance</h5>
                    </div>
                    <div class="col-5 text-end fw-bold fs-5">
                    <i class="bi bi-currency-rupee fs-4"></i><span class="fs-4">{{$user->wallet}}</span>
                    </div>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="">
                  <h5 class="mt-3">Add Money to Wallet</h5>
                  <p>Select from the available rechage packes given below</p>
                </div>
                <div class="">
                  <a href="{{route('user.recharge-history')}}"> <button class="btn btn-secondary">Recharge History</button></a>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-sm-3 mb-3" v-for="(plan, index) in plans" :key="plan.id">
                  <div class="card rounded" @click="selectedplan = index; applyCouponCode()">
                   <div class="my-badge">50% Extra</div>
                    <div class="card-body text-center recharge-card" :class="{'bg-warning': plan == selectedplan, 'active': index == selectedplan}">
                        <h6 class="fs-6 fw-bold text-capitalize"><i class="fa fa-coins"></i> @{{ plan.name }}</h6>
                        <h4 class="mb-3 fs-5 mt-3"><i class="bi bi-currency-rupee"></i>@{{ plan.special_amount?plan.special_amount.toFixed(2):plan.amount.toFixed(2) }}</h4>
                        <div v-if="index == selectedplan" class="selected-indicator"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="col-sm m-2">
         <div class="card px-2 mb-3">
          <div class="table table-responsive rounded mt-4" >
             <table class="table table-transparent" >
               <thead>
                 <tr>
                   <th scope="col">Bill Summary</th>
                   <th class="text-end" scope="col"><small>@{{myPlan.name}}</small></th>
                 </tr>
               </thead>
               <tbody>
                <tr >
                 <td scope="row">Recharge Amount</td>
                 <td class="text-end"><i class="bi bi-currency-rupee"></i> @{{((myPlan.special_amount>0?myPlan.special_amount:myPlan.amount)).toFixed(2)}} </td>
               </tr>
                <tr class="d-none">
                 <td scope="row">Coupon Applied</td>
                 <td class="text-end">- <i class="bi bi-currency-rupee"></i> <span class="coupon_amount_txt"></span> </td>
               </tr>
               <tr class="">
                 <td scope="row">GST charges (18.0%)</td>
                <td class="text-end">
                    <i class="bi bi-currency-rupee"></i> @{{((myPlan.special_amount>0?myPlan.special_amount:myPlan.amount-this.coupon.discount) * 0.18).toFixed(2)}}
                </td>
              </tr>
              <tr>
                <td>
                  <input type="hidden" id="" name="coupon_amount" class="form-control coupon_amount" placeholder="Coupon Amount">
                  <input type="hidden" id="coupon_id" name="coupon_id" class="form-control">
                  <input type="hidden" id="coupon_type" name="coupon_type" value="order" class="form-control">
                  <input type="text" id="coupon_code" name="coupon_code" class="form-control" placeholder="Coupon code">
              </td>
              <td>
                <button class="btn btn-bg" type="button" onclick="applyCouponCode('recharge')" id="coupon-apply-btn">Apply</button>
              </td>
              </tr>
              <tr class="coupon-validate" id="coupon_code_error">
                <td colspan="2" class="fs-8 text-danger"></td>
              </tr>
              <tr>
               <th scope="col">To Pay</th>
               <th class="text-end" scope="col">
                 <small>
                   <i class="bi bi-currency-rupee"></i>
                   @{{((myPlan.special_amount>0?(myPlan.special_amount-this.coupon.discount):(myPlan.amount-this.coupon.discount))+(myPlan.special_amount>0?(myPlan.special_amount-this.coupon.discount):(myPlan.amount-this.coupon.discount))*0.18).toFixed(2)}}
                 </small>
               </th>
              </tr>
               </tbody>
             </table>
             <div class="col-12 mb-3 mt-3">
    <button class="btn btn-secondary btn-lg w-100 btn-rounded" v-on:click="confirmAndpay">Proceed to Pay</button>
    </div>
         </div>
        </div>
      </div>
</div>
</div>
  <div class="modal fade" id="recharge-balance" data-backdrop="static" data-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        <div class="modal-body text-center p-5">
            <form action="{{route('astrologer.recharge')}}" method="post">
                @csrf
                <input type="hidden" :value="myPlan.id" name="selectedplan">
                <input type="hidden" :value="coupon_code" name="coupon_code">
                <input type="submit" value="Confirm to pay" class="btn btn-warning">
            </form>

        </div>
    </div>
</div>
</div>
<!-- tag-section-start -->
<div class="container-fluid bg-light py-4">
  <div class="container mb-4">
    <h2 class="text-center heading mb-3">100% SECURE PAYMENT</h2>
    <div class="add-img-main d-flex">
      <div class="row">
        <div class="col-md-2 p-0">
          <div class="im-box">
            <img src="{{asset('/assets/images/secured.jpg')}}" alt="secured">
          </div>
        </div>
      <div class="col-md-2 p-0">
      <div class="im-box">
      <img src="{{asset('/assets/images/ssl.jpg')}}" alt="ssl">
      </div>
      </div>
      <div class="col-md-2 p-0">
      <div class="im-box">
      <img src="{{asset('/assets/images/verified-astrologer.jpg')}}" alt="verified-astrologer">
      </div>
      </div>
      <div class="col-md-2 p-0">
      <div class="im-box">
      <img src="{{asset('/assets/images/visa.jpg')}}" alt="visa">
      </div>
      </div>
      <div class="col-md-2 p-0">
      <div class="im-box">
      <img src="{{asset('/assets/images/razorpay.jpg')}}" alt="razorpay">
      </div>
      </div>
      <div class="col-md-2 p-0">
      <div class="im-box">
      <img src="{{asset('/assets/images/paytm.jpg')}}" alt="paytm">
      </div>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var totalAmount = 0;
var plan_id = 0;
var couponCode = '';
var planss = @json($plans);
if(planss.length){
  plan_id = planss[0].id;
  totalAmount = planss[0].special_amount?planss[0].special_amount:planss[0].amount;
}
    var rechargeApp = new Vue({
        el : '#recharge-app',
        data: {
            plans : {!! $plans !!},
            selectedplan : 0,
            coupon : {discount:0, fixed_perc: 0, status: 0},
            coupon_code: '{{session('coupon_code')}}',
            uid : ''
        },
        mounted: function(){
            if(this.coupon_code!=''){
              this.applyCouponCode();
            }
        },
        methods:{
          applyCouponCode(){
              let that = this;
            axios.post('{{route('astrologer.coupon.apply')}}', {
                code: this.coupon_code,
                plan: that.myPlan.id,
            }).then(function(response){
                if(response.data.status==1){
                  toastr.success(response.data.message);
                }else{toastr.error(response.data.message);}
                that.coupon = response.data;
            }).catch((response)=>{ console.log(response); })
          },
          removeCouponCode(){
            this.coupon_code = '';
            this.coupon = {discount:0, fixed_perc: 0, status: 0};
          },
          confirmAndpay(){
            let that = this;
            axios.post('{{route('astrologer.recharge')}}', {
                coupon_code: that.coupon_code,
                selectedplan: that.myPlan.id,
                uid: that.uid,
                user_type: 'astrologer',
                coupon_code: couponCode,
            }).then(function(response){
              that.uid = response.data.uid;
              var rzp1 = new Razorpay(response.data.request);
              rzp1.open();
            }).catch((response)=>{ console.log(response); })
          }
        },
        computed:{
            myPlan: function(){
              return this.plans[this.selectedplan];
            },
            discount: function(){
                if(this.coupon.discount>0 && this.coupon.status>0){
                  if(this.coupon.fixed_perc==0 && this.coupon.discount>=this.myPlan.amount) return this.this.myPlan.amount;
                  else if(this.coupon.fixed_perc==0 && this.coupon.discount<=this.myPlan.amount) return this.coupon.discount;
                  else if(this.coupon.fixed_perc==1) return (this.myPlan.amount*this.coupon.discount)/100;
                }
                return 0;
            }
        }
    })
    @if($option)
      $('#rechargemodal').modal('show')
        @if($option=='thankyou')
          setTimeout(()=>{
              window.location.href= '{{route('astrologer.recharge-history')}}'
          }, 3000);
        @endif
    @endif
    $( document ).ready(function() {
      // $('#header-nav').addClass('d-none');
      // $('#footer-nav').addClass('d-none');
  });
</script>
<script type="text/javascript">
  function applyCouponCode(type){
    couponCode = '';
    $('#coupon-apply-btn').attr('disabled', 'disabled')
    $('.coupon-validate').addClass('d-none');
    $('.coupon_amount_txt').parent().parent().addClass('d-none');
    $('#coupon_id').val('');
    $('.coupon_amount_txt').text('');
    rechargeApp.coupon.discount = 0;
    $.ajax({
        type: 'POST',
        url: "{{ route('apply.coupon') }}", // Update with your route name
        data: {
          type:type,
          plan_id:rechargeApp.plans[rechargeApp.selectedplan].id,
          coupon_code:$('#coupon_code').val(),
        },
        success: function(response) {
          $('#coupon-apply-btn').removeAttr('disabled');
          if(response.success){
            $('#coupon_code_error').removeClass('d-none').find('td').text(response.message).removeClass('d-none');
            $('.coupon_amount_txt').text(response.discount);
            $('.coupon_amount_txt').parent().parent().removeClass('d-none');
            rechargeApp.coupon.discount = response.discount;
            $('#coupon_id').val(response.coupon_code);
            couponCode = response.coupon_code;
          }
      },
      error: function(xhr) {
        $('#coupon-apply-btn').removeAttr('disabled');
        if(xhr.responseJSON.message){
          $('#coupon_code_error').removeClass('d-none').find('td').text(xhr.responseJSON.message);
          return false;
        }
          var errors = xhr.responseJSON.errors;
          for(let key in errors){
            $('#'+key+"_error").removeClass('d-none').find('td').text(errors[key][0]);
          }
      }
    });
  }
</script>
@endpush
