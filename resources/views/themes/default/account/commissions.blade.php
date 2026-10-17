@extends(themeView().'layout')

@section("title", "Product Commissions | ".config("app.name"))

@push('style')
<style>
.commission-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    transition: transform 0.2s;
}
.commission-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.commission-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
}
</style>
@endpush

@section('content')
<section class="account-page-ui dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeView().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">
        
        <div class="mb-4 d-flex align-items-start justify-content-between gap-2 flex-wrap">
          <div>
            <h3 class="mb-0"><i class="bi bi-cash-coin"></i> Product Commissions</h3>
            <p class="text-muted mb-0">Commissions earned from product purchases</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-3 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-success">₹{{ number_format((float)($stats['total'] ?? 0), 2) }}</h4>
                <p class="text-muted mb-0">Total Commissions</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-primary">₹{{ number_format((float)($stats['credited'] ?? 0), 2) }}</h4>
                <p class="text-muted mb-0">Credited</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-warning">₹{{ number_format((float)($stats['pending'] ?? 0), 2) }}</h4>
                <p class="text-muted mb-0">Pending</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h4 class="text-info">{{ (int)($stats['total_orders'] ?? 0) }}</h4>
                <p class="text-muted mb-0">Total Orders</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
          <div class="card-body">
            <form method="GET" action="{{ route('user.commissions') }}">
            <div class="row">
              <div class="col-lg-3 col-md-6 mb-2">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id">
                  <option value="">All Categories</option>
                  @if(isset($categories) && $categories->count())
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ (string)request('category_id') === (string)$cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="col-lg-3 col-md-6 mb-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                  <option value="">All Status</option>
                  <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="credited" {{ request('status') === 'credited' ? 'selected' : '' }}>Credited</option>
                  <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-6 mb-2">
                <label class="form-label">Date From</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
              </div>
              <div class="col-lg-2 col-md-6 mb-2">
                <label class="form-label">Date To</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
              </div>
              <div class="col-lg-2 col-md-6 mb-2">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary w-100" type="submit">Apply Filters</button>
              </div>
            </div>
            </form>
          </div>
        </div>

        <!-- Commissions List -->
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="mb-0">Commissions List</h5>
          </div>
          <div class="card-body">
            @if(isset($commissions) && $commissions->count())
              @foreach($commissions as $c)
                @php
                  $status = (string) ($c->status ?? 'pending');
                  $statusBadge = match ($status) {
                    'credited' => 'bg-success',
                    'pending' => 'bg-warning text-dark',
                    'cancelled' => 'bg-secondary',
                    'refunded' => 'bg-danger',
                    default => 'bg-secondary',
                  };

                  $orderId = $c->order_id ?? (optional($c->order)->id);
                  $productTitle = optional(optional($c->orderItem)->product)->title;
                  $categoryName = optional($c->category)->name;
                @endphp

                <div class="commission-card">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-md-2">
                        <strong>Order #{{ $orderId ?? '—' }}</strong>
                        <br>
                        <small class="text-muted">{{ optional($c->created_at)->format('d M Y') }}</small>
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted d-block">Product</small>
                        <strong>{{ $productTitle ?: '—' }}</strong>
                        <br>
                        <small class="text-muted">Category: {{ $categoryName ?: '—' }}</small>
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Order Amount</small>
                        <strong>₹{{ number_format((float)($c->order_amount ?? 0), 2) }}</strong>
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Commission Rate</small>
                        <strong>
                          @if(($c->commission_mode ?? '') === 'PERCENTAGE')
                            {{ number_format((float)($c->commission_rate ?? 0), 2) }}%
                          @else
                            Fixed
                          @endif
                        </strong>
                      </div>
                      <div class="col-md-2">
                        <small class="text-muted d-block">Commission</small>
                        <strong class="text-success">₹{{ number_format((float)($c->commission_amount ?? 0), 2) }}</strong>
                      </div>
                      <div class="col-md-1 text-end">
                        <span class="badge {{ $statusBadge }} commission-badge">{{ ucfirst($status) }}</span>
                        <br>
                        <a href="{{ route('user.commissions.detail', ['id' => $c->id]) }}" class="btn btn-sm btn-outline-primary mt-2">
                          <i class="bi bi-eye"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="mt-3">
                {{ $commissions->links() }}
              </div>
            @else
              <div class="text-center py-5">
                <i class="bi bi-cash-coin" style="font-size: 4rem; color: #dee2e6;"></i>
                <p class="text-muted mt-3">No commissions yet</p>
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
