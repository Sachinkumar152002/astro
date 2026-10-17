@extends(themeView().'layout')

@section('title', 'Wallet Histories | '.config("app.name"))

@section('content')
  <section class="account-page-ui dashboad_section">
    <div class="container" id="profile-app">
      <div class="row">
       @include(themeview().'account._sidebar')
       <div class="col-lg-9 col-12 mt-5 mt-md-0 all_pages pad-ap3">
         <div class="row">
           <div class="col-sm-12">
             <div class="card float-shadow-2 p-3 mb-3 wallet-mobile">
               <div class="row align-items-md-center">
                 <div class="col-lg-4 col-md-6 col-7 border-right">
                   <div class="card-wallet1">
                     <div class="icons1">
                       <div class="wallet1" id="wallet">
                         <div class="icon1">
                           <i class="bi bi-credit-card fs-2 text-black"></i>
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
                       <h6 class="text-black">Wallet Balance </h6>
                       <h3 class="text-black pese"><i class="bi bi-currency-rupee"></i>{{$user->wallet}}</h3>
                       {{--<h4>Your last login was <span id="datetime">{{$user->last_logined}}</span> </h4>--}}
                     </div>
                   </div>
                 </div>
                 {{--<div class="col-md-3 col-6 text-start text-md-center">
                   <p class="text-black fs-6 mb-0">Wallet Balance</p>
                   <h3 class="text-uppercase mt-0 text-black fw-bold"><i class="bi bi-currency-rupee"></i>{{$user->wallet}}</h3>
                 </div>--}}
                 <div class="col-lg-8 col-md-6 col-5 text-start text-md-end">
                   <p class=" mt-3">
                     <a href="{{route('user.recharge')}}" class="main_button">
                        Add Money
                     </a>
                   </p>
                 </div>
               </div>
             </div>
           </div>
           <div class="col-sm-12 my-3">
             <h5 class="dash-heading"> Wallet History</h5>
             <div class="card bg-light table_consultation mt-4 mb-3 border-n">
               <div class="container k_wall">
                 <div class="row">
                   @forelse($transitions as $key => $item)
                     <div class="col-md-4 col-sm-6 mb-3">
                       <div class="earning-card earning-card-new  shadow-sm p-3 bg-light rounded border-n">
                         <h6 class="mb-2 text-dark">{{ $item->remark }}</h6>
                         <div class="d-flex justify-content-between align-items-center mb-2">
                           <small class="small">{{ date('d-m-Y h:i a', strtotime($item->created_at)) }}</small>
                           @if($item->action)
                             <span class="fw-bold text-success">+ ₹{{$item->amount}}</span>
                           @else
                             <span class="fw-bold text-danger">- ₹{{$item->amount}}</span>
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
            <div class="col-12">
              {!! $transitions->links('vendor.pagination.bootstrap-5') !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
