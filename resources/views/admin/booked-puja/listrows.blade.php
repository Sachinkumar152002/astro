@if($assignments->count())
@foreach($assignments as $index => $assignment)
<tr>
  <th scope="row">{{$index+1+(($assignments->currentPage()-1)*$assignments->perPage())}}</th>
  @if(!isset($type) || $type != 'group')
    <td>
      @if($assignment->order)
        <a href="{{ route('admin.order.edit', [$assignment->order->id]) }}" class="text-primary" target="_blank">
          {{ $assignment->order->order_number }}
        </a>
      @else
        N/A
      @endif
    </td>
  @endif
  <td>
    @if($assignment->product)
      @if(!empty($assignment->valid_date))
        {{ $assignment->product->title }} <span class="text-muted">({{ date('d M, Y', strtotime($assignment->valid_date)) }})</span>
      @else
        {{ $assignment->product->title }}
      @endif
    @else
      N/A
    @endif
  </td>
  <td>
    @if($assignment->astrologer)
      {{ $assignment->astrologer->name ?: $assignment->astrologer->display_name }}
    @else
      N/A
    @endif
  </td>
  @if(isset($type) && $type == 'group')
    <td>
      @php
        $totalBookings = $assignment->total_bookings ?? 0;
      @endphp
      @if($assignment->product && $totalBookings > 0)
        @php
          $gbParams = ['product_id' => $assignment->product->id];
          if(!empty($assignment->valid_date)) {
            $gbParams['valid_date'] = $assignment->valid_date;
          }
        @endphp
        <a href="{{ route('admin.booked-puja.group-bookings', $gbParams) }}"
           class="text-primary font-weight-bold">
          {{ $totalBookings }}
        </a>
      @else
        0
      @endif
    </td>
  @else
    <td>
      @php
        $hasImages = ($assignment->total_images_count ?? 0) > 0;
        $hasVideos = ($assignment->total_videos_count ?? 0) > 0;
      @endphp
      <div class="d-flex gap-2 align-items-center">
        <i class="bi bi-image{{ $hasImages ? ' text-primary' : ' text-muted' }}" style="font-size: 1.2rem;" title="Images"></i>
        <i class="bi bi-camera-video{{ $hasVideos ? ' text-danger' : ' text-muted' }}" style="font-size: 1.2rem;" title="Videos"></i>
      </div>
    </td>
    <td>
      @php
        $status = strtolower(trim((string)($assignment->status ?? 'assigned')));
        $statusBadge = match($status) {
          'completed' => 'bg-success',
          'cancelled' => 'bg-danger',
          'pending' => 'bg-warning text-dark',
          default => 'bg-warning text-dark', // 'assigned' or unknown = pending/warning
        };
        $statusLabel = ucfirst($status === 'assigned' ? 'Pending' : $status);
      @endphp
      <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
    </td>
  @endif
  @if(isset($type) && $type == 'group')
    @php
      $product = $assignment->product ?? null;
      $date = !empty($assignment->valid_date) ? $assignment->valid_date : ($product && !empty($product->expire_at) ? $product->expire_at : null);
    @endphp
    <td>{{ $date ? (strlen($date) <= 10 ? date('d M, Y', strtotime($date)) : $date) : 'N/A' }}</td>
  @else
    <td>{{ $assignment->created_at ? (is_string($assignment->created_at) ? $assignment->created_at : $assignment->created_at->format('Y-m-d H:i')) : 'N/A' }}</td>
  @endif
  <td class="text-right">
    @if(isset($type) && $type == 'group')
      {{-- For group puja, eye should open edit page (media management) with product_id parameter --}}
      @php
        $hasMedia = !empty($assignment->media_item);
        $editUrlParams = [
          'id' => $hasMedia ? $assignment->id : ($assignment->parent_product_id ?? $assignment->product->id ?? $assignment->id),
          'type' => 'group',
        ];
        if(!empty($assignment->product) && !empty($assignment->product->id)) {
          $editUrlParams['product_id'] = $assignment->product->id;
        }
        if(!empty($assignment->valid_date)) {
          $editUrlParams['valid_date'] = $assignment->valid_date;
        }
      @endphp
      @if($assignment->product && !empty($assignment->product->id))
        <a href="{{ route('admin.booked-puja.edit', $editUrlParams) }}" class="btn btn-sm btn-info" title="View / Edit">
          <i class="bi bi-eye"></i>
        </a>
      @else
        <span class="btn btn-sm btn-info disabled" title="No product">
          <i class="bi bi-eye"></i>
        </span>
      @endif

    @else
      <a href="{{ route('admin.booked-puja.edit', ['id' => $assignment->id, 'type' => isset($type) ? $type : 'one-to-one']) }}" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
      </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="{{ isset($type) && $type == 'group' ? '7' : '8' }}"><p>No Records</p></td>
</tr>
@endif
