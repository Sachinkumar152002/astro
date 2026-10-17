@extends(themeView().'layout')

@section('title', 'Suggested Remedies | '.config("app.name"))

@section('content')
<section class="account-page-ui dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 mt-5 all_pages pad-ap3">
        <div class="card bg-light mb-3 mt-3">
          <div class="card-body pb-0">
            <div class="row">
              @forelse($suggestedRemedies as $suggestion)
                <div class="col-12 mb-4">
                  <div class="card shadow-sm">
                    <div class="card-header">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <small>{{ date('d M Y, h:i A', strtotime($suggestion['created_at'])) }}</small>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      @if(count($suggestion['products']) > 0)
                        <h6 class="mb-3">Products:</h6>
                        <div class="row">
                          @foreach($suggestion['products'] as $product)
                            <div class="col-md-6 mb-2">
                              <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span style="font-size: 12px;"><strong>{{ $product['title'] }}</strong></span>
                                <span class="text-primary fw-bold">₹{{ number_format($product['price'], 2) }}</span>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      @endif
                      @if(count($suggestion['reports']) > 0)
                        <h6 class="mb-3 mt-3">Reports:</h6>
                        <div class="row">
                          @foreach($suggestion['reports'] as $report)
                            <div class="col-md-6 mb-2">
                              <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span style="font-size: 12px;"><strong>{{ $report['title'] }}</strong></span>
                                <span class="text-primary fw-bold">₹{{ number_format($report['price'], 2) }}</span>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      @endif
                      @if(count($suggestion['pujas'] ?? []) > 0)
                        <h6 class="mb-3 mt-3">Pujas:</h6>
                        <div class="row">
                          @foreach($suggestion['pujas'] as $puja)
                            <div class="col-md-6 mb-2">
                              <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span style="font-size: 12px;"><strong>{{ $puja['title'] }}</strong></span>
                                <span class="text-primary fw-bold">₹{{ number_format($puja['price'], 2) }}</span>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      @endif
                      @if($suggestion['referral_code'])
                        <div class="mt-3 pt-3 border-top">
                          <small class="text-muted">Referral Code: <strong>{{ $suggestion['referral_code'] }}</strong></small>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-12">
                  <div class="no-data text-center">
                    <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                    <h5 class="mt-3 text-dark">No Suggested Remedies Found!</h5>
                    <p class="text-secondary">Remedies suggested by astrologers will be displayed here.</p>
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
