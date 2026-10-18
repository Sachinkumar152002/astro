@extends(themeView().'layout')

@section("title", "Dashboard | ".config("app.name"))

@push('style')
<style media="screen">
.ast_dash { --astro-purple:#4c176f; --astro-gold:#e89008; --astro-ink:#21183e; }
.ast_dash .astro-profile-card,.ast_dash .astro-time-box,.ast_dash .astro-revenue-stats,.ast_dash .tab-scroll-wrapper { border:1px solid #eee4d8 !important; border-radius:14px; box-shadow:0 5px 18px rgba(43,27,67,.08); }
.ast_dash .astro-profile-card { padding:20px; border-top:3px solid var(--astro-gold) !important; }
.ast_dash .astro-profile-card > .text-center.mt-2.position-relative { margin-top:16px !important; min-height:42px; }
.ast_dash .astro-revenue-stats { background:linear-gradient(115deg,#fff 0%,#fff8eb 100%); flex-wrap:wrap; margin-top:14px; padding:14px 8px; }
.ast_dash .astro-revenue-box { flex:1; text-align:center; padding:8px 6px; border-right:1px solid #eee4d8; }
.ast_dash .astro-revenue-box:last-child { border:0; }
.ast_dash .astro-amount { color:var(--astro-purple); font-weight:700; }
.ast_dash .astro-label,.ast_dash .astro-time-title { color:#514867; font-weight:600; }
.ast_dash .astro-time-box { padding:16px 8px; background:#fff; }
.ast_dash .astro-time-value { color:var(--astro-purple); }
.ast_dash .astro-time-container { margin-top:18px; gap:14px; }
.ast_dash .astro-revenue-btn { position:relative; left:auto; padding:10px 24px; background:var(--astro-purple); color:#fff; border:0 !important; border-bottom:3px solid var(--astro-gold) !important; border-radius:10px; box-shadow:0 5px 14px rgba(76,23,111,.2); }
.ast_dash .status-tab li .nav-link.active { background:var(--astro-purple) !important; }
.dashboad_section { background:linear-gradient(145deg,#fffaf4 0%,#f8f3eb 52%,#fffaf4 100%) !important; padding:34px 0 48px; min-height:calc(100vh - 150px); }
.dashboad_section .dashbord_card { background:#fff; border:1px solid #eee4d8; border-radius:14px; box-shadow:0 5px 18px rgba(43,27,67,.08); overflow:hidden; }
.dashboad_section .astro-list ul { padding:12px 16px 4px !important; }
.dashboad_section .astro-list .list-group-item { border-color:#f0e8df !important; margin:0 !important; padding:12px 8px !important; transition:background .2s ease; }
.dashboad_section .astro-list .list-group-item:hover { background:#fff8eb; }
.dashboad_section .astro-list .list-group-item a { color:#36234a !important; font-weight:500; text-decoration:none; }
.ast_dash .mt-3.border-n.radius { border:1px solid #eee4d8; border-radius:14px; overflow:hidden; background:#fff; box-shadow:0 5px 18px rgba(43,27,67,.08); }
.ast_dash .tab-scroll-wrapper { border:0 !important; border-radius:0; padding:18px 16px 0; }
.ast_dash .tab-scroll-wrapper h4 { color:var(--astro-ink); font-weight:700; }
.ast_dash .astro-account-tab { gap:8px; margin:0 !important; }
.ast_dash .astro-account-tab .nav-link { border:1px solid #eee4d8; border-radius:9px 9px 0 0; color:#514867; font-weight:700; padding:10px 16px; }
.ast_dash .status-tab li .nav-link.active { color:#fff !important; }
.ast_dash .status-list { padding:10px 16px; background:#fff !important; }
.ast_dash .status-item { border:1px solid #f0e8df; border-radius:10px; margin:7px 0; padding:13px 15px; color:#36234a; transition:background .2s ease,border-color .2s ease; }
.ast_dash .status-item:hover { background:#fffaf1; border-color:#efd39c; }
.ast_dash .status-dot { flex:0 0 18px; }
.ast_dash .live-card,.ast_dash .policy-box { border:1px solid #e8e1f0 !important; border-radius:14px; box-shadow:0 5px 18px rgba(47,32,71,.08); padding:18px; }
.ast_dash .same-card { height:100%; border:1px solid #e8e1f0 !important; border-radius:14px; box-shadow:0 4px 14px rgba(47,32,71,.07); transition:transform .2s ease,box-shadow .2s ease; }
.ast_dash .same-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(47,32,71,.12); }
.ast_dash .same-card p { color:#393148; }
.ast_dash .same-icon { background:linear-gradient(135deg,var(--astro-purple),var(--astro-gold)) !important; }
.ast_dash .same-icon img { filter:brightness(0) invert(1); }
.ast_dash .astro-revenue-box { min-width:145px; }
.ast_dash .mt-3.border-n.radius { margin-top:20px !important; }
.ast_dash .same-menu-box>a { display:block; height:100%; text-decoration:none; }
.astrologer-dashboard-skeleton { position:fixed; inset:0; z-index:20000; overflow:auto; background:#fffaf4; transition:opacity .3s ease; }
.astrologer-dashboard-skeleton.is-dismissing { opacity:0; pointer-events:none; }
.astro-dashboard-skeleton__header { height:84px; padding:0 max(4vw,24px); display:flex; align-items:center; gap:12px; background:#fffaf4; border-bottom:1px solid #eee4d8; }
.astro-dashboard-skeleton__header>div { display:flex; align-items:center; gap:12px; margin-left:auto; }
.astro-dashboard-skeleton__layout { max-width:1320px; margin:34px auto; padding:0 18px; display:grid; grid-template-columns:288px minmax(0,1fr); gap:24px; align-items:start; }
.astro-dashboard-skeleton__sidebar,.astro-dashboard-skeleton__profile,.astro-dashboard-skeleton__revenue,.astro-dashboard-skeleton__times>i,.astro-dashboard-skeleton__availability { border:1px solid #eee4d8; border-radius:14px; background:#fff; box-shadow:0 5px 18px rgba(43,27,67,.06); }
.astro-dashboard-skeleton__sidebar { min-height:500px; padding:24px 18px; display:grid; align-content:start; gap:18px; }
.astro-dashboard-skeleton__main { min-width:0; display:grid; gap:16px; }
.astro-dashboard-skeleton__profile { min-height:145px; padding:20px; display:flex; align-items:center; gap:16px; }
.astro-dashboard-skeleton__profile>div { flex:1; display:grid; gap:12px; }
.astro-dashboard-skeleton__revenue { min-height:84px; display:grid; grid-template-columns:repeat(4,1fr); align-items:center; padding:14px; }
.astro-dashboard-skeleton__times { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
.astro-dashboard-skeleton__times>i { height:90px; }
.astro-dashboard-skeleton__availability { min-height:180px; padding:20px; display:grid; grid-template-columns:repeat(3,1fr); align-content:start; gap:14px; }
.astro-dashboard-skeleton__availability>b { grid-column:1/-1; width:45%; height:22px !important; margin-bottom:6px; }
.astrologer-dashboard-skeleton i,.astrologer-dashboard-skeleton b,.astrologer-dashboard-skeleton span { display:block; height:14px; border-radius:8px; background:linear-gradient(100deg,#eee8e2 20%,#fff7e9 40%,#eee8e2 60%); background-size:220% 100%; animation:astro-dashboard-shimmer 1.35s linear infinite; }
.astro-dashboard-skeleton__logo { width:205px; height:52px !important; object-fit:contain; background:none !important; animation:none !important; }
.astro-dashboard-skeleton__header>div i { width:42px; height:42px; border-radius:50%; }
.astro-dashboard-skeleton__header>div b { width:72px; height:34px; border-radius:20px; }
.astro-dashboard-skeleton__profile>i { flex:0 0 84px; width:84px; height:84px; border-radius:12px; }
.astro-dashboard-skeleton__profile b { width:180px; height:18px; }
.astro-dashboard-skeleton__profile span:nth-child(2) { width:35%; }
.astro-dashboard-skeleton__profile span:nth-child(3) { width:48%; }
.astro-dashboard-skeleton__sidebar i { height:18px; }
.astro-dashboard-skeleton__revenue i { width:65%; height:18px; justify-self:center; }
@keyframes astro-dashboard-shimmer { to { background-position-x:-220%; } }
@media(max-width:767.98px){ .dashboad_section { padding:22px 0 32px; } .ast_dash .astro-revenue-box { flex:1 1 50%; min-width:50%; } .ast_dash .astro-revenue-box:nth-child(2) { border-right:0; } }
@media(max-width:575.98px){ .ast_dash .astro-time-container { display:grid; grid-template-columns:1fr; } .ast_dash .astro-revenue-box { min-width:50%; } }
@media(max-width:767.98px){ .astro-dashboard-skeleton__header { height:64px; padding:0 14px; } .astro-dashboard-skeleton__logo { width:125px; height:38px !important; } .astro-dashboard-skeleton__header>div { gap:6px; } .astro-dashboard-skeleton__header>div i { width:30px; height:30px; } .astro-dashboard-skeleton__layout { grid-template-columns:1fr; margin:20px auto; padding:0 14px; } .astro-dashboard-skeleton__sidebar { display:none; } .astro-dashboard-skeleton__profile { min-height:112px; padding:14px; } .astro-dashboard-skeleton__profile>i { flex-basis:64px; width:64px; height:64px; } .astro-dashboard-skeleton__revenue { grid-template-columns:repeat(2,1fr); gap:12px; } .astro-dashboard-skeleton__times { grid-template-columns:1fr; gap:10px; } .astro-dashboard-skeleton__times>i { height:68px; } .astro-dashboard-skeleton__availability { grid-template-columns:1fr; min-height:160px; } .astro-dashboard-skeleton__availability>span:nth-of-type(n+2) { display:none; } }
.left-icon i {
  color: #FF0000;   /* YouTube Red */
  font-size: 20px;  /* Size adjust kar sakte ho */
}
.booking .card {
         border: none;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s, box-shadow 0.3s;
     }
    .booking .card:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
     }
    .booking .card img {
         border-top-left-radius: 10px;
         border-top-right-radius: 10px;
         height: 200px;
         object-fit: cover;
     }
    .booking .card-title {
         font-size: 1.2rem;
         font-weight: 600;
     }
     .booking .card-text {
         color: #6c757d;
     }
     .ast_dash{
       padding-top: 8px;
     }
</style>
@endpush

@section('content')
  @php
    $_token=request()->has('_token')?request()->get("_token"):'';
  @endphp
<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-0  mtt-7 ast_dash">
        <div class="astro-profile-card">
                <div class="astro-profile-row justify-content-between">

                    <!-- Image + Rating -->
                    <div class="d-flex">
                      <div class="astro-photo-box">
                          <img src="{{auth()->guard('astrologer')->user() && auth()->guard('astrologer')->user()->image ? auth()->guard('astrologer')->user()->image->image(100,100):prfileImg()}}" class="astro-photo" alt="{{auth()->guard('astrologer')->user()->uuid}}">
                          <div class="astro-rating">
                              <i class="fa fa-star"></i> {{$astrologer->rating}}/5
                          </div>
                          <div class="position-relative">
                            <div class="astro-status">

                            </div>
                          </div>
                      </div>

                      <!-- Details -->
                      <div class="astro-info">
                          <h5 class="astro-name">{{auth()->guard('astrologer')->user()->firstname?:'User'}}</h5>
                            @if(auth()->guard('astrologer')->user()->is_verified)
                          <div class="astro-uuid">UUID: {{auth()->guard('astrologer')->user()->uuid}}</div>
                          @endif
                        <div class="mt-2">
                          <div class="astro-login-label">Your last login was :</div>
                          <div class="astro-login-time">{{auth()->guard('astrologer')->user()?date('d M Y h:i A', strtotime(auth()->guard('astrologer')->user()->last_logined)):''}}</div>
                        </div>
                      </div>
                    </div>

                    <!-- Status -->


                </div>
                <div class="text-center mt-2 position-relative">
                    <button class="astro-revenue-btn">My Revenue</button>
                </div>
            </div>
            @php
              $todayAmountChatConsultations = $chatConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
              $todayAmountCallConsultations = $callConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
              $todayAmountVideoConsultations = $videoConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
              $todayAmountGifts = $gifts->where('created_at','=',date("Y-m-d"))->sum("price");
              $todayAmount = $todayAmountChatConsultations + $todayAmountCallConsultations + $todayAmountVideoConsultations + $todayAmountGifts;

              $monthAmountChatConsultations = $chatConsultations
              ->filter(function($item) {
                  return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
              })
              ->sum("astrologer_amount");
              $monthAmountCallConsultations = $callConsultations
              ->filter(function($item) {
                  return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
              })
              ->sum("astrologer_amount");
              $monthAmountVideoConsultations = $videoConsultations
              ->filter(function($item) {
                  return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
              })
              ->sum("astrologer_amount");
              $monthAmountGifts = $gifts
              ->filter(function($item) {
                  return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
              })
              ->sum("price");
              $monthAmount = $monthAmountChatConsultations + $monthAmountCallConsultations + $monthAmountVideoConsultations + $monthAmountGifts;

              $totalAmountChatConsultations = $chatConsultations->sum("astrologer_amount");
              $totalAmountCallConsultations = $callConsultations->sum("astrologer_amount");
              $totalAmountVideoConsultations = $videoConsultations->sum("astrologer_amount");
              $totalAmountGifts = $gifts->sum("price");
              $totalAmount = $totalAmountChatConsultations + $totalAmountCallConsultations + $totalAmountVideoConsultations + $totalAmountGifts;
            @endphp

            <div class="astro-revenue-stats">

               <div class="astro-revenue-box">
                   <div class="astro-amount">&#8377; {{ sprintf("%.2f", $todayAmount) }}</div>
                   <div class="astro-label">TODAY</div>
               </div>

               <div class="astro-revenue-box">
                 <div class="astro-amount">&#8377; {{ sprintf("%.2f", $monthAmount) }}</div>
                   <div class="astro-label">LAST 30 DAYS</div>
               </div>

               <div class="astro-revenue-box">
                   <div class="astro-amount">&#8377; {{ sprintf("%.2f", $totalAmount) }}</div>
                   <div class="astro-label">LIFE TIME</div>
               </div>

               <div class="astro-revenue-box">
                   <div class="astro-amount">&#8377; {{ sprintf("%.2f", $astrologer->wallet) }}</div>
                   <div class="astro-label">WALLET BALANCE</div>
               </div>

           </div>

           @php
             $consultationMinutes = function($items) {
               return $items->sum(function($item) {
                 $userStart = $item->user_start_time ? strtotime($item->scheduled_date.' '.$item->user_start_time) : false;
                 $userEnd = $item->user_end_time ? strtotime($item->scheduled_date.' '.$item->user_end_time) : false;
                 $astroStart = $item->astrologer_start_time ? strtotime($item->scheduled_date.' '.$item->astrologer_start_time) : false;
                 $astroEnd = $item->astrologer_end_time ? strtotime($item->scheduled_date.' '.$item->astrologer_end_time) : false;
                 if (!$userStart || !$userEnd || !$astroStart || !$astroEnd) return 0;
                 $start = max($userStart, $astroStart);
                 $end = min($userEnd, $astroEnd);
                 return $end > $start ? (int) ceil(($end - $start) / 60) : 0;
               });
             };
             $totalTimeChatConsultations = $consultationMinutes($chatConsultations);
             $totalTimeCallConsultations = $consultationMinutes($callConsultations);
             $totalTimeVideoConsultations = $consultationMinutes($videoConsultations);
           @endphp

           <div class="astro-time-container">

           <div class="astro-time-box">
               <div class="astro-time-title">Total Chat Time</div>
               <div class="astro-time-value">{{ $totalTimeChatConsultations }} Min.</div>
           </div>

           <div class="astro-time-box">
               <div class="astro-time-title">Total Call Time</div>
               <div class="astro-time-value">{{ $totalTimeCallConsultations }} Min.</div>
           </div>

           <div class="astro-time-box">
               <div class="astro-time-title">Total Video Time</div>
               <div class="astro-time-value">{{ $totalTimeVideoConsultations }} Min.</div>
           </div>

       </div>
         <div class="mt-3 border-n radius">
         <div class="tab-scroll-wrapper bg-light radius">
           <h4 class="ms-2 mb-3 text-center text-md-start">Update Availability Status</h4>

           <ul id="myTab" role="tablist" class="status-tab nav dashboard_tab nav-tabs dash-tab astro-account-tab custom-tabs ms-2">
             <li class="nav-item">
                 <a id="chat-tab" data-bs-toggle="tab" href="#chat" class="nav-link active">CHAT</a>
             </li>
             <li class="nav-item">
                 <a id="phone-tab" data-bs-toggle="tab" href="#phone" class="nav-link">PHONE CALL</a>
             </li>
             <li class="nav-item">
                 <a id="video-tab" data-bs-toggle="tab" href="#video" class="nav-link">VIDEO CALL</a>
             </li>
           </ul>
         </div>
         <div class="tab-content nb-light">
           <!-- CHAT -->
           <div id="chat" class="tab-pane fade show active">
             <div class="status-list bg-light">
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'online'])}}')">
                   I am Online
                   <div class="status-dot {{ $astrologer->chat == 1 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'offline'])}}')">
                   I am Offline
                   <div class="status-dot {{ $astrologer->chat == 2 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'busy'])}}')">
                   I am Busy
                   <div class="status-dot {{ $astrologer->chat == 3 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'hide'])}}')">
                   Hide
                   <div class="status-dot {{ $astrologer->chat == 0 ? 'status-active' : '' }}"></div>
                 </div>
             </div>
           </div>
           <!-- PHONE -->
           <div id="phone" class="tab-pane fade">
             <div class="status-list bg-light">
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'online'])}}')">
                   I am Online
                   <div class="status-dot {{ $astrologer->call == 1 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'offline'])}}')">
                   I am Offline
                   <div class="status-dot {{ $astrologer->call == 2 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'busy'])}}')">
                   I am Busy
                   <div class="status-dot {{ $astrologer->call == 3 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'hide'])}}')">
                   Hide
                   <div class="status-dot {{ $astrologer->call == 0 ? 'status-active' : '' }}"></div>
                 </div>
             </div>
           </div>
           <!-- VIDEO -->
           <div id="video" class="tab-pane fade">
             <div class="status-list bg-light">
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'online'])}}')">
                   I am Online
                   <div class="status-dot {{ $astrologer->video == 1 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'offline'])}}')">
                   I am Offline
                   <div class="status-dot {{ $astrologer->video == 2 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'busy'])}}')">
                   I am Busy
                   <div class="status-dot {{ $astrologer->video == 3 ? 'status-active' : '' }}"></div>
                 </div>
                 <div class="status-item" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'hide'])}}')">
                   Hide
                   <div class="status-dot {{ $astrologer->video == 0 ? 'status-active' : '' }}"></div>
                 </div>
             </div>
           </div>
         </div>
      </div>
      @if($astrologerLive)
         <div class="live-card mt-3">
              <div class="live-header">
                  <span class="live-dot"></span>
                  GO LIVE NOW
              </div>
              <div class="schedule-title">My Schedule</div>
              <div class="schedule-row">
                <strong>Start at:</strong> {{ date("d-m-Y h:i a",strtotime($astrologerLive->start_at)) }}
              </div>
              <div class="schedule-row">
                <strong>End at:</strong> {{ date("d-m-Y h:i a",strtotime($astrologerLive->end_at)) }}
              </div>
              <div class="arrow-btn">
              <a href="{{ route('astrologer.live.index') }}">
                <i class="bi bi-chevron-right"></i>
              </a>
          </div>
          </div>
      @endif

        <div class="container mt-4 mb-2 px-1">
         <div class="row g-3 same-menu-row">

             <!-- Item -->
             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('kundali.index') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#0d6efd;">
                         <img src="/assets/img/Kundli.png" alt="">
                     </div>
                     <p>Kundli</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('panchang') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#28a745;">
                         <img src="/assets/img/Panchang-1.png" alt="">
                     </div>
                     <p>Panchang</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('horoscopes.index') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#6f42c1;">
                         <img src="/assets/img/horoscope.png" alt="">
                     </div>
                     <p>Horoscope</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('kundalimatching') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#e63946;">
                         <img src="/assets/img/matchmaking.png" alt="">
                     </div>
                     <p>Matching</p>
                 </div>
               </a>
             </div>
             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('blog.category', ['festival']) }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#d4a017;">
                         <img src="/assets/img/Festivals.png" alt="">
                     </div>
                     <p>Festivals</p>
                 </div>
               </a>
             </div>

             <!-- Row 2 -->
             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.earnings') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#009670;">
                         <img src="/assets/img/gift.png" alt="">
                     </div>
                     <p>Gifts</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.consultations') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#28a745;">
                         <img src="/assets/img/chat.png" alt="">
                     </div>
                     <p>Chat</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.consultations') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#5bc0de;">
                         <img src="/assets/img/phone.png" alt="">
                     </div>
                     <p>Call</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.consultations') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#ff4444;">
                         <img src="/assets/img/video-camera.png" alt="">
                     </div>
                     <p>Video</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.withdraws') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#8e24aa;">
                         <img src="/assets/img/redeem.png" alt="">
                     </div>
                     <p>Redeem</p>
                 </div>
               </a>
             </div>

             <!-- Row 3 -->
             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.earnings') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#6f42c1;">
                         <img src="/assets/img/savings.png" alt="">
                     </div>
                     <p>Total Earning</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.notification') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#e31a91;">
                         <img src="/assets/img/notification.png" alt="">
                     </div>
                     <p>Notification</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.support') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#114712;">
                         <img src="/assets/img/support.png" alt="">
                     </div>
                     <p>Support</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-2 same-menu-box">
               <a href="{{ route('astrologer.newsletter') }}">
                 <div class="same-card">
                     <div class="same-icon" style="background:#1fa7ff;">
                         <img src="/assets/img/email.png" alt="">
                     </div>
                     <p>Newsletter</p>
                 </div>
               </a>
             </div>

         </div>
     </div>
      <div class="video-accordion">
        <a href="{{ fromSetting('youtube_channel_link_astrologer_training') }}" target="_blank">
          <button class="video-acc-btn">
            <span class="left-icon">
                <i class="fab fa-youtube"></i>
                </span>
              <span class="video-title">Training Video</span>
              <span class="right-arrow">Ã¢â‚¬Âº</span>
          </button>
        </a>

          <div class="video-acc-content">
              <div class="video-wrapper">
                  <video width="100%" controls>
                      <source src="dummy-video.mp4" type="video/mp4">
                      Your browser does not support the video tag.
                  </video>
              </div>
          </div>
      </div>

      @if($astrologer->referral_code)
      <!-- Referral Code Card -->
      <div class="container mt-4 mb-2 px-1">
        <div class="card border-primary shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-gift"></i> My Referral Code</h5>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-center p-3 bg-light rounded">
                  <small class="text-muted d-block mb-2">Your Referral Code</small>
                  <h4 class="mb-0 text-primary fw-bold">{{ $astrologer->referral_code }}</h4>
                  <div class="mt-2">
                    <a href="javascript:void(0)" onclick="copyReferralCode('{{ $astrologer->referral_code }}')" class="p-add text-black fw-bold">Copy Code</a>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="p-3 bg-light rounded">
                  <small class="text-muted d-block mb-3">Share Your Referral Code</small>
                  <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                    <!-- Facebook Share -->
                    <a href="javascript:void(0)" onclick="shareOnFacebook('{{ $astrologer->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #1877f2; border-color: #1877f2; min-width: 120px; justify-content: center;">
                      <i class="bi bi-facebook" style="font-size: 18px; color: white;"></i>
                      <span>Facebook</span>
                    </a>
                    <!-- Messenger Share -->
                    <a href="javascript:void(0)" onclick="shareOnMessenger('{{ $astrologer->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #0084ff; border-color: #0084ff; min-width: 120px; justify-content: center;">
                      <i class="bi bi-messenger" style="font-size: 18px; color: white;"></i>
                      <span>Messenger</span>
                    </a>
                    <!-- WhatsApp Share -->
                    <a href="javascript:void(0)" onclick="shareOnWhatsApp('{{ $astrologer->referral_code }}')" class="btn btn-sm d-flex align-items-center gap-2 text-white" style="background-color: #25d366; border-color: #25d366; min-width: 150px; justify-content: center;">
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

      <div class="policy-box">
        <h4 class="text-center text-md-start">POLICIES</h4>

        <ul>
          @foreach($policies as $p)
          {!! substr($p->content,0,236) !!}
          @endforeach
        </ul>
        @if(isset($policies) && $policies->count() > 0)
          @php
            $lastPolicy = $policies->last();
          @endphp
          <a href="{{ route('pages', $lastPolicy->slug) }}" target="_blank" class="policy-more">View More...</a>
        @endif
        </div>
      </div>
     </div>
   </div>
 </div>
</div>
</section>
@endsection
@push('scripts')
@if($astrologer->referral_code)
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
<script type="text/javascript">
  function handleStatus(that, url){
    $.ajax({
      url:url,
      method:'GET',
      success:function(response){
        $(that).parent().find('.status-dot').removeClass('status-active');
        $(that).find('.status-dot').addClass('status-active');
      },
      error:function(error){
        console.log(error)
      }
    });
  }
</script>
<script>
document.querySelector(".video-acc-btn").addEventListener("click", function () {
    // let content = document.querySelector(".video-acc-content");
    // let arrow = document.querySelector(".right-arrow");
    //
    // if (content.style.maxHeight) {
    //     content.style.maxHeight = null;
    //     arrow.style.transform = "rotate(0deg)";
    // } else {
    //     content.style.maxHeight = content.scrollHeight + "px";
    //     arrow.style.transform = "rotate(90deg)";
    // }
});
</script>

@endpush
