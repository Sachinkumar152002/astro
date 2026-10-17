@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
<div class="container mt-3 mb-3" id="profile-app">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
     <div class="col-md-9 col-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-success p-3 mb-3">
                  <div class="row align-items-center">
                    <div class="col-sm-6 border-right">
                      <div class="pl-3 pt-3 ">
                        <div class="text-white fs-6">Welcome</div>
                        <h3 class="text-white mb-0 fw-bold">{{$user->display_name}}</h3>
                      <div class="text-white small fs-6">Your last login was <span id="datetime">{{$user->last_logined}}</span> </div>
                      </div>
                    </div>
                    <div class="col-sm-3 text-end"></div>

                    <div class="col-sm-3 text-center">
                      <p class="text-white fs-6">Wallet Balance</p>
                      <h3 class="text-uppercase mt-3 text-warning fw-bold"><i class="bi bi-currency-rupee"></i>{{$user->wallet}}</h3>
                      <p class=" mt-3"><a href="{{route('astrologer.recharge')}}" class="btn btn-secondary btn-lg btn-rounded btn-h"><i
                            class="fa fa-rupee"></i>Add Money</a></p>
                    </div>
                  </div>
                </div>

              </div>
          <div class="col-sm-12 my-3">

                <h5><i class="fa fa-coins"></i> Recharge History</h5>

          <div class="card bg-light mt-4 mb-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table  table-bordered recharge-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th><i class="bi bi-cart"></i> Order ID</th>
                      <th><i class="bi bi-calendar-date"></i> Date</th>
                      <th><i class="bi bi-alarm"></i> Time</th>
                      <th><i class="bi bi-boxes"></i> Package</th>
                        <th><i class="bi bi-wallet"></i> Mode</th>
                      <!-- <th><i class="bi bi-percent"></i> Discount</th> -->
                      <th><i class="bi bi-currency-rupee"></i> Debit</th>
                      <th><i class="bi bi-currency-rupee"></i> Credit</th>
                      <!-- <th><i class="bi bi-wallet"></i> Balance</th> -->
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($history as $index=>$item)
                      <tr>
                        <td>{{$index+1+(($history->currentPage()-1)*$history->perPage())}}</td>
                        <td>{{$item->invoice_id}}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y')}}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A')}}</td>
                        <td>{{$item->item_name}}</td>
                        <td>{{ucfirst($item->payment_method)}}</td>
                        <!-- <td>{{$item->discount}}</td> -->
                        <td>{{$item->total_amount}}</td>
                        <td>{{$item->taktime_value}}</td>
                        <!-- <td>{{$item->balance}}</td> -->
                        <td><a href="{{route('astrologer.recharge-print', $item->id)}}" target="_blank"><i class="bi bi-printer text-blue"></i> </a></td>
                      </tr>
                    @empty
                        <tr>
                          <td colspan="8" class="text-center"><img src="/assets/images/no-data.png" alt="No Data" class="no-img"></td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
                {!! $history->links() !!}
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
