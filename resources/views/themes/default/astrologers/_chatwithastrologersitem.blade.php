<div class="col-lg-4 col-sm-6 col-12 mb-4">
  <div class="card astro-pro-card h-100 shadow-sm bg-white" style="padding: 28px 16px 16px 16px !important;">
    @php
      $todayDate = date("Y-m-d");
      $astrologerImage = $astrologer->image;
      $specialPriceFromDate = $astrologer->sp_from_date;
      $specialPriceToDate = $astrologer->sp_to_date;
      $hasSpecialPrice = false;
      if($specialPriceFromDate && ($specialPriceFromDate <= $todayDate) && ($specialPriceToDate >= $todayDate)){
        $hasSpecialPrice = true;
      }
      $chatPrice = $astrologer->chat_price;
      $chatSpecialPrice = $astrologer->chat_special_price;
      $astrologerType = $astrologer->type;
      $astrologerSlug = $astrologer->slug;
      $chatStatus = (int) $astrologer->chat;
      $show_notify = ($chatStatus === 0 || $chatStatus === 2);
      $astrologerStatus = ($chatStatus === 3) ? 'busy' : (($chatStatus === 1) ? 'online' : 'offline');
      $expertise = trim(str_replace(', ,', '', $astrologer->my_expertise), " ,") ?: ($astrologer->my_language ?: 'Vedic Astrology');
      $waitingTime = "0:0 m";
      $currentTime = strtotime(date('Y-m-d H:i:s'));
      $currentAppointment = $astrologer->currentAppointment();
      $withInThirtyMinutes = $astrologer->withInThirtyMinutes();
      if($currentAppointment || $withInThirtyMinutes){
        if(!$currentAppointment) $currentAppointment = $withInThirtyMinutes;
        $scheduledTimestamp = strtotime($currentAppointment->scheduled_date . ' ' . $currentAppointment->max_time.'+1minutes');
        $diffInSeconds = $scheduledTimestamp-$currentTime;
        if($diffInSeconds > 0){
          $minutes = floor($diffInSeconds / 60);
          $seconds = $diffInSeconds % 60;
          $waitingTime = "{$minutes}:{$seconds} m";
        }
      }
    @endphp

    <div class="d-flex gap-3">
      <!-- Left: Image & Rating -->
      <div class="text-center" style="width: 80px;">
        <div style="position: relative !important; width: 74px !important; height: 74px !important; margin: 0 auto 8px auto !important; display: block !important;">
          <a href="{{ $user_id ? route('astrologers.publicview', [$astrologerType, $astrologerSlug]) : 'javascript:void(0)' }}" {!! !$user_id ? 'onclick="handleUserLoginModal(\'open\')"' : '' !!} style="display:block; width:100%; height:100%;">
            <img src="{{ $astrologerImage ? $astrologerImage->image(200) : no_image() }}" alt="{{ $astrologer->display_name }}" class="rounded-circle" style="width: 74px; height: 74px; object-fit: cover; border: 2px solid #f59e0b; padding: 2px;">
          </a>
          <span class="pro-status-dot {{ $astrologerStatus }}" style="position: absolute !important; bottom: 2px !important; right: 5px !important; top: auto !important; left: auto !important; width: 14px !important; height: 14px !important; border-radius: 50% !important; border: 2px solid #fff !important; z-index: 99 !important; display: block !important; margin: 0 !important; transform: none !important;"></span>
        </div>
        <div class="badge bg-light text-dark border border-warning" style="font-size: 11px;"><i class="fa fa-star text-warning"></i> {{ $astrologer->rating ?: 'New' }}</div>
      </div>

      <!-- Right: Details -->
      <div class="flex-grow-1" style="width: calc(100% - 95px);">
        <a href="{{ $user_id ? route('astrologers.publicview', [$astrologerType, $astrologerSlug]) : 'javascript:void(0)' }}" {!! !$user_id ? 'onclick="handleUserLoginModal(\'open\')"' : '' !!} class="text-decoration-none">
          <h6 class="fw-bold mb-1 text-dark text-truncate d-flex align-items-center flex-wrap gap-1">
            {{ mySubStr($astrologer->display_name, 20) }}
            @if($astrologer->badge && $astrologer->badge->status == 1)
              <span style="background: #f59e0b; color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;"><i class="fa fa-star"></i> {{ $astrologer->badge->name }}</span>
            @endif
          </h6>
        </a>
        <div class="text-muted" style="font-size: 12px; margin-bottom: 4px;">{{ mySubStr($astrologer->my_language, 20) }} &bull; {{ $astrologer->year_exp }} Yrs</div>
        <div class="text-muted text-truncate" style="font-size: 11px; margin-bottom: 8px;">{{ $expertise }}</div>

        <div class="d-flex align-items-center gap-2 mt-2">
          @if($hasSpecialPrice)
            @if($astrologer->first_chat_free && $takeConsultation == false)
              <span class="text-success fw-bold" style="font-size: 14px;">FREE</span>
              <strike class="text-muted" style="font-size: 11px;">&#8377;{{ $chatSpecialPrice }}</strike>
            @else
              <span class="text-success fw-bold" style="font-size: 14px;">&#8377;{{ $chatSpecialPrice }}/min</span>
              <strike class="text-muted" style="font-size: 11px;">&#8377;{{ $chatPrice }}</strike>
            @endif
          @else
            @if($astrologer->first_chat_free && $takeConsultation == false)
              <span class="text-success fw-bold" style="font-size: 14px;">FREE</span>
              <strike class="text-muted" style="font-size: 11px;">&#8377;{{ $chatPrice }}</strike>
            @else
              <span class="text-success fw-bold" style="font-size: 14px;">&#8377;{{ $chatPrice }}/min</span>
            @endif
          @endif
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2 mt-3 pt-3 border-top">
      @if($show_notify)
        @if($user_id)
          <button class="pro-btn pro-btn-chat w-100" onclick="handleNotifyMe(this,'{{ $astrologer->id }}','astrologer-online')">Notify Me</button>
        @else
          <button class="pro-btn pro-btn-chat w-100" onclick="handleUserLoginModal('open')">Notify Me</button>
        @endif
      @elseif($waitingTime == '0:0 m')
        @if($user_id)
          <a href="{{ route('astrologers.publicview', [$astrologerType, $astrologerSlug, 'type' => 'chat']) }}" class="pro-btn pro-btn-chat text-decoration-none w-100">Chat</a>
        @else
          <a href="javascript:void(0)" onclick="handleUserLoginModal('open')" class="pro-btn pro-btn-chat text-decoration-none w-100">Chat</a>
        @endif
      @else
        <a href="{{ route('astrologers.publicview', [$astrologerType, $astrologerSlug]) }}" class="pro-btn pro-btn-chat text-decoration-none text-danger border-danger w-100">Wait {{$waitingTime}}</a>
      @endif
    </div>
  </div>
</div>