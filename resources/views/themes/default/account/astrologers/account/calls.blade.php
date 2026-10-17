@extends(themeView().'layout')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-sm-9">
        <div class="card bg-primary text-center text-white  mb-2">
            <div class="row">
                <div class="col-sm-6 col-6 text-left">
                    <p class="text-uppercase pb-0 p-3 mb-0 text-white"> <i class="fa fa-phone-volume"></i> {{$history->total()}} Call Received
                    </p>
                </div>
                <div class="col-sm-6 col-6 ">
                    <p class="text-uppercase text-right text-white pb-0 mb-0 p-3"> <i class="fa fa-inr"></i> {{$totalCharge}}</p>

                </div>
            </div>


        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><i class="fa fa-user"></i> Name</th>
                        <th><i class="fa fa-clock"></i> Call Duation</th>
                        <th><i class="fa fa-calendar-alt"></i> Date and Time</th>
                        <th><i class="fa fa-inr"></i> Call Rate</th>
                        <th><i class="fa fa-inr"></i> My Earning</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $index=>$row)
                    <tr>
                        <td>{{$index+1+(($history->currentPage()-1)*$history->perPage())}}</td>
                        <td>{{$row->user->name}}</td>
                        <td>{{$row->minutes_consumed}} min</td>
                        <td>{{ISTDatetimeFormat($row->created_at)}}</td>
                        <td>{{$row->call_charges}} PM</td>
                        <td>{{$row->astrologer_amount}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
  </div>
@endsection
