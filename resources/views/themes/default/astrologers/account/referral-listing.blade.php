@extends(themeView().'layout')

@section("title", "Register user and astrologer using referral code | ".config("app.name"))

@push('style')
<style media="screen">
.referral-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 0;
    height: 100%;
}
.referral-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.referral-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
    font-size: 1.1rem;
}
.referral-card-body {
    padding: 20px;
}
.referral-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 4px solid #667eea;
}
.referral-item:last-child {
    margin-bottom: 0;
}
.referral-item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}
.referral-item-date {
    color: #6c757d;
    font-size: 0.9rem;
}
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}
.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #dee2e6;
}
</style>
@endpush

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-0 mtt-7 ast_dash">
        <div class="mb-4">
          <h3 class="mb-0">Register user and astrologer using referral code</h3>
          <p class="text-muted">Users and astrologers who registered using your referral code: <strong>{{ $astrologer->referral_code }}</strong></p>
        </div>

        <div class="row">
          <!-- Astrologers Section -->
          <div class="col-md-6 mb-3 mb-md-0">
            <div class="card referral-card border-n">
              <div class="referral-card-header">
                <i class="bi bi-person-badge"></i> Astrologer
              </div>
              <div class="referral-card-body">
                @if($referredAstrologers->count() > 0)
                  @foreach($referredAstrologers as $referredAstrologer)
                    <div class="referral-item">
                      <div class="referral-item-name">
                        {{ $referredAstrologer->firstname ?? 'N/A' }} {{ $referredAstrologer->lastname ?? '' }}
                      </div>
                      <div class="referral-item-date">
                        <i class="bi bi-calendar"></i> Registered on: {{ date('d M Y', strtotime($referredAstrologer->created_at)) }}
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    <p>No astrologers have registered using your referral code yet.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- Users Section -->
          <div class="col-md-6">
            <div class="card referral-card border-n">
              <div class="referral-card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="bi bi-people"></i> User
              </div>
              <div class="referral-card-body">
                @if($referredUsers->count() > 0)
                  @foreach($referredUsers as $referredUser)
                    <div class="referral-item" style="border-left-color: #f5576c;">
                      <div class="referral-item-name">
                        {{ $referredUser->name ?? 'N/A' }}
                      </div>
                      <div class="referral-item-date">
                        <i class="bi bi-calendar"></i> Registered on: {{ date('d M Y', strtotime($referredUser->created_at)) }}
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    <p>No users have registered using your referral code yet.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
@endpush
