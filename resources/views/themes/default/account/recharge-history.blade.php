@extends(themeView().'layout')

@section('title', 'Recharge Histories | '.config("app.name"))

@section('content')
  <section class="dashboad_section">
    <div class="container" id="profile-app">
      <div class="row">
       @include(themeview().'account._sidebar')
       <div class="col-sm-12 col-lg-9 mt-5 mt-md-0 all_pages pad-ap3">
         <div class="row">
           <div class="col-sm-12">
             <div class="card float-shadow-2 p-3 mb-3 wallet-mobile">
               <div class="row align-items-center">
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
                       {{--<h6 class="text-black">Welcome </h6>--}}
                       {{--<h3 class="text-black">{{$user->name}}</h3>--}}
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
         </div>
         <div class="row">
             <h5 class="dash-heading mb-3"> Recharge History</h5>
             {{--<div class="card bg-light table_consultation mt-4 mb-3">
               <div class="card-body">
                 <div class="table-responsive">
                   <table class="table  table-bordered recharge-table">
                     <thead>
                       <tr>
                         <th>#</th>
                         <th>Recharge Number</th>
                         <th>Date Time</th>
                         <th>Plan</th>
                         <th>Mode</th>
                         <th>Debit</th>
                         <th>Credit</th>
                         <th>Invoice</th>
                       </tr>
                     </thead>
                     <tbody>
                        @forelse ($history as $index=>$item)
                          <tr>
                            <td>{{$index+1+(($history->currentPage()-1)*$history->perPage())}}</td>
                            <td>{{$item->my_txn_id}}</td>
                            <td>{{ date("d M Y h:i A", strtotime($item->created_at)) }}</td>
                            <td>{{$item->item_name}}</td>
                            <td>{{ucfirst($item->payment_method)}}</td>
                            <td>{{$item->total_amount}}</td>
                            <td>{{$item->taktime_value}}</td>
                            <td>
                              @if(invoice('recharge', $item->id))
                                <a href="{{route('user.invoice', ['recharge', $item->id])}}" target="_blank">
                                  <i class="bi bi-printer text-blue"></i>
                                </a>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="8" class="text-center"><img src="/assets/images/w-data.webp" alt="No  Transaction Found" class="nodata">
                              <h5 class="mt-3 text-dark no-img-h"> No  Transaction Found!</h5>
                              <p class="text-secondary">Balance and transaction history will be displayed here.</p>
                            </td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                    {!! $history->links() !!}
                  </div>
                </div>
              </div>--}}
              @forelse ($history as $index=>$item)
                <div class="col-md-4 mb-2">
                  <div class="earning-card  p-2  rounded k_earning border-n bg-white">
                   <div class="d-flex justify-content-between align-items-center mb-2">
                     <span class="badge k_bg_badge">#{{$index+1+(($history->currentPage()-1)*$history->perPage())}}</span>
                     <span class="text-muted small">{{ date("d M Y h:i A", strtotime($item->created_at)) }}</span>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                   <span><i class="bi bi-phone"></i> Recharge Number</span>
                   <span class="text-dark fw-bold">{{$item->my_txn_id}}</span>
                 </div>

                 <div class="d-flex justify-content-between mb-2">
                   <span><i class="bi bi-card-list"></i> Plan</span>
                   <span class="text-dark fw-bold">{{$item->item_name}}</span>
                 </div>

                 <div class="d-flex justify-content-between mb-2">
                   <span><i class="bi bi-credit-card-2-front"></i> Mode</span>
                   <span class="text-black fw-bold">{{ucfirst($item->payment_method)}}</span>
                 </div>

                   <div class="d-flex justify-content-between mb-2">
                     <span><i class="bi bi-currency-rupee"></i> Debit</span>
                     <span class="text-danger fw-bold">{{$item->total_amount}}</span>
                   </div>
                   <div class="d-flex justify-content-between pt-2">
                     <span><i class="bi bi-currency-rupee"></i> Credit</span>
                     <span class="text-success fw-bold">{{$item->taktime_value}}</span>
                  </div>
                    @if(invoice('recharge', $item->id))
                     <div class="d-flex justify-content-between pt-2">
                       <span><i class="bi bi-lightning-charge"></i> Action</span>
                       <a href="{{route('user.invoice', ['recharge', $item->id])}}" target="_blank">
                         <i class="bi bi-printer text-blue"></i>
                       </a>
                     </div>
                    @endif
                    </div>
                    </div>
                    @empty
                    <div class="">
                      <h5 class="mt-3 text-dark no-img-h"> No  Transaction Found!</h5>
                      <p class="text-secondary">Balance and transaction history will be displayed here.</p>
                    </div>
              @endforelse
              <div class=" mt-2">
                {!! $history->links() !!}
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
