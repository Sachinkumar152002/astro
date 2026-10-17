@extends('layouts.app')
@push('styles')
<link href="{{asset('css/pricetable.css')}}" rel="stylesheet">
@endpush
@section('content')
<section id="page-header3">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center animated wow fadeInUp">
        <h2 class="title-1 text-uppercase">Subscribe the Plan</h2>
      </div>
    </div>
  </div>
</section>
<section class="emp-details">
  <div class="container">
    <div class="table-responsive">
      <div class="membership-pricing-table">
        <table class="table">
          <tbody>
            <tr>
              <td>
                <ul class="list-group">
                  <li class="list-group-item min-height">&nbsp;</li>
                  <li class="list-group-item"><span class="mid-align">Job Posting</span></li>
                  <li class="list-group-item"><span class="mid-align">Resume Downloads</span></li>
                  <li class="list-group-item"><span class="mid-align">Validity</span></li>
                  <li class="list-group-item">&nbsp;</li>
                </ul>
              </td>
              @if($plans)
              @foreach($plans as $k=>$plan)
              <td>
                <ul class="list-group">
                  <li class="list-group-item plan-header-green" style="background-color: {{$plan->color_name}}">
                    @if($plans->count()==$k+1)
                    <span class="recommended-plan-ribbon">RECOMMENDED</span>
                    @endif
                    <span class="pricing-plan-name">{{$plan->name}}</span>
                    <span class="pricing-plan-price"><sup><i class="fa fa-inr"></i></sup>{{number_format($plan->price)}}<span></span></span>
                  </li>
                  <li class="list-group-item"><span class="icon-yes fa fa-check mid-align"></span></li>
                  <li class="list-group-item">
                      @if($dwn = $plan->downloads)
                        <span class="mid-align">{{$dwn}}</span>
                      @else
                      <span class="icon-no glyphicon glyphicon-remove-circle mid-align"></span>
                      @endif
                  </li>
                  <li class="list-group-item"><span class="mid-align">{{$plan->duration}} day{{$plan->duration>1?'s':''}}</span></li>
                  <li class="list-group-item">
                     @if(Auth::guard('employer')->check())
                      @if(Auth::guard('employer')->user()->checkAlreadyFree($plan->id, Auth::guard('employer')->user()->id))
                          <p class="btn btn-silver" style="background-color: {{$plan->color_name}}">Already Subscribed</p>
                      @else
                      <a href="{{route('cart.item.addplan', [$plan->id])}}"  class="btn btn-silver" style="background-color: {{$plan->color_name}}">Subscribe</a>
                      @endif
                    @else
                    <a href="javascript:void(0)" onclick="openModal('registration')" class="btn btn-silver" style="background-color: {{$plan->color_name}}">Subscribe</a>
                    @endif

                </li>
                </ul>
              </td>
              @endforeach
              @endif
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
</section>
@endsection