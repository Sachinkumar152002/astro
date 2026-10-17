@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}
<div class="container mt-3 mb-3">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12">
          <div class="row my-3 recent">

            @if($user->referral_code)
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
                        <h4 class="mb-0 text-primary fw-bold">{{ $user->referral_code }}</h4>
                        <div class="mt-2">
                          <a href="javascript:void(0)" onclick="copyReferralCode('{{ $user->referral_code }}')" class="p-add text-black fw-bold">Copy Code</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="p-3 bg-light rounded">
                        <small class="text-muted d-block mb-3">Share Your Referral Code</small>
                        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                          <!-- Facebook Share -->
                          <a href="javascript:void(0)" onclick="shareOnFacebook('{{ $user->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #1877f2; border-color: #1877f2; min-width: 120px; justify-content: center;">
                            <i class="bi bi-facebook" style="font-size: 18px; color: white;"></i>
                            <span>Facebook</span>
                          </a>
                          <!-- Messenger Share -->
                          <a href="javascript:void(0)" onclick="shareOnMessenger('{{ $user->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #0084ff; border-color: #0084ff; min-width: 120px; justify-content: center;">
                            <i class="bi bi-messenger" style="font-size: 18px; color: white;"></i>
                            <span>Messenger</span>
                          </a>
                          <!-- WhatsApp Share -->
                          <a href="javascript:void(0)" onclick="shareOnWhatsApp('{{ $user->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #25d366; border-color: #25d366; min-width: 150px; justify-content: center;">
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

            <div class="col-lg-12">
            <h4>Orders</h4>
            @forelse($orders as $key => $item)
              <div class="order-box my-4">
                <div class="o-table-box bg-light px-4 py-3">
                  <table class="w-100">
                    <tbody>
                      <tr>
                        <td><strong>Order Date:</strong> {{date('d-m-Y', strtotime($item->created_at))}}</td>
                        <td><strong>Total Price:</strong> ₹{{inrConvert($item->total_price)}}</td>
                        <td><strong>Order ID:</strong> #{{$item->order_number}}</td>
                        <td><strong>Order Status:</strong> {{$item->status == 'Success' ? 'Placed' : $item->status}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="px-4 py-3">
                  @php
                  $firstItem = count($item->orderItems)>0?$item->orderItems[0]:null;
                  @endphp
                  <div class="row">
                    <div class="col-md-1">
                        <img src="{{$firstItem ? $firstItem->image : no_image()}}" class="img-fluid radius" alt="no image" height="100">
                    </div>
                    <div class="col-md-7">
                      <p class="mb-0">{{$firstItem?$firstItem->name:''}}</p>
                      <div class="">
                        <del class="actual_price fs-7 text fw-bold" tabindex="0">₹{{$firstItem ? inrConvert($firstItem->unit_price) : ''}}</del>
                        <span class="ms-2 sale_price text-left fs-7 text fw-bold text-ac-price" tabindex="0">₹{{$firstItem ? inrConvert($firstItem->price) : ''}}</span>
                     </div>
                    </div>
                    <div class="col-md-4 text-end">
                      <a href="{{route('astrologer.order.detail', [$item->id])}}"><button class="btn btn-secondary py-0 px-4" style="height:35px;">{{count($item->orderItems)>1?(count($item->orderItems)-1).' more items':'View Detail'}}</button></a>
                    </div>
                  </div>
                </div>
              </div>
            @empty
            <div class="order-box my-4">
              <div class="o-table-box bg-light px-4 py-3">
                <table class="w-100">
                  <tbody>
                    <tr>
                      <td>No Orders Found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            @endforelse
          </div>
          </div>
        </div>
    </div>
  </div>

@endsection

@push('scripts')
@if($user->referral_code)
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
