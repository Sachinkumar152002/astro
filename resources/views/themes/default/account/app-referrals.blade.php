@extends(themeView().'layout')

@section("title", "App Referrals | ".config("app.name"))

@push('style')
<style>
.referral-status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
}
.referral-item-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    transition: transform 0.2s;
}
.referral-item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
@endpush

@section('content')
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeView().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">

        <div class="mb-4 d-flex align-items-start justify-content-between gap-2 flex-wrap">
          <div>
            <h3 class="mb-0"><i class="bi bi-people"></i> App Referrals</h3>
            <p class="text-muted mb-0">Users who signed up using your referral code</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-primary">{{ (int)($stats['total'] ?? 0) }}</h4>
                <p class="text-muted mb-0">Total Referrals</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-success">{{ (int)($stats['credited'] ?? 0) }}</h4>
                <p class="text-muted mb-0">Credited</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-warning">{{ (int)($stats['pending'] ?? 0) }}</h4>
                <p class="text-muted mb-0">Pending</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
          <div class="card-body">
            <form method="GET" action="{{ route('user.referrals.app') }}">
            <div class="row">
              <div class="col-md-4 mb-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                  <option value="">All Status</option>
                  <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="credited" {{ request('status') === 'credited' ? 'selected' : '' }}>Credited</option>
                  <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                  <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>
              <div class="col-md-4 mb-2">
                <label class="form-label">Trigger</label>
                <select class="form-select" name="reward_trigger">
                  <option value="">All Triggers</option>
                  <option value="signup" {{ request('reward_trigger') === 'signup' ? 'selected' : '' }}>Signup</option>
                  <option value="first_purchase" {{ request('reward_trigger') === 'first_purchase' ? 'selected' : '' }}>First Purchase</option>
                </select>
              </div>
              <div class="col-md-4 mb-2">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary w-100" type="submit">Apply Filters</button>
              </div>
              <div class="col-md-12">
                <a href="{{ route('user.referrals.app') }}" class="btn btn-primary btn-sm">Reset</a>
              </div>
            </div>
            </form>
          </div>
        </div>

        <!-- Referrals List -->
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="mb-0">Referrals List</h5>
          </div>
          <div class="card-body">
            @if(isset($referrals) && $referrals->count())
              @foreach($referrals as $r)
                @php
                  $status = (string) ($r->status ?? 'pending');
                  $statusBadge = match ($status) {
                    'credited' => 'bg-success',
                    'pending' => 'bg-warning text-dark',
                    'expired' => 'bg-secondary',
                    'cancelled' => 'bg-danger',
                    default => 'bg-secondary',
                  };

                  $trigger = (string) ($r->reward_trigger ?? 'signup');
                  $triggerLabel = $trigger === 'first_purchase' ? 'First Purchase' : ucfirst($trigger);

                  $referred = $r->referred;
                  $referredName = $referred->name ?? $referred->display_name ?? $referred->firstname ?? '—';
                @endphp

                <div class="referral-item-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-md-3">
                        <small class="text-muted d-block">Name</small>
                        <strong>{{ $referredName }}</strong>
                        @if(!empty($referred?->email))
                          <div class="small text-muted">{{ $referred->email }}</div>
                        @endif
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Trigger</small>
                        <span class="badge bg-info text-dark">{{ $triggerLabel }}</span>
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Reward</small>
                        <strong class="text-success">₹{{ number_format((float)($r->reward_amount ?? 0), 2) }}</strong>
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge {{ $statusBadge }} referral-status-badge">{{ ucfirst($status) }}</span>
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted d-block">Date</small>
                        <small>{{ optional($r->created_at)->format('d M Y') }}</small>
                        @if(!empty($r->order_id))
                          <div class="small text-muted">Order: #{{ $r->order_id }}</div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="mt-3">
                {{ $referrals->links() }}
              </div>
            @else
              <div class="text-center py-5">
                <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                <p class="text-muted mt-3">No referrals found.</p>
                <a href="{{ route('user.refer.earn') }}" class="btn btn-primary">
                  <i class="bi bi-share"></i> Share Your Referral Code
                </a>
              </div>
            @endif

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
@endpush
