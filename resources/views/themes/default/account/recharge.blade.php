
@extends(themeView().'layout')

@section('title', 'Recharge Plans | '.config("app.name"))

@section('content')
<div class="container mt-5 mb-3 px-0 " style="overflow-x:hidden;"   id="recharge-app">
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
  <div class="row mt-4">
    <div class="col-md-8"></div>
    <div class="col-lg-4 col-sm-12 wallet_bal">
      <div class="card-wallet card-wallet2">
        <div class="icons1">
          <div class="wallet1" id="wallet">
            <div class="icon1">
              <i class="bi bi-credit-card fs-2"></i>
            </div>
            <div class="coin coin--animated" style="--coin-to-x: calc(-100px + 24px); --coin-to-y: calc(-105px + 24px); --coin-delay: 0.3s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(-70px + 24px); --coin-to-y: -90px; --coin-delay: 0.1s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(-30px + 24px); --coin-to-y: -125px; --coin-delay: 0s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(10px + 24px); --coin-to-y: -130px; --coin-delay: 0.2s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(30px + 24px); --coin-to-y: -100px; --coin-delay: 0.1s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(70px + 24px); --coin-to-y: -95px; --coin-delay: 0.4s;"></div>
            <div class="coin coin--animated" style="--coin-to-x: calc(100px + 24px); --coin-to-y: -100px; --coin-delay: 0.2s;"></div>
          </div>
        </div>
        <div class="text">
          <h2>Wallet Balance</h2>
          <h1><i class="bi bi-currency-rupee fs-4"></i>{{$user->wallet}}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-12 d-lg-flex mobile-flex mt-3 mt-md-0">
      <div class="col-lg-8 col-md-12 mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between px-2 px-lg-0 mobile_view">
              <div class="">
                <h5 class="mt-2">Add Money to Wallet</h5>
                <p>Select from the available recharge packs given below</p>
              </div>
              <div class="">
                <a href="{{route('user.recharge-history')}}"> <button class="main_button_default">Recharge History</button></a>
              </div>
            </div>
          </div>
        </div>
          <div class="row g-2 all_wallet">
              <div class="col-lg-2 col-md-2 col-4" v-for="(plan, index) in plans" :key="plan.id">
                <div class="card  mb-3 new_wallet_card" @click="selectedplan = index; removeCouponCode()" :class="{'bg-warning': plan == selectedplan, 'active': index == selectedplan}">
                  <div class="card-body text-black new_recharge p2">
                    <h6 class="card-title k_card_title">@{{ plan.name }}</h6>
                    <h5 class="k_rupee"><i class="bi bi-currency-rupee"></i>@{{ plan.amount.toFixed(2) }}</h5>
                    <div v-if="index == selectedplan" class="selected-indicator"></div>
                  </div>
                  <div class=" my-badge1 new_footer">
                     @{{ plan.from_date && new Date(plan.from_date).getTime() <= new Date().getTime() && new Date(plan.to_date).getTime() >= new Date().getTime() && (((plan.talk_amount - plan.amount) / plan.amount) * 100) > 0 ? ((((plan.talk_amount - plan.amount) / plan.amount) * 100).toFixed(2)) + '% Extra' : '' }}
                   </div>
                </div>
              </div>
          </div>
      </div>
      <div class="col-lg-4 col-12 width-100-mobile m-1 mt-2">

         <div class="card bg-white p-3 border-0 mx-2 radius-8 bill_card ms-3">
          <div class="inside_bill" >
             <table class="table total_box bg-transparent table-borderless" >
               <thead>
                 <tr>
                   <th scope="col">Bill Summary</th>
                   <th class="text-end" scope="col">@{{myPlan.name}}</th>
                 </tr>
               </thead>
               <tbody>
                <tr>
                 <td scope="row">Recharge Amount</td>
                 <td class="text-end"><i class="bi bi-currency-rupee"></i> @{{myPlan.amount.toFixed(2)}} </td>
               </tr>
                <tr class="d-none">
                 <td scope="row">Coupon Applied</td>
                 <td class="text-end">- <i class="bi bi-currency-rupee"></i> <span class="coupon_amount_txt"></span> </td>
               </tr>
               <tr class="">
                 <td scope="row">GST charges (18.0%)</td>
                <td class="text-end">
                    <i class="bi bi-currency-rupee"></i> @{{((myPlan.amount-this.coupon.discount) * 0.18).toFixed(2)}}
                </td>
              </tr>
              <tr>
                <td colspan="3">
                <div class="coupon-box d-flex align-items-center p-2 rounded shadow-sm">
                  <div class="coupon-icon d-flex justify-content-center align-items-center me-3">
                  <i class="bi bi-tag text-success fs-5"></i>

                  </div>
                  <div class="coupon-input d-flex w-100">
                    <input type="hidden" name="coupon_amount" class="form-control coupon_amount" placeholder="Coupon Amount">
                    <input type="hidden" id="coupon_id" name="coupon_id" class="form-control">
                    <input type="hidden" id="coupon_type" name="coupon_type" value="order" class="form-control">

                    <input type="text" id="coupon_code" name="coupon_code" class="form-control me-2" placeholder="Enter coupon code">
                    <button class="main_button" type="button" onclick="applyCouponCode('recharge')" id="coupon-apply-btn">Apply</button>
                    <button class="main_button d-none bg-danger" type="button" onclick="removeCouponCode()" id="coupon-remove-btn">Remove</button>
                  </div>
                </div>
              </td>
             </tr>
              <tr style="border-bottom:none;">
               <th class="" scope="col">Total Amount</th>
               <th class="text-end" scope="col">
                 <small>
                   <i class="bi bi-currency-rupee"></i>
                   @{{((myPlan.amount-this.coupon.discount)+(myPlan.amount-this.coupon.discount)*0.18).toFixed(2)}}
                 </small>
               </th>
              </tr>
              {{--<tr>
               <th class="" scope="col">Get</th>
               <th class="text-end" scope="col">
                 <small>
                   <i class="bi bi-currency-rupee"></i>
                   @{{ isPlanActive(myPlan) ? myPlan.talk_amount : myPlan.amount}}
                 </small>
               </th>
              </tr>--}}
               </tbody>
             </table>

             <div class="col-12 mb-3 mt-3 text-center">
            <button class="main_button" v-on:click="confirmAndpay">Proceed to Pay</button>
            </div>
            <div class="extra-offer-box d-flex align-items-center justify-content-between mt-2">
              <div class="d-flex align-items-center">
                <span class="offer-icon me-3"><i class="bi bi-tag"></i></span>
                <div>
                  <span class="offer-text">₹@{{ isPlanActive(myPlan) ? myPlan.talk_amount : myPlan.amount}} cashback in wallet after recharge</span>
                </div>
              </div>
              <!-- <button type="button" class="btn-close" aria-label="Close" id="cls_btn"></button> -->
         </div>
        </div>
      </div>
    </div>
  </div>
  <!-- tag-section-start -->
  <div class="container-fluid py-2 py-md-4">
    <div class="container mb-4">
      <div class="row align-items-center gx-0 mx-2">
        <div class="col-lg-3 col-sm-3"></div>
        <div class="col-lg-6 col-sm-6">
          <div class="heading_main">
            {{--<img src="{{asset('assets/img/icons/heading_icon.svg')}}">--}}
            <h2>100% Secure Payment</h2>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 mb-2 mb-md-0"></div>
      </div>
      <div class="add-img-main d-flex">
        <div class="row">
          <div class="col-md-12 p-0">
            <div class=" mt-2 mt-md-0">
              <img src="{{asset('/assets/img/secure-payment.jpg')}}" alt="secure-payment" style="width:100%;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var rzp1 = null;
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
            uid : '',
            loader: false
        },
        mounted: function(){
            this.loader = true;
        },
        methods:{
          removeCouponCode(){
            $('#coupon_code').val('');
            applyCouponCode('recharge', 0);
            $('#coupon-remove-btn').addClass('d-none');
            $('#coupon-apply-btn').removeClass('d-none');
            $('#coupon_code').prop('readonly', false);
          },
          confirmAndpay(){
            let that = this;
            axios.post('{{route('user.recharge')}}', {
              coupon_code: that.coupon_code,
              selectedplan: that.myPlan.id,
              uid: that.uid,
              coupon_code: couponCode,
            }).then(function(response){
              that.uid = response.data.uid;
              let message = response.data.message;
              if(message){
                toastr.success(message);
              }
              let redirectTo = response.data.redirectTo;
              if(redirectTo){
                window.location.href = redirectTo;
              }else{
                rzp1 = new Razorpay(response.data.request);
                rzp1.open();
              }
            }).catch((response)=>{ console.log(response); })
          },
          isPlanActive(plan) {
            const today = new Date().toISOString().split('T')[0];
            const fromDate = new Date(plan.from_date).toISOString().split('T')[0];
            const toDate = new Date(plan.to_date).toISOString().split('T')[0];
            return fromDate <= today && toDate >= today;
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
    });
    @if($option)
      $('#rechargemodal').modal('show')
        @if($option=='thankyou')
          setTimeout(()=>{
              window.location.href= '{{route('user.recharge-history')}}'
          }, 3000);
        @endif
    @endif
    $( document ).ready(function() {
      $('#cls_btn').on('click', function(){
        console.log('dnksdn');
        const box = $(this).closest('.extra-offer-box');
        box.addClass('fade-out');
        setTimeout(() => {
          box.slideUp(300, function(){
            $(this).remove();
          });
        }, 1000);
      });
  });
