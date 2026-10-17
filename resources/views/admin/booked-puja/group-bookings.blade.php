@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
  <h1 class="text-primary mb-0" style="word-break: break-word; white-space: normal; overflow: visible; text-overflow: clip; max-width: 100%; flex: 1 1 auto; margin-right: 15px;">Group Puja Bookings - {{ $product->title ?? 'Puja #'.$product->id }}</h1>
  <div style="flex-shrink: 0; display: flex; gap: 10px;">
    @php
      // Use same logic as listrows.blade.php for edit URL
      $hasMedia = false;
      $mediaItem = \App\Model\AstrologerPujaMedia::where('product_id', $product->id)
        ->whereNotNull('group_puja_reference')
        ->first();
      if($mediaItem) {
        $hasMedia = true;
      }
      $editUrlParams = [
        'id' => $hasMedia ? $mediaItem->id : $product->id,
        'type' => 'group',
      ];
      if($product->id) {
        $editUrlParams['product_id'] = $product->id;
      }
    @endphp
    <a href="{{ route('admin.booked-puja.edit', $editUrlParams) }}" class="btn btn-sm btn-info">
      <i class="bi bi-eye"></i> Manage Media
    </a>
    <a href="{{ route('admin.booked-puja.group-bookings.pdf', ['product_id' => $product->id]) }}" class="btn btn-sm btn-primary" target="_blank">
      Download Data
    </a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <p class="mb-1"><strong>Pandit / Astrologer:</strong> {{ $product->pandit_name ?? 'N/A' }}</p>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><strong>Total Bookings:</strong> {{ $totalBookings ?? $families->total() }}</p>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><strong>Scheduled At:</strong>
          @if($product->expire_at)
            {{ \Carbon\Carbon::parse($product->expire_at)->format('Y-m-d H:i') }}
          @else
            N/A
          @endif
        </p>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Family References & Members</h5>
  </div>
  <div class="card-body">
    @if($families->count() > 0)
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Family Reference</th>
              <th>Gotra Name</th>
              <th>Order Number</th>
              <th>Order Date</th>
              <th>Members</th>
            </tr>
          </thead>
          <tbody>
            @foreach($families as $index => $family)
              <tr>
                <td>{{ ($families->currentPage() - 1) * $families->perPage() + $loop->iteration }}</td>
                <td>{{ $family['reference'] ?? 'N/A' }}</td>
                <td>{{ $family['gotra'] ?? 'N/A' }}</td>
                <td>
                  @if(!empty($family['order_id']))
                    <a href="{{ route('admin.order.edit', [$family['order_id']]) }}" target="_blank">
                      {{ $family['order_number'] ?? $family['order_id'] }}
                    </a>
                  @else
                    N/A
                  @endif
                </td>
                <td>{{ $family['order_date'] ?? 'N/A' }}</td>
                <td>
                  @if(!empty($family['persons']) && is_array($family['persons']))
                    <ul class="mb-0 pl-3">
                      @foreach($family['persons'] as $person)
                        <li>
                          {{ $person['label'] ?? 'Name' }}: {{ $person['name'] ?? '' }}
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <span class="text-muted">No member details</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        @include('admin._partials.pagination', ['results' => $families])
      </div>
    @else
      <p class="text-muted mb-0">No bookings found for this group puja.</p>
    @endif
  </div>
</div>
@endsection

