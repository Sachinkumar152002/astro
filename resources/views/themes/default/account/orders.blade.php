@extends(themeView().'layout')

@section('title', 'Orders | '.config("app.name"))

@section('content')

@php
  $mobileView = str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
@endphp

<section class="account-page-ui dashboad_section">
  <div class="container">
  <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
      <div class="row my-3 recent mt-md-0">

        @if(isset($userReferral) && $userReferral)
        <!-- Referral Code Card -->
        <div class="col-lg-12 mb-4">
          <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-gift"></i> My Referral Code</h5>
            </div>
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                  <div class="text-center p-3 bg-light rounded">
                    <small class="text-muted d-block mb-2">Your Referral Code</small>
                    <h4 class="mb-0 text-primary fw-bold">{{ $userReferral->referral_code }}</h4>
                    <div class="mt-2">
                      <a href="javascript:void(0)" onclick="copyReferralCode('{{ $userReferral->referral_code }}')" class="p-add text-black fw-bold">Copy Code</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block mb-3">Share Your Referral Code</small>
                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                      <!-- Facebook Share -->
                      <a href="javascript:void(0)" onclick="shareOnFacebook('{{ $userReferral->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #1877f2; border-color: #1877f2; min-width: 120px; justify-content: center;">
                        <i class="bi bi-facebook" style="font-size: 18px; color: white;"></i>
                        <span>Facebook</span>
                      </a>
                      <!-- Messenger Share -->
                      <a href="javascript:void(0)" onclick="shareOnMessenger('{{ $userReferral->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #0084ff; border-color: #0084ff; min-width: 120px; justify-content: center;">
                        <i class="bi bi-messenger" style="font-size: 18px; color: white;"></i>
                        <span>Messenger</span>
                      </a>
                      <!-- WhatsApp Share -->
                      <a href="javascript:void(0)" onclick="shareOnWhatsApp('{{ $userReferral->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #25d366; border-color: #25d366; min-width: 150px; justify-content: center;">
                        <i class="bi bi-whatsapp" style="font-size: 18px; color: white;"></i>
                        <span>Refer via WhatsApp</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif

        <div class="col-lg-12 order_page">
        <h4>Orders</h4>
        @forelse($orders as $key => $item)
          <div class="order-box my-4 bg-white border-n">
            <div class="o-table-box bg-dash2 px-3 py-1">
              @if($mobileView)
                <table class="w-100 order-table">
                  <tbody>
                    <tr>
                      <td><strong>Order Date:</strong><br> {{date('d-m-Y', strtotime($item->created_at))}}</td>
                      <td><strong>Total Price:</strong><br> ₹{{$item->total_price}}</td>
                    </tr>
                    <tr>
                      <td><strong>Order ID:</strong><br> #{{$item->order_number}}</td>
                      @if($item->item_type == 0)
                        @php
                          $orderRequests = $item->requests;
                        @endphp
                        @if($orderRequests->where('request_type', 'cancel')->where('status', 0)->count() > 0)
                          <td><strong>Order Status:</strong><br> Cancel request pending </td>
                        @elseif($orderRequests->where('request_type', 'return')->where('status', 0)->count() > 0)
                          <td><strong>Order Status:</strong><br> Return request pending </td>
                        @else
                          @if($item->order_status == 'cancel')
                            <td><strong>Order Status:</strong><br> Cancelled</td>
                          @elseif($item->order_status=='return')
                            <td><strong>Order Status:</strong><br> Returned</td>
                          @else
                            <td><strong>Order Status:</strong><br> {{ $item->order_status }}</td>
                          @endif
                        @endif
                      @endif
                      <td>{{ $item->orderItems->count() }}</td>
                    </tr>
                  </tbody>
                </table>
              @else
                <table class="w-100">
                  <tbody>
                    <tr>
                      <td><strong>Order Date:</strong> {{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                      <td><strong>Total Price:</strong> ₹{{ $item->total_price }}</td>
                      <td><strong>Order ID:</strong> #{{ $item->order_number }}</td>
                      @if($item->item_type == 0)
                        @php
                          $orderRequests = $item->requests;
                        @endphp
                        @if($orderRequests->where('request_type', 'cancel')->where('status', 0)->count() > 0)
                          <td><strong>Order Status:</strong> Cancel request pending </td>
                        @elseif($orderRequests->where('request_type', 'return')->where('status', 0)->count() > 0)
                          <td><strong>Order Status:</strong> Return request pending </td>
                        @else
                          @if($item->order_status == 'cancel')
                            <td><strong>Order Status:</strong> Cancelled</td>
                          @elseif($item->order_status=='return')
                            <td><strong>Order Status:</strong> Returned</td>
                          @else
                            <td><strong>Order Status:</strong> {{ $item->order_status }}</td>
                          @endif
                        @endif
                      @endif
                      <td>{{ $item->orderItems->count() }}</td>
                    </tr>
                  </tbody>
                </table>
              @endif
            </div>
            @php
              $firstItem = $item->orderItems->first() ?: null;
            @endphp
            @if($firstItem)
              <div class="px-3 py-3">
                <div class="row">
                  <div class="col-md-1 text-center text-md-start">
                      <img src="{{$firstItem ? $firstItem->image : no_image()}}" class="img-fluid radius order-main-b w-100" alt="{{$firstItem?$firstItem->name:'no_image'}}" height="100">
                  </div>
                  <div class="col-md-8  text-start mt-2 mt-md-0">
                    <p class="mb-0 fw-semibold fs-6" style="">{{ $firstItem->name }}</p>
                    @if($firstItem->quantity > 1)
                      <div class="">
                        <span class="ms-2 sale_price text-left fs-7 text fw-semibold text-ac-price" tabindex="0">₹{{$firstItem->price.' x '.$firstItem->quantity }}</span>
                     </div>
                    @else
                      <div class="">
                        <span class="sale_price text-left fs-7 text fw-semibold text-ac-price" tabindex="0">₹{{ $firstItem->price }}</span>
                     </div>
                    @endif
                  </div>
                  <div class="col-md-3  text-md-end mt-2 mt-md-0">
                    <a href="{{ route('user.order.detail', [$item->id]) }}"><button class="main_button">View Detail</button></a>
                  </div>
                </div>
              </div>
            @endif
          </div>
        @empty
          <div class="order-box my-4">
            <div class="o-table-box bg-light px-4 py-3">
              <table class="w-100">
                <tbody>
                  <tr>
                    <td class="text-center"><div class="">
                      <img src="/assets/images/no-product.webp" alt="No Orders Found" class="nodata">
                      <h5 class="mt-3 text-dark"> No Orders Found!</h5>
                      <p class="text-secondary"> Your Purchages and history will be displayed here.</p>
                    </div> </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        @endforelse
        <div class="text-center">
        {!! $orders->links('vendor.pagination.bootstrap-5') !!}
        </div>
      </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection

@push('scripts')
@if(isset($userReferral) && $userReferral)
<script>
function copyReferralCode(code) {
  navigator.clipboard.writeText(code).then(function() {
    toastr.success('Referral code copied to clipboard!');
  }, function() {
    // Fallback for older browsers
    var textArea = document.createElement("textarea");
    textArea.value = code;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    toastr.success('Referral code copied to clipboard!');
  });
}

function shareOnFacebook(code) {
  var shareText = 'Use my referral code: ' + code;
  var shareUrl = 'https://www.facebook.com/sharer/sharer.php?quote=' + encodeURIComponent(shareText);
  window.open(shareUrl, 'facebook-share-dialog', 'width=626,height=436');
}

function shareOnMessenger(code) {
  var shareText = 'Use my referral code: ' + code;
  // Copy code to clipboard first
  navigator.clipboard.writeText(code).then(function() {
    // Try to open Messenger web
    var messengerUrl = 'https://www.facebook.com/messages/t/';
    // Since Messenger requires a user ID, we'll just copy the code and show a message
    toastr.success('Referral code copied! You can now paste it in Messenger: ' + code);
    // Alternative: Open Messenger in new tab (user can paste the code)
    window.open('https://www.facebook.com/messages/', '_blank');
  }, function() {
    // Fallback
    var textArea = document.createElement("textarea");
    textArea.value = code;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    toastr.success('Referral code copied! You can now paste it in Messenger: ' + code);
    window.open('https://www.facebook.com/messages/', '_blank');
  });
}

function shareOnWhatsApp(code) {
  var shareText = 'Use my referral code: *' + code + '*';
  var whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(shareText);
  window.open(whatsappUrl, '_blank');
}
</script>
@endif
@endpush