</script>
<script type="text/javascript">
  function applyCouponCode(type, error=1){
    couponCode = '';
    $('#coupon-apply-btn').prop('disabled', true)
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
          $('#coupon-apply-btn').prop('disabled', false)
          if(response.success){
            toastr.success(response.message);
            $('.coupon_amount_txt').text(response.discount);
            $('.coupon_amount_txt').parent().parent().removeClass('d-none');
            rechargeApp.coupon.discount = response.discount;
            $('#coupon_id').val(response.coupon_code);
            couponCode = response.coupon_code;
            $('#coupon-remove-btn').removeClass('d-none');
            $('#coupon-apply-btn').addClass('d-none');
            $('#coupon_code').prop('readonly', true);
          }
      },
      error: function(xhr){
        $('#coupon-apply-btn').prop('disabled', false)
        if(error){
          let errors = xhr.responseJSON.errors;
          if(errors){
            for(let key in errors){
              toastr.error(errors[key][0]);
            }
          }else{
            let message = xhr.responseJSON.message;
            if(message){
              toastr.error(message);
              return false;
            }
          }
        }
      }
    });
  }

  function removeCouponCode(){
    $('#coupon_code').val('');
    applyCouponCode('recharge', 0);
    $('#coupon-remove-btn').addClass('d-none');
    $('#coupon-apply-btn').removeClass('d-none');
    $('#coupon_code').prop('readonly', false);
  }



</script>
@endpush
