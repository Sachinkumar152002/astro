@extends(themeView().'layout')

@section('title', 'Booked Puja | '.config("app.name"))
@section('keywords', '')
@section('description','')

@push('style')
<style>
  .puja-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
  }
  .puja-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
  }
  .puja-card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }
  .puja-card-body {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .puja-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
    min-height: 44px;
  }
  .order-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    font-size: 13px;
  }
  .order-info-item {
    margin-bottom: 5px;
  }
  .status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .status-assigned {
    background-color: #28a745;
    color: white;
  }
  .puja-card-buttons {
    display: flex;
    gap: 8px;
    margin-top: 15px;
    width: 100%;
    box-sizing: border-box;
  }
  .puja-card-buttons .btn {
    flex: 1;
    white-space: nowrap;
    padding: 6px 8px;
    font-size: 11px;
    border-radius: 4px;
    border: 1px solid transparent;
    transition: all 0.3s ease;
    line-height: 1.2;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .puja-card-buttons .btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }
  .modal .close {
    padding: 0;
    background-color: transparent;
    border: 0;
    opacity: 0.5;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    cursor: pointer;
  }
  .modal .close:hover {
    opacity: 0.75;
  }
</style>
@endpush

@section('content')
@php
  $user = auth()->guard('web')->user();
@endphp

