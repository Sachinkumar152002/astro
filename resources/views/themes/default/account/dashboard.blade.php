@extends(themeView().'layout')

@section('title', 'User Dashboard | ASTROVYRA')

@section('content')
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row justify-content-between">
       @include(themeview().'account._sidebar')
       <div class="col-lg-9 col-12 mt-md-0 user_dash dash_b">
         <div class="container mt-2 mb-2 px-1">
          <div class="row g-3 same-menu-row">

              <!-- Item -->


              <div class="col-6 col-md-3">
                <a href="{{route('user.purchase.item')}}">
                  <div class="same-card">
                      <div class="same-icon" style="background:#0d6efd;">
                          <img src="/assets/img/checkout.png" alt="">
                      </div>
                      <p>Order</p>
                  </div>
                </a>
              </div>



              <div class="col-6 col-md-3 ">
                <a href="{{route('user.wallethistory')}}">
                  <div class="same-card">
                      <div class="same-icon" style="background:#28a745;">
                          <img src="/assets/img/redeem.png" alt="">
                      </div>
                      <p>Wallet History</p>
                  </div>
                </a>
              </div>



              <div class="col-6 col-md-3">
                <a href="{{route('user.consultations')}}">
                  <div class="same-card">
                      <div class="same-icon" style="background:#6f42c1;">
                          <img src="/assets/img/talk.png" alt="">
                      </div>
                      <p>Consultation</p>
                  </div>
                </a>
              </div>

              <div class="col-6 col-md-3">
                <a href="{{ route('user.support') }}">
                  <div class="same-card">
                      <div class="same-icon" style="background:#d4a017;">
                          <img src="/assets/img/support.png" alt="">
                      </div>
                      <p>Support</p>
                  </div>
                </a>
              </div>


          </div>
      </div>
         <div class="row my-0 dashbord">
           <!-- <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3">
             <a href="{{route('user.purchase.item')}}">
               <div class="dashbord_card bg text-center">
                 <i class="bi fs-4 text bi-cart text-black "></i>
                 <div class="card_text">
                   <h2>Orders</h2>
                 </div>
               </div>
             </a>
           </div>
           <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3 card-dashboard">
             <a href="{{ route('user.consultations') }}">
               <div class="dashbord_card bg-2 text-center">
                 <i class="bi fs-4 text bi-people text-black "></i>
                 <div class="card_text">
                   <h2>Consultation</h2>
                 </div>
               </div>
             </a>
           </div>
           <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3 card-dashboard">
             <a href="{{ route('user.wallethistory') }}">
               <div class="dashbord_card bg-3 text-center">
                 <i class="bi fs-4 text bi-hourglass text-black "></i>
                 <div class="card_text">
                   <h2>Wallet History</h2>
                 </div>
               </div>
             </a>
           </div>
           <div class="col-md-3 col-6 mb-2 mb-md-0 pe-1 pe-md-3 card-dashboard">
             <a href="{{ route('user.support') }}">
               <div class="dashbord_card min text-center">
                 <i class="bi fs-4 text bi-headset text-black "></i>
                 <div class="card_text">
                   <h2>Support</h2>
                 </div>
               </div>
             </a>
          </div> -->

          @if(isset($userReferral) && $userReferral && $user->referral_code)
          <!-- Referral Code Card -->
          <div class="col-md-12 mt-4">
            <div class="card border-primary shadow-sm">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-gift"></i> My Referral Code</h5>
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

          <div class="col-md-12">
            <!-- orders start -->
            <div class="financial-box1 mt-4">
     <h5>Orders</h5>
     <div class="mt-2">
       <div class="row">
         @if(count($orders))
           @foreach($orders as $index => $item)
             <div class="col-md-4 col-sm-6 mb-4">
               <div class="earning-card  p-3 bg-light rounded earning_hieght border">

                 <div class="d-flex justify-content-between align-items-center mb-2">
                   <span class="badge k_bg_badge-2">#{{ $index + 1 }}</span>
                   <span>
                     @if($item->product_type)
                      <span class="badge k_bg_badge">{{ productype($item->product_type) }}</span>
                     @endif
                     <span class="text-muted small">{{date('d-m-Y', strtotime($item->created_at))}}</span>
                   </span>
                 </div>

                 <div class="d-flex justify-content-between mb-2">
                   <span><i class="bi bi-receipt"></i> Order ID</span>
                   <span class="text-dark fw-bold">
                     <a href="{{ route('user.order.detail', [$item->id]) }}">{{ $item->order_number }}</a>
                   </span>
                 </div>

                 <div class="d-flex justify-content-between border-top pt-2">
                   <span><i class="bi bi-currency-rupee"></i> Amount</span>
                   <span class="text-success fw-bold">₹ {{$item->payable_amount}}</span>
                 </div>

               </div>
             </div>
           @endforeach

           <div class="col-12 text-center mt-3">
             <a href="{{route('user.purchase.item')}}" class="p-add text-black fw-bold ">View All</a>
           </div>

         @else
           <div class="col-12 text-center">
             <p>No Records</p>
           </div>
         @endif
       </div>
     </div>
   </div>

            <!-- orders end -->

            <!-- consultations start -->
            <div class="financial-box1 mt-4">
              <h5>Consultation</h5>
              <div class="mt-2">
                <div class="row">
                  @if(count($consultations))
                    @php
                      $modesName = ["", "Video", "Chat", "Call"]
                    @endphp
                    @foreach($consultations as $index => $item)
                      <div class="col-md-4 col-sm-6 mb-4">
                        <div class="earning-card  p-3 bg-light rounded earning_hieght border">

                          <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge k_bg_badge-2 ">#{{ $index + 1 }}</span>
                            <span class="text-muted small">{{ date('d-m-Y h:i a', strtotime($item->scheduled_date." ".$item->user_start_time)) }}</span>
                          </div>

                          <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-person"></i> Astrologer</span>
                            <span class="text-dark fw-bold">{{ $item->astrologer->display_name }}</span>
                          </div>

                          <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-camera-video"></i> Mode</span>
                            <span class="text-primary fw-bold">{{ $modesName[$item->package] }}</span>
                          </div>

                          <div class="d-flex justify-content-between border-top pt-2">
                            <span><i class="bi bi-currency-rupee"></i> Amount</span>
                            @if($item->free)
                              @php
                                $totalPrice = $item->total_price - ($item->actualprice*5)
                              @endphp
                              <span class="text-success fw-bold">₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</span>
                            @else
                              <span class="text-success fw-bold">₹ {{ $item->total_price }}</span>
                            @endif
                          </div>

                        </div>
                      </div>
                    @endforeach

                    <div class="col-12 text-center mt-3">
                      <a href="{{route('user.consultations')}}" class="p-add text-black fw-bold ">View All</a>
                    </div>

                  @else
                    <div class="col-12 text-center">
                      <p>No Records</p>
                    </div>
                  @endif
                </div>

               </table>
             </div>
           </div>
            <!-- consultations end -->

            <!-- wallet history start -->
            <div class="financial-box1 mt-4">
    <h5>Wallet History</h5>
    <div class="mt-2">
      <div class="row">
        @if(count($walletHistory))
          @foreach($walletHistory as $index => $item)
            <div class="col-md-4 col-sm-6 mb-4">
              <div class="earning-card  p-3 bg-light rounded earning_hieght border">

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge k_bg_badge-2">#{{ $index + 1 }}</span>
                  <span class="text-muted small">{{date('d-m-Y h:i a', strtotime($item->created_at))}}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span><i class="bi bi-chat-left-text"></i> Remark</span>
                  <span class="text-dark fw-bold">{{ $item->remark }}</span>
                </div>

                <div class="d-flex justify-content-between border-top pt-2">
                  <span><i class="bi bi-currency-rupee"></i> Amount</span>
                  <span class="fw-bold {{ $item->action ? 'text-success' : 'text-danger' }}">
                    {{ $item->action ? '+' : '-' }} ₹{{ $item->amount }}
                  </span>
                </div>

              </div>
            </div>
          @endforeach

          <div class="col-12 text-center mt-3">
            <a href="{{route('user.wallethistory')}}" class="p-add text-black fw-bold ">View All</a>
          </div>

        @else
          <div class="col-12 text-center">
            <p>No Records</p>
          </div>
        @endif
      </div>
    </div>
  </div>

            <!-- wallet history end -->

            <!-- support start -->
            <div class="financial-box1 mt-4">
              <h5>Support Tickets</h5>
              <div class="mt-2">
 <div class="row">
   @if(count($supports))
     @foreach($supports as $index => $item)
       <div class="col-md-4 col-sm-6 mb-4">
         <div class="earning-card  p-3 bg-light rounded earning_hieght border">

           <div class="d-flex justify-content-between align-items-center mb-2">
             <span class="badge k_bg_badge-2">#{{ $index + 1 }}</span>
             <span class="text-muted small">{{date('d-m-Y h:i a', strtotime($item->created_at))}}</span>
           </div>

           <div class="d-flex justify-content-between mb-2">
             <span><i class="bi bi-chat-left-text"></i> Subject</span>
             <span class="text-dark fw-bold">{{ $item->subject }}</span>
           </div>

           <div class="d-flex justify-content-between border-top pt-2">
             <span><i class="bi bi-info-circle"></i> Status</span>
             <span class="fw-bold
               @if($item->status == 'open') text-success
               @elseif($item->status == 'pending') text-warning
               @elseif($item->status == 'closed') text-danger
               @else text-secondary @endif">
               {{ ucfirst($item->status) }}
             </span>
           </div>

         </div>
       </div>
     @endforeach

     <div class="col-12 text-center mt-3">
       <a href="{{route('user.support')}}" class="p-add text-black fw-bold ">View All</a>
     </div>

   @else
     <div class="col-12 text-center">
       <p>No Records</p>
     </div>
   @endif
 </div>
</div>

           </div>
            <!-- support end -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
@if(isset($userReferral) && $userReferral && $user->referral_code)
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
