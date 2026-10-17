@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">Transaction Detail</h1>
<div class="clearfix"></div>


<div class="card">
  <div class="card-body">
    <table class="table table-striped table-bordered">
      <tbody>
        @php
          $wt = $detailData['wallet_transition'] ?? null;
          $accountHolder = $detailData['account_holder'] ?? null;
          $withdraw = $detailData['withdraw'] ?? null;
        @endphp

        @php
          $commission = $detailData['commission'] ?? null;
        @endphp

        @if($commission)
          <!-- Commission Transaction Report (Influencer) - Show listing fields in table format -->
          @php
            $influencer = $detailData['influencer'] ?? null;
            $order = $detailData['order'] ?? null;
            $orderItem = $detailData['order_item'] ?? null;
            $product = $detailData['product'] ?? null;
          @endphp

          <tr>
            <th style="width: 30%;">Influencer Name</th>
            <td>{{ $detailData['influencer_name'] ?? (($influencer && $influencer->influencer_name) ? $influencer->influencer_name : 'N/A') }}</td>
          </tr>
          <tr>
            <th>Product Name</th>
            <td>{{ $product ? ($product->title ?? 'N/A') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Purchase Date</th>
            <td>{{ $order && $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Order Number</th>
            <td>{{ $order ? ($order->order_number ?? 'N/A') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Commission Amount</th>
            <td>₹{{ number_format($commission->commission_amount ?? 0, 2) }}</td>
          </tr>
          <tr>
            <th>Credited Date</th>
            <td>{{ $commission->credited_at ? \Carbon\Carbon::parse($commission->credited_at)->format('Y-m-d H:i') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              @if($commission->status == 'credited')
                <span class="badge badge-success">Credited</span>
              @elseif($commission->status == 'refunded')
                <span class="badge badge-danger">Refunded</span>
              @else
                <span class="badge badge-warning">Pending</span>
              @endif
            </td>
          </tr>
        @elseif($withdraw && !$wt)
          <!-- Withdrawal Report - Show only listing fields -->
          @php
            $astrologer = $detailData['astrologer'] ?? null;
            $availableBalance = $detailData['available_balance'] ?? 0;
            $walletBalance = $detailData['wallet_balance'] ?? 0;
          @endphp

          <tr>
            <th style="width: 30%;">Date</th>
            <td>{{ $withdraw->created_at ? \Carbon\Carbon::parse($withdraw->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Astrologer Name</th>
            <td>{{ $astrologer ? ($astrologer->display_name ?? ($astrologer->firstname . ' ' . $astrologer->lastname)) : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Available Balance</th>
            <td>₹{{ number_format($availableBalance, 2) }}</td>
          </tr>
          <tr>
            <th>Request Amount</th>
            <td>₹{{ number_format($withdraw->amount ?? 0, 2) }}</td>
          </tr>
          <tr>
            <th>Wallet Balance</th>
            <td>₹{{ number_format($walletBalance, 2) }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              @if($withdraw->status == 'done')
                <span class="badge badge-success">Done</span>
              @elseif($withdraw->status == 'cancel')
                <span class="badge badge-danger">Cancel</span>
              @else
                <span class="badge badge-warning">Pending</span>
              @endif
            </td>
          </tr>
        @else
          <!-- Other Reports - Show wallet transition details -->
          <!-- Wallet Transition Details -->
          <tr>
            <th style="width: 30%;">Transaction ID</th>
            <td>{{ $wt->id ?? 'N/A' }}</td>
          </tr>
          <tr>
            <th>Transaction Type</th>
            <td>
              @if($wt->action == 1)
                <span class="badge badge-success">Credit</span>
              @else
                <span class="badge badge-danger">Debit</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Amount</th>
            <td>₹{{ number_format($wt->amount ?? 0, 2) }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              @if($wt->status == 1)
                <span class="badge badge-success">Success</span>
              @else
                <span class="badge badge-danger">Failed</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Remark</th>
            <td>{{ $wt->remark ?? 'N/A' }}</td>
          </tr>
          <tr>
            <th>Created At</th>
            <td>{{ $wt->created_at ? \Carbon\Carbon::parse($wt->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
          </tr>
          <tr>
            <th>Updated At</th>
            <td>{{ $wt->updated_at ? \Carbon\Carbon::parse($wt->updated_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
          </tr>

          <!-- Account Holder Details -->
          @if($accountHolder)
            <tr>
              <th colspan="2" class="bg-light"><strong>Account Holder Information</strong></th>
            </tr>
            <tr>
              <th>Account Type</th>
              <td>{{ ucfirst($wt->user_type ?? 'N/A') }}</td>
            </tr>
            @if($wt->user_type == 'astrologer')
              <tr>
                <th>Astrologer Name</th>
                <td>{{ $accountHolder->display_name ?? ($accountHolder->firstname . ' ' . $accountHolder->lastname) ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ $accountHolder->email ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Phone</th>
                <td>{{ $accountHolder->phone ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Current Wallet Balance</th>
                <td>₹{{ number_format($accountHolder->wallet ?? 0, 2) }}</td>
              </tr>
            @else
              <tr>
                <th>User Name</th>
                <td>{{ $accountHolder->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ $accountHolder->email ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Phone</th>
                <td>{{ $accountHolder->phone ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Current Wallet Balance</th>
                <td>₹{{ number_format($accountHolder->wallet ?? 0, 2) }}</td>
              </tr>
            @endif
          @endif
        @endif




      </tbody>
    </table>
  </div>
</div>

<div class="clearfix"></div>
<div class="text-center mt-3">
  <a href="javascript:history.back()" class="btn btn-info">Back</a>
</div>
@endsection

@push('styles')
<style>
  .table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 12px;
  }
  .table td {
    padding: 12px;
  }
  .bg-light {
    background-color: #e9ecef !important;
  }
</style>
@endpush