<section class="account-page-ui dashboad_section">
  <div class="container">
    <div class="row">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
        <div class=" mt-4 mt-lg-0 tab-scroll-wrapper ps-2 border-n">
          <!-- Tabs Navigation -->
          <ul class="nav nav-tabs mb-3" id="bookedPujaTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="one-to-one-tab" data-bs-toggle="tab" data-bs-target="#one-to-one" type="button" role="tab" aria-controls="one-to-one" aria-selected="true">
                One to One Puja
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab" aria-controls="group" aria-selected="false">
                Family Puja
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="bookedPujaTabContent">
            <!-- One to One Puja Tab -->
            <div class="tab-pane fade show active" id="one-to-one" role="tabpanel" aria-labelledby="one-to-one-tab">
              <div class="card">
                <div class="card-header bg-white border-bottom">
                  <h4 class="mb-0">One to One Puja</h4>
                </div>
                <div class="card-body">
                  @if(isset($oneToOneAssignments) && $oneToOneAssignments->count() > 0)
                    <div class="row g-4">
                      @foreach($oneToOneAssignments as $assignment)
                        @php
                          // Check if within 24 hours of booking (use order created_at as booking time)
                          $bookingTime = $assignment->order && $assignment->order->created_at ? strtotime($assignment->order->created_at) : ($assignment->created_at ? strtotime($assignment->created_at) : time());
                          $currentTime = time();
                          $hoursSinceBooking = ($currentTime - $bookingTime) / 3600;
                          $within24hrs = $hoursSinceBooking <= 24;

                          // Check if media exists (images or videos) - when See Media is shown, hide Cancel
                          $hasMedia = false;
                          if($assignment->approvedMedia && $assignment->approvedMedia->count() > 0) {
                            $hasMedia = true;
                          }

                          // Cancel logic: if astrologer scheduled within 24 hrs, cancel only until 10 min before schedule; else 24 hrs from booking. If schedule already passed, hide cancel.
                          $scheduleTimestamp = null;
                          if($assignment->mahurat && $assignment->mahurat->date && $assignment->mahurat->time) {
                            $scheduleTimestamp = strtotime($assignment->mahurat->date->format('Y-m-d') . ' ' . $assignment->mahurat->time);
                          }
                          $scheduleAlreadyPassed = $scheduleTimestamp && ($scheduleTimestamp <= $currentTime);
                          if($scheduleAlreadyPassed) {
                            $canCancel = false;
                          } else {
                            $scheduleWithin24hrs = $scheduleTimestamp && (($scheduleTimestamp - $currentTime) <= 24 * 3600) && ($scheduleTimestamp - $currentTime) > 0;
                            if($scheduleWithin24hrs) {
                              $tenMinBeforeSchedule = $scheduleTimestamp - (10 * 60);
                              $canCancel = ($currentTime < $tenMinBeforeSchedule) && !$hasMedia;
                            } else {
                              $canCancel = $within24hrs && !$hasMedia;
                            }
                          }
                        @endphp
                        <div class="col-md-4 col-sm-6">
                          <div class="card">
                            @if($assignment->product && $assignment->product->image)
                              <img src="{{ $assignment->product->image->image(400, 400) }}"
                                   alt="{{ $assignment->product->title ?? 'Puja' }}"
                                   class="card-img-top puja-card-image">
                            @else
                              <img src="{{ no_image() }}"
                                   alt="Puja"
                                   class="card-img-top puja-card-image">
                            @endif
                            <div class="card-body puja-card-body">
                              <h5 class="card-title puja-card-title">{{ $assignment->product->title ?? 'Puja #' . $assignment->product_id }}</h5>
                              @php
                                $bookedAt = $assignment->order && $assignment->order->created_at ? $assignment->order->created_at : $assignment->created_at;
                              @endphp
                              @if($bookedAt)
                                <p class="text-muted small mb-2"><strong>Booked Date:</strong> {{ is_string($bookedAt) ? date('d M Y, h:i A', strtotime($bookedAt)) : $bookedAt->format('d M Y, h:i A') }}</p>
                              @endif
                              <div class="puja-card-buttons">
                                @if($hasMedia)
                                  <a href="{{ route('user.booked-puja.detail', [$assignment->id]) }}" class="btn btn-secondary btn-sm" style="text-align: center; display: flex; align-items: center; justify-content: center;">
                                    See Media
                                  </a>
                                @endif
                                @if($canCancel)
                                  <button type="button" class="btn btn-danger btn-sm" onclick="cancelPuja({{ $assignment->id }})" style="text-align: center; display: flex; align-items: center; justify-content: center;">
                                    Cancel Puja
                                  </button>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No one-to-one pujas booked yet.</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- Family Puja Tab -->
            <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
              <div class="card">
                <div class="card-header bg-white border-bottom">
                  <h4 class="mb-0">Family Puja</h4>
                </div>
                <div class="card-body">
                  @if(isset($groupPujaOrders) && $groupPujaOrders->count() > 0)
                    <div class="row g-4">
                      @foreach($groupPujaOrders as $order)
                        @php
                          // Use parent_product from order object (already set in controller)
                          $parentProduct = $order->parent_product ?? null;
                          if(!$parentProduct) {
                            $firstOrderItem = $order->orderItems->first();
                            $product = $firstOrderItem ? $firstOrderItem->product : null;
                            $parentProduct = $product;
                            if($product && $product->parent_id && $product->parent_id > 0) {
                              $parentProduct = \App\Product::find($product->parent_id);
                            }
                          }
                        @endphp
                        @if($parentProduct && $order->group_puja_reference)
                          <div class="col-md-4 col-sm-6">
                            <div class="card">
                              @if($parentProduct->image)
                                <img src="{{ $parentProduct->image->image(400, 400) }}"
                                     alt="{{ $parentProduct->title ?? 'Puja' }}"
                                     class="card-img-top puja-card-image">
                              @else
                                <img src="{{ no_image() }}"
                                     alt="Puja"
                                     class="card-img-top puja-card-image">
                              @endif
                              <div class="card-body puja-card-body">
                                <h5 class="card-title puja-card-title">{{ $parentProduct->title ?? 'Puja #' . $parentProduct->id }}</h5>
                                @if($order->created_at)
                                  <p class="text-muted small mb-2"><strong>Booked Date:</strong> {{ is_string($order->created_at) ? date('d M Y, h:i A', strtotime($order->created_at)) : $order->created_at->format('d M Y, h:i A') }}</p>
                                @endif
                                <div class="puja-card-buttons">
                                  @if(isset($order->has_approved_media) && $order->has_approved_media && $order->group_puja_reference && $parentProduct)
                                    <a href="{{ url('/account/booked-puja/group/detail') }}?group_puja_reference={{ urlencode($order->group_puja_reference) }}&product_id={{ $parentProduct->id }}&order_id={{ $order->id }}" class="btn btn-secondary btn-sm" style="text-align: center; display: flex; align-items: center; justify-content: center; color: white;">
                                      See Media
                                    </a>
                                  @endif
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No family pujas booked yet.</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Cancel Puja Modal -->
<div class="modal fade" id="cancelPujaModal" tabindex="-1" aria-labelledby="cancelPujaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelPujaModalLabel">Cancel Puja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="cancelPujaForm">
          <input type="hidden" id="cancelAssignmentId" name="assignment_id">
          <div class="mb-3">
            <label for="cancelReason" class="form-label">Reason for Cancel <span class="text-danger">*</span></label>
            <textarea class="form-control" id="cancelReason" name="reason" rows="4" placeholder="Please provide a reason for cancelling this puja" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="submitCancelPuja()">Cancel Puja</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
var currentCancelAssignmentId = null;

function cancelPuja(assignmentId) {
  currentCancelAssignmentId = assignmentId;
  $('#cancelAssignmentId').val(assignmentId);
  $('#cancelReason').val('');
  $('#cancelPujaModal').modal('show');
}

function submitCancelPuja() {
  var reason = $('#cancelReason').val().trim();

  if(!reason) {
    toastr.error('Please provide a reason for cancellation');
    return;
  }

  if(!currentCancelAssignmentId) {
    toastr.error('Invalid assignment');
    return;
  }

  $.ajax({
    url: '{{ route("user.booked-puja.cancel") }}',
    type: 'POST',
    data: {
      assignment_id: currentCancelAssignmentId,
      reason: reason,
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      if(response.success) {
        $('#cancelPujaModal').modal('hide');
        toastr.success(response.message || 'Puja cancelled successfully');
        setTimeout(function() {
          location.reload();
        }, 1000);
      } else {
        toastr.error(response.message || 'Error cancelling puja');
      }
    },
    error: function(xhr) {
      var message = xhr.responseJSON?.message || 'Error cancelling puja';
      toastr.error(message);
    }
  });
}
</script>
@endpush

@endsection
