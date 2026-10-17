@extends(themeView().'layout')

@section("title", "Earnings Dashboard | ".config("app.name"))

@push('style')
<style>
.earnings-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: transform 0.2s;
}
.earnings-card:hover {
    transform: translateY(-5px);
}
.earnings-icon {
    font-size: 3rem;
    opacity: 0.8;
}
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
}
.stat-card.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}
.stat-card.info {
    background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%);
}
.recent-transaction {
    border-left: 4px solid #667eea;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.ledger-list-head {
    font-size: 12px;
    color: #6c757d;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.ledger-row {
    border: 1px solid #eef0f2;
    background: #ffffff;
    border-radius:0px;
    padding: 12px 14px;
    margin-bottom:-1px;
}
.ledger-row:hover {
    border: 1px solid #eef0f2;
    background: #f9f9f9;
    border-radius:0px;
    padding: 12px 14px;
    margin-bottom:-1px;
}

.ledger-row .badge {
    font-weight: 700;
}

.ledger-amount {
    font-size: 16px;
    font-weight: 800;
}

.earnings-section {
    background: #f2f2f2;
}

.ledger-cols {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}



</style>
@endpush

@section('content')
<section class="dashboad_section earnings-section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeView().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">

        <div class="mb-4 d-flex align-items-start justify-content-between gap-2 flex-wrap">
          <div>
            <h3 class="mb-0"><i class="bi bi-graph-up"></i> Earnings Dashboard</h3>
            <p class="text-muted mb-0">Track your referral earnings and commissions</p>
          </div>
          <a href="{{ route('user.refer.earn') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Go Back
          </a>
        </div>

        <!-- Wallet Cards -->
        <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card earnings-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="card-title mb-0"><i class="bi bi-wallet2 text-primary"></i> Total Earnings</h5>
                </div>
                <h2 class="text-primary mb-2">₹{{ number_format((float)($totalEarnings ?? 0), 2) }}</h2>
                <small class="text-muted">Lifetime earnings (referral + commission)</small>

              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card earnings-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="card-title mb-0"><i class="bi bi-gift text-primary"></i> Referral Wallet</h5>
                </div>
                <h2 class="text-primary mb-2">₹{{ number_format((float)($referralBalance ?? 0), 2) }}</h2>
                <div class="d-flex justify-content-between">
                  <small class="text-muted">Lifetime: ₹{{ number_format((float)($referralLifetime ?? 0), 2) }}</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card earnings-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="card-title mb-0"><i class="bi bi-cash-coin text-success"></i> Commission Wallet</h5>
                </div>
                <h2 class="text-success mb-2">₹{{ number_format((float)($commissionBalance ?? 0), 2) }}</h2>
                <div class="d-flex justify-content-between">
                  <small class="text-muted">Lifetime: ₹{{ number_format((float)($commissionLifetime ?? 0), 2) }}</small>

                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- All Transactions (Wallet Ledger) -->
        <div class="card">
          <div class="card-header bg-white">
            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
              <h5 class="mb-0"><i class="bi bi-clock-history"></i> Transactions</h5>
              <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-sm {{ empty($walletType) ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('user.earnings') }}">All</a>
                <a class="btn btn-sm {{ ($walletType ?? '') === 'referral' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('user.earnings', ['wallet_type' => 'referral']) }}">Referral Wallet</a>
                <a class="btn btn-sm {{ ($walletType ?? '') === 'commission' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('user.earnings', ['wallet_type' => 'commission']) }}">Commission Wallet</a>
              </div>
            </div>
          </div>

          <div class="card-body">
            @if(isset($ledgerEntries) && $ledgerEntries->count())
              <div class="d-none d-md-flex ledger-cols ledger-list-head mb-2">
                <div class="ledger-col ledger-col-date">Date</div>
                <div class="ledger-col ledger-col-desc">Description</div>
                <div class="ledger-col ledger-col-wallet">Wallet</div>
                <div class="ledger-col ledger-col-type">Type</div>
                <div class="ledger-col ledger-col-amount">Amount</div>
              </div>

              @foreach($ledgerEntries as $t)
                @php
                  $isCredit = ($t->transaction_type ?? '') === 'credit';
                  $amountSign = $isCredit ? '+' : '-';
                  $amountClass = $isCredit ? 'text-success' : 'text-danger';

                  $walletBadgeClass = ($t->wallet_type ?? '') === 'commission' ? 'bg-success' : 'bg-primary';
                  $walletLabel = ($t->wallet_type ?? '') === 'commission' ? 'Commission' : 'Referral';

                  $typeBadgeClass = $isCredit ? 'bg-success' : 'bg-danger';
                  $typeLabel = $isCredit ? 'Credit' : 'Debit';

                  $desc = $t->description ?: ucfirst(str_replace('_', ' ', (string) $t->reference_type));
                  $refLink = null;
                  if (($t->reference_type ?? '') === 'commission_transaction' && !empty($t->reference_id)) {
                    $refLink = route('user.commissions.detail', ['id' => $t->reference_id]);
                  } elseif (($t->reference_type ?? '') === 'referral_transaction') {
                    $refLink = route('user.referrals.app');
                  }
                @endphp

                <div class="ledger-row">
                  <div class="ledger-cols flex-column flex-md-row">
                    <div class="ledger-col ledger-col-date text-muted">
                      <div class="d-md-none ledger-list-head">Date</div>
                      {{ optional($t->created_at)->format('d M Y, h:i A') }}
                      <div class="small text-muted">Balance: ₹{{ number_format((float)($t->balance_after ?? 0), 2) }}</div>
                    </div>

                    <div class="ledger-col ledger-col-desc">
                      <div class="d-md-none ledger-list-head">Description</div>
                      <div class="fw-bold">{{ $desc }}</div>
                      <div class="small text-muted">
                        Ref: {{ ucfirst(str_replace('_', ' ', (string) $t->reference_type)) }}
                        @if(!empty($t->reference_id)) #{{ $t->reference_id }} @endif
                        @if($refLink)
                          · <a href="{{ $refLink }}">View</a>
                        @endif
                      </div>
                    </div>

                    <div class="ledger-col ledger-col-wallet">
                      <div class="d-md-none ledger-list-head">Wallet</div>
                      <span class="badge {{ $walletBadgeClass }}">{{ $walletLabel }}</span>
                    </div>

                    <div class="ledger-col ledger-col-type">
                      <div class="d-md-none ledger-list-head">Type</div>
                      <span class="badge {{ $typeBadgeClass }}">{{ $typeLabel }}</span>
                    </div>

                    <div class="ledger-col ledger-col-amount">
                      <div class="d-md-none ledger-list-head">Amount</div>
                      <div class="ledger-amount {{ $amountClass }}">{{ $amountSign }}₹{{ number_format((float)($t->amount ?? 0), 2) }}</div>
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="mt-3">
                {{ $ledgerEntries->links() }}
              </div>
            @else
              <div class="text-center py-5">
                <i class="bi bi-clock-history" style="font-size: 3rem; color: #dee2e6;"></i>
                <p class="text-muted mt-3 mb-0">No transactions found.</p>
                <p class="text-muted small mt-2 mb-0">Transactions will appear here after you receive a referral reward or commission credit.</p>
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
