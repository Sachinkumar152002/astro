@extends(themeView().'layout')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}


<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container mt-3 mb-3">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12">
      <div class="card bg-light p-3 mt-3">
        <div class="mt-3 er-table">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-2 mb-md-4 dash-he">Payout History</h4>
          </div>
          <div class="row">
            @forelse($withdrawHistories as $key => $item)
              <div class="col-md-4 col-sm-6 mb-4">
                <div class="earning-card shadow-sm p-3 bg-light rounded k_payout">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="">
                      <h6 class="mb-2 text-dark">₹{{ $item->amount }}</h6>
                      <small class="small">{{ date('d-m-Y h:i a', strtotime($item->created_at)) }}</small>
                    </div>
                    @if($item->status=='done')
                      <span class="fw-bold text-success">Success</span>
                    @elseif($item->status=='pending')
                      <span class="fw-bold text-dark">Pending</span>
                    @else
                      <span class="fw-bold text-danger">Cancelled</span>
                    @endif
                  </div>
                  @if($description = $item->description)
                  <small class="small">{!! $description !!}</small>
                  @endif
                </div>
              </div>
            @empty
              <div class="col-12">
                <div class="no-data text-center">
                  <img src="{{ asset('assets/images/earning.webp') }}" class="nodata">
                  <h5 class="mt-3 text-dark"> No Payout Found!</h5>
                </div>
              </div>
            @endforelse
          </div>


        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection
