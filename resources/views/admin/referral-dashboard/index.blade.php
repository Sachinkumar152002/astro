@extends('layouts.admin')

@push('styles')
<style>
  .refdash .page-title {
    font-size: 22px;
    font-weight: 700;
    margin: 0;
    color: #111827;
  }
  .refdash .page-subtitle {
    font-size: 13px;
    color: #6b7280;
  }
  /* Default cards (non-colorful sections) */
  .refdash .card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(17, 24, 39, 0.05);
  }

  /* Use main Dashboard colorful card styling for KPI + Quick Actions grids */
  .refdash .dashboard .card {
    border: none;
    border-radius: 5px;
    box-shadow: inherit;
  }
  .refdash .card-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  .refdash .card-link:hover .card {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(17, 24, 39, 0.08);
    transform: translateY(-1px);
    transition: all 0.2s ease;
  }
  .refdash .dashboard .card-link:hover .card {
    border-color: transparent;
    box-shadow: inherit;
    transform: translateY(-1px);
  }
  .refdash .kpi-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    letter-spacing: .02em;
    text-transform: uppercase;
  }
  .refdash .kpi-value {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin: 2px 0 0 0;
  }
  .refdash .kpi-meta {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }
  .refdash .section-title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0;
  }
  .refdash .muted {
    font-size: 12px;
    color: #6b7280;
  }
  .refdash .badge-soft {
    font-size: 12px;
    font-weight: 700;
    padding: 6px 10px;
    border-radius: 999px;
    display: inline-block;
  }
  .refdash .badge-soft.success { background: #e8f7ee; color: #067647; }
  .refdash .badge-soft.danger { background: #fdecec; color: #b42318; }
  .refdash .badge-soft.warning { background: #fff6e6; color: #b45309; }
  .refdash .badge-soft.info { background: #e8f2ff; color: #1d4ed8; }
  .refdash .table thead th {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .02em;
    color: #6b7280;
    border-top: 0;
  }
  .refdash .table thead.table-light th {
    background-color: #f1f5f9;
  }
  .refdash .table tbody td {
    font-size: 13px;
    color: #111827;
    vertical-align: middle;
  }
  .refdash .table tbody tr {
    background: #ffffff;
  }
  /* No rounded corners for these dashboard tables */
  .refdash .table,
  .refdash .table-responsive {
    border-radius: 0 !important;
  }
  .refdash .table-responsive {
    overflow: visible;
  }
  .refdash .quick-title {
    font-size: 12px;
    font-weight: 800;
    color: #111827;
    margin: 0;
  }
  .refdash .quick-subtitle {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
  }
  .refdash .quick-icon {
    height: 38px;
    width: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.55);
    font-size: 18px;
    flex: 0 0 38px;
  }
  .refdash .quick-card .card {
    padding: 14px;
  }
</style>
@endpush

@section('content')
@php
  $fmtMoney = function($amount) {
    return '₹' . number_format((float)$amount, 2);
  };
@endphp

<div class="refdash">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h1 class="page-title">Referral Dashboard</h1>
      <div class="page-subtitle">Quick overview + shortcuts to configure and review referral activity.</div>
    </div>
  </div>

  <!-- Top KPI Summary Cards (At a Glance) -->
  @php
    $canAppConfig = adminCan('referral_configuration', 'view');
    $canProductConfig = adminCan('product_referral_configuration', 'view');
    $canCategory = adminCan('category_commissions', 'view');
    $canReferralTx = adminCan('referral_transactions', 'view');
    $canCommissionTx = adminCan('commission_transactions', 'view');
    $canWithdrawals = adminCan('referral_withdrawals', 'view');
  @endphp

  <!-- Colorful KPI cards (reuse main Dashboard styling from styles.css) -->
  <div class="dashboard">
    <div class="row gutters-1">
      <div class="col-lg-4 col-md-4 col-6 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Referrals</div>
              <div class="kpi-value">{{ (int)$totalReferrals }}</div>
              <div class="kpi-meta">Signups with referral code (User + Astrologer)</div>
            </div>
            <div class="quick-icon"><i class="bi bi-people"></i></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-6 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Successful Conversions</div>
              <div class="kpi-value">{{ (int)$firstPurchaseUsers }}</div>
              <div class="kpi-meta">Referred users with ≥ 1 purchase</div>
            </div>
            <div class="quick-icon"><i class="bi bi-check-circle"></i></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-6 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Rewards Paid</div>
              <div class="kpi-value">{{ $fmtMoney($totalRewardsPaid ?? 0) }}</div>
              <div class="kpi-meta">App referral rewards (credited)</div>
            </div>
            <div class="quick-icon"><i class="bi bi-gift"></i></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-6 mb-3">
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Total Commission Paid</div>
              <div class="kpi-value">{{ $fmtMoney($totalCommissionPaid ?? 0) }}</div>
              <div class="kpi-meta">Product commissions (credited)</div>
            </div>
            <div class="quick-icon"><i class="bi bi-cash-coin"></i></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-6 mb-3">
        @php $net = (float)($netRevenueFromReferrals ?? 0); @endphp
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Net Revenue from Referrals</div>
              <div class="kpi-value" style="color: {{ $net >= 0 ? '#067647' : '#b42318' }};">
                {{ $fmtMoney($net) }}
              </div>
              <div class="kpi-meta">Sales − Commission − Rewards (approx)</div>
            </div>
            <div class="quick-icon"><i class="bi bi-graph-up"></i></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 col-6 mb-3">
        @php
          $avg = ((int)($totalReferrals ?? 0) > 0) ? ($net / (int)$totalReferrals) : 0;
        @endphp
        <div class="card p-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-label">Avg Revenue per Referral</div>
              <div class="kpi-value" style="color: {{ $avg >= 0 ? '#067647' : '#b42318' }};">
                {{ $fmtMoney($avg) }}
              </div>
              <div class="kpi-meta">Net Revenue ÷ Total Referrals</div>
            </div>
            <div class="quick-icon"><i class="bi bi-calculator"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Funnel View (Performance Flow) -->
  <div class="row">
    <div class="col-12 mb-3">
      <div class="shadow-none  p-3 border-0">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <div class="section-title">Funnel View (Performance Flow)</div>
            <div class="muted">Visitors → Signups → First Purchase → Repeat Purchase</div>
          </div>
        </div>

        <!-- Colorful funnel cards (reuse main Dashboard styling) -->
        <div class="dashboard">
          <div class="row gutters-1">
            <div class="col-md-3 col-6 mb-2">
              <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="kpi-label">Visitors</div>
                    <div class="kpi-value">{{ (int)($funnel['visitors'] ?? 0) }}</div>
                  </div>
                  <div class="quick-icon"><i class="bi bi-eye"></i></div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="kpi-label">Signups</div>
                    <div class="kpi-value">{{ (int)($funnel['signups'] ?? 0) }}</div>
                  </div>
                  <div class="quick-icon"><i class="bi bi-person-plus"></i></div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="kpi-label">First Purchase</div>
                    <div class="kpi-value">{{ (int)($funnel['first_purchase'] ?? 0) }}</div>
                  </div>
                  <div class="quick-icon"><i class="bi bi-cart-check"></i></div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
              <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="kpi-label">Repeat Purchase</div>
                    <div class="kpi-value">{{ (int)($funnel['repeat_purchase'] ?? 0) }}</div>
                  </div>
                  <div class="quick-icon"><i class="bi bi-arrow-repeat"></i></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Category Performance + Top Referrers + Pending Liabilities -->
  <div class="row">
    <div class="col-md-6 mb-3">
      <div class="bg-white">
        <div class="d-flex align-items-center justify-content-between k_bg3 py-2 px-3">
          <div>
            <div class="section-title mb-0">Category Performance (Revenue Impact)</div>
            <div class="muted">Category · Sales · Commission · Profit</div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>Category</th>
                <th class="text-right">Sales</th>
                <th class="text-right">Commission</th>
                <th class="text-right">Profit</th>
              </tr>
            </thead>
            <tbody>
              @forelse($categoryPerformance as $row)
                @php
                  $sales = (float)($row->sales ?? 0);
                  $commission = (float)($row->commission ?? 0);
                  $profit = $sales - $commission;
                @endphp
                <tr>
                  <td>{{ $row->category_name ?? 'N/A' }}</td>
                  <td class="text-right"><b>{{ $fmtMoney($sales) }}</b></td>
                  <td class="text-right">{{ $fmtMoney($commission) }}</td>
                  <td class="text-right" style="color: {{ $profit >= 0 ? '#067647' : '#b42318' }};">
                    <b>{{ $fmtMoney($profit) }}</b>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="muted">No data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-3">
      <div class="bg-white">
        <div class="d-flex align-items-center justify-content-between k_bg4 py-2 px-3">
          <div>
            <div class="section-title mb-0">Top Referrers (Leaderboard)</div>
            <div class="muted">User · Type · Total Referrals · Earnings</div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>User</th>
                <th>Type</th>
                <th class="text-right">Total Referrals</th>
                <th class="text-right">Earnings</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topReferrers as $r)
                <tr>
                  <td>{{ $r->name ?? 'N/A' }}</td>
                  <td>{{ ucfirst($r->type ?? '-') }}</td>
                  <td class="text-right"><b>{{ (int)($r->total_referrals ?? 0) }}</b></td>
                  <td class="text-right"><b>{{ $fmtMoney($r->earnings ?? 0) }}</b></td>
                </tr>
              @empty
                <tr><td colspan="4" class="muted">No data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12 mb-3">
      <div class="bg-white">
        <div class="d-flex align-items-center justify-content-between k_bg5 py-2 px-3">
          <div>
            <div class="section-title mb-0">Pending Liabilities</div>
            <div class="muted">Type · Amount · Count</div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>Type</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Count</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Pending App Rewards</td>
                <td class="text-right"><b>{{ $fmtMoney($pendingAppRewards->amt ?? 0) }}</b></td>
                <td class="text-right">{{ (int)($pendingAppRewards->cnt ?? 0) }}</td>
              </tr>
              <tr>
                <td>Pending Commissions</td>
                <td class="text-right"><b>{{ $fmtMoney($pendingCommissions->amt ?? 0) }}</b></td>
                <td class="text-right">{{ (int)($pendingCommissions->cnt ?? 0) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions (bottom) -->
  <div class=" p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <div>
        <div class="section-title">Quick Actions</div>
        <div class="muted">Jump directly to the most-used referral screens.</div>
      </div>
    </div>

    <div class="dashboard">
      <div class="row gutters-1">
      @if($canAppConfig)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.referral_configuration.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-sliders"></i></div>
                <div class="pl-3">
                  <div class="quick-title">App Referral Config</div>
                  <div class="quick-subtitle">Enable + set rewards & triggers</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif

      @if($canProductConfig)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.product_referral_configuration.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-gear"></i></div>
                <div class="pl-3">
                  <div class="quick-title">Product Referral Config</div>
                  <div class="quick-subtitle">Mode, credit time & users</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif

      @if($canCategory)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.category_commissions.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-table"></i></div>
                <div class="pl-3">
                  <div class="quick-title">Category Commissions</div>
                  <div class="quick-subtitle">Set rate, cap & status</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif

      @if($canReferralTx)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.referral_transactions.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-arrow-repeat"></i></div>
                <div class="pl-3">
                  <div class="quick-title">Referral Transactions</div>
                  <div class="quick-subtitle">Search & filter reward entries</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif

      @if($canCommissionTx)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.commission_transactions.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="pl-3">
                  <div class="quick-title">Commission Transactions</div>
                  <div class="quick-subtitle">Pending/credited/refunded</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif

      @if($canWithdrawals)
        <div class="col-lg-4 col-md-4 col-6 mb-3 quick-card">
          <a class="card-link" href="{{ route('admin.referral_withdrawals.index') }}">
            <div class="card">
              <div class="d-flex">
                <div class="quick-icon"><i class="bi bi-bank"></i></div>
                <div class="pl-3">
                  <div class="quick-title">Withdrawal Requests</div>
                  <div class="quick-subtitle">Approve / reject / process</div>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endif
      </div>
    </div>
  </div>
</div>
@endsection
