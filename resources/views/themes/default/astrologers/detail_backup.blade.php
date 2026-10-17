<!-- banner section start -->
<section class=" mt-md-0 mt-md-5 mt-lg-2 pad-ap">
  <div class="container consult_banner rounded mt-md-0 mt-5 ff">
    <div class="row">
      <div class="col-xl-7 col-lg-7 mt-topn ast_detail star_box star-ne">
        <div class="card con-de-card">
          <div class="card-header card-detail border-0 p-0">
            <div class="row gx-0">
              <div class="col-md-3 col-3  card_image justify-content-start d-flex flex-column">

                <!-- user details start -->
                @php
                  $name = '';
                  $email = '';
                  $phone = '';
                  $birth_date = '';
                  $birth_time = '';
                  $birth_place = '';
                  $type = $type;
                  $gender = '';
                  $maritial_status = '';
                  $occupation = '';
                  $consult_type = "now";
                  $slot_time = '5';
                  if($user){
                      $user_id = $user->id;
                      $name = $user->name;
                      $email = $user->email;
                      $phone = $user->phone;
                      $birth_date = $user->birth_date;
                      $birth_time = $user->birth_time;
                      $birth_place = $user->birth_place;
                      if($user->gender == 1){
                        $gender = "male";
                      }elseif($user->gender == 2){
                        $gender = "female";
                      }elseif($user->gender == 3){
                        $gender = "other";
                      }
                  }
                @endphp
                <!-- user details end -->

                @php
                  $chatStatus = (int) $astrologer->chat;
                  $callStatus = (int) $astrologer->call;
                  $videoStatus = (int) $astrologer->video;
                  $is_busy = ($chatStatus === 3 || $callStatus === 3 || $videoStatus === 3 || $diffInSeconds > 0);
                  $is_online = ($chatStatus === 1 || $callStatus === 1 || $videoStatus === 1);
                  $astrologer_status = $is_busy ? 'bussy' : ($is_online ? 'online' : 'offline');
                  $show_notify = ($astrologer_status === 'offline');
                  $status_border = $astrologer_status === 'bussy' ? 'bor-red' : ($astrologer_status === 'online' ? 'bor-green' : 'bor-gray');
                @endphp
                @if($astrologer_status === 'bussy')
                  <div class="busy">Bussy</div>
                @elseif($astrologer_status === 'online')
                  <div class="online"> </div>
                @else
                  <div class="offline"></div>
                  <div class="offline"></div>
                @endif
                  <div class="card-im position-relative {{$status_border}}">
                    <img src="{{ $astrologer->image ? $astrologer->image->image(175) : no_image() }}" class="astro-img border-white img-fluid radius-7" alt="{{ $astrologer->display_name }}">
                    <div class="d-block d-md-none">
                      <div class="de-share mt-2">
                        <a class="a2a_dd" href="https://www.addtoany.com/share">
                        <i class="bi bi-share share-i text-danger"></i>
                        </a>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="col-9 col-md-5 card_detail p-md-0">
                  <h3 class=" fs-5 fs-md-3 text-capitalize fw-bold text ms-3 as-h mb-0">{{ $astrologer->display_name }}</h3>
                  <ul class="list-group list-group-flush charge-list de-card2 p-0 ms-2 ps-0 star-n">

                    <li class="list-group-item fs-md-6 fs-7 lh-base d-flex text-break gap-2">
                      <i class="bi bi-award"></i>
                      <span class="ms-1"> {{ $astrologer->my_expertise_full }}</span>
                    </li>
                    <li class="list-group-item fs-md-6 fs-7 lh-base d-flex text-break gap-2">
                      <i class="bi bi-menu-button-wide"></i>
                      <span class="ms-1">{{ $astrologer->my_language }}</span>
                    </li>
                    <li class="list-group-item fs-md-6 fs-7 lh-base gap-2">
                      <i class="bi bi-mortarboard"></i>
                      <span class="ms-1">{{ $astrologer->year_exp }} years</span>
                    </li>
                      <li>
                        @if($show_notify)
                              <button class="book_nowbut pointer video-b bg-warning d-block d-md-none" onclick="handleNotifyMe(this,'{{ $astrologer->id }}','astrologer-online')"><i class="fa fa-bell text-white" aria-hidden="true"></i> &nbsp; Notify Me</button>

                        @endif
                      </li>
                    <li>
                      <div class="d-md-block d-none">
                        <div class="d-flex">
                          @if($show_notify)
                              <button class="book_nowbut pointer video-b bg-warning" onclick="handleNotifyMe(this,'{{ $astrologer->id }}','astrologer-online')"><i class="fa fa-bell text-white" aria-hidden="true"></i> &nbsp; Notify Me</button>

                          @endif
                          <div class="de-share ms-2">
                            <a class="a2a_dd" href="https://www.addtoany.com/share">
                            <i class="bi bi-share share-i text-danger"></i>
                            </a>
                          </div>
                        </div>

                      </div>
                    </li>
                  </ul>
                  @php
                    $todayDate = date("Y-m-d");
                    $specialPriceFromDate = $astrologer->sp_from_date;
                    $specialPriceToDate = $astrologer->sp_to_date;
                    $hasSpecialPrice = false;

                    if($specialPriceFromDate && ($specialPriceFromDate <= $todayDate) && ($specialPriceToDate >= $todayDate)){
                      $hasSpecialPrice = true;
                    }

                    $activeTabFunction = "activeTab(this)";
                    $handleUserLoginModalFunction = "handleUserLoginModal('open')";
                    $onClickTabFunction = $user ? $activeTabFunction : $handleUserLoginModalFunction;
                  @endphp

              </div>
              <div class="col-md-4 d-none d-md-block">
                @if(!$loggedInAstrologer && $diffInSeconds <= 0)
                  <ul class="icon_list">
                    <!-- chat button start -->
                    @if($chatStatus === 1)
                      @php
                        $chatSpecialPrice = $astrologer->chat_special_price;
                        $chatPrice = $astrologer->chat_price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          @if($astrologer->first_chat_free && $takeConsultation == false)
                            <div class="d-flex align-items-center justify-content-between">
                              <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}" data-type="chat" data-price="{{ $chatSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                                <span><i class="fa fa-commenting text-green me-1"></i> Chat Rate</span>
                                <br>
                                <small>
                                  <strike class="text-black">₹{{ $chatSpecialPrice }}</strike>
                                </small>
                                <span class="text-danger fw-bold">Free</span>
                              </button>
                            </div>
                          @else
                            <div class="d-flex align-items-center justify-content-between">
                              <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}" data-type="chat" data-price="{{ $chatSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                                <span><i class="fa fa-commenting text-green me-1"></i> Chat Rate</span>
                                <br>
                                <small>
                                  <strike class="text-black">₹{{ $chatPrice }}</strike>
                                </small>
                                <span class="text-danger fw-bold">₹{{ $chatSpecialPrice }}/min</span>
                              </button>
                            </div>
                          @endif
                        @else
                          @if($astrologer->first_chat_free && $takeConsultation == false)
                            <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}"  data-type="chat" data-price="{{ $chatPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                              <span><i class="fa fa-commenting text-green me-1"></i> Chat Rate</span>
                              <br>
                              <small>
                                <strike class="text-black">₹{{ $chatPrice }}</strike>
                              </small>
                              <span class="text-danger fw-bold">Free</span>
                            </button>
                          @else
                            <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}"  data-type="chat" data-price="{{ $chatPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                              <span><i class="fa fa-commenting text-green me-1"></i> Chat Rate</span>
                              <br>
                              <span class="text-danger fw-bold">₹{{ $chatPrice }}/min</span>
                            </button>
                          @endif
                        @endif
                      </li>
                    @endif
                    <!-- chat button end -->
                    <!-- call button start -->
                    @if($callStatus === 1)
                      @php
                        $callSpecialPrice = $astrologer->call_special_price;
                        $callPrice = $astrologer->call_price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-call {{$type=='call'?'active':''}}"  data-type="call" data-price="{{ $callSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="calltab">
                            <span class="text-black"><i class="fa fa-volume-control-phone text-purple me-1"></i>Call Rate</span>
                            <br>
                            <small>
                              <strike class="text-black">₹{{ $callPrice }}</strike>
                            </small>
                            <span class="text-danger fw-bold">₹{{ $callSpecialPrice }}/min</span>
                          </button>
                        @else
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-call {{$type=='call'?'active':''}}"  data-type="call" data-price="{{ $callPrice }}" onclick="{{ $onClickTabFunction }}" id="calltab">
                            <span class="text-black"><i class="fa fa-volume-control-phone text-purple me-1"></i> Call Rate</span>
                            <br>
                            <span class="text-danger fw-bold">₹{{ $callPrice }}/min</span>
                          </button>
                        @endif
                      </li>
                    @endif
                    <!-- call button end -->
                    <!-- video button start -->
                    @if($videoStatus === 1)
                      @php
                        $videoSpecialPrice = $astrologer->special_price;
                        $videoPrice = $astrologer->price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-video {{$type=='video'?'active':''}}"  data-type="video" data-price="{{ $videoSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="videotab">
                            <span class="text-black"><i class="bi bi-camera-video-fill video_icon me-1"></i> Video Call Rate</span>
                            <br>
                            <small>
                              <strike class="text-black">₹{{ $videoPrice }}</strike>
                            </small>
                            <span class="text-danger fw-bold">₹{{ $videoSpecialPrice }}/min</span>
                          </button>
                        @else
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-video {{$type=='video'?'active':''}}"  data-type="video" data-price="{{ $videoPrice }}" onclick="{{ $onClickTabFunction }}" id="videotab">
                            <span class="text-black"><i class="bi bi-camera-video-fill video_icon me-1"></i> Video Rate</span>
                            <br>
                            <span class="text-danger fw-bold">₹{{ $videoPrice }}/min</span>
                          </button>
                        @endif
                      </li>
                    @endif
                    <!-- video button end -->
                  </ul>
                @else
                  <ul class="icon_list">
                    <li>
                      <button class="book_nowbut border border-danger" id="waittab">
                        <span class="text-danger" id="waitingP">Wait: {{ $waitingTime }}</span>
                      </button>
                    </li>
                  </ul>
                @endif
              </div>
              <div class="col-md-4 d-block d-md-none phn">
                @if(!$loggedInAstrologer && $diffInSeconds <= 0)
                  <ul class="icon_list">
                    <!-- chat button start -->
                    @if($chatStatus === 1)
                      @php
                        $chatSpecialPrice = $astrologer->chat_special_price;
                        $chatPrice = $astrologer->chat_price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          @if($astrologer->first_chat_free && $takeConsultation == false)
                            <div class="d-flex align-items-center justify-content-between">
                              <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}" data-type="chat" data-price="{{ $chatSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                                <span><i class="fa fa-commenting text-green me-1"></i> Chat</span>
                                <br>
                                <small>
                                  <strike class="text-black">₹{{ $chatSpecialPrice }}</strike>
                                </small>
                                <span class="text-danger fw-bold">Free</span>
                              </button>
                            </div>
                          @else
                            <div class="d-flex align-items-center justify-content-between">
                              <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}" data-type="chat" data-price="{{ $chatSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                                <span><i class="fa fa-commenting text-green me-1"></i> Chat</span>
                                <br>
                                <small>
                                  <strike class="text-black">₹{{ $chatPrice }}</strike>
                                </small>
                                <br>
                                <span class="text-danger fw-bold">₹{{ $chatSpecialPrice }}/min</span>
                              </button>
                            </div>
                          @endif
                        @else
                          @if($astrologer->first_chat_free && $takeConsultation == false)
                            <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}"  data-type="chat" data-price="{{ $chatPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                              <span><i class="fa fa-commenting text-green me-1"></i> Chat</span>
                              <br>
                              <small>
                                <strike class="text-black">₹{{ $chatPrice }}</strike>
                              </small>
                                  <br>
                              <span class="text-danger fw-bold">Free</span>
                            </button>
                          @else
                            <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-chat1 {{$type=='chat'?'active':''}}"  data-type="chat" data-price="{{ $chatPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                              <span><i class="fa fa-commenting text-green me-1"></i> Chat</span>
                              <br>
                              <span class="text-danger fw-bold">₹{{ $chatPrice }}/min</span>
                            </button>
                          @endif
                        @endif
                      </li>
                    @endif
                    <!-- chat button end -->
                    <!-- call button start -->
                    @if($callStatus === 1)
                      @php
                        $callSpecialPrice = $astrologer->call_special_price;
                        $callPrice = $astrologer->call_price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-call {{$type=='call'?'active':''}}"  data-type="call" data-price="{{ $callSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="calltab">
                            <span class="text-black"><i class="fa fa-volume-control-phone text-purple me-1"></i>Call</span>
                            <br>
                            <small>
                              <strike class="text-black">₹{{ $callPrice }}</strike>
                            </small>
                                <br>
                            <span class="text-danger fw-bold">₹{{ $callSpecialPrice }}/min</span>
                          </button>
                        @else
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-call {{$type=='call'?'active':''}}"  data-type="call" data-price="{{ $callPrice }}" onclick="{{ $onClickTabFunction }}" id="calltab">
                            <span class="text-black"><i class="fa fa-volume-control-phone text-purple me-1"></i> Call</span>
                            <br>
                            <span class="text-danger fw-bold">₹{{ $callPrice }}/min</span>
                          </button>
                        @endif
                      </li>
                    @endif
                    <!-- call button end -->
                    <!-- video button start -->
                    @if($videoStatus === 1)
                      @php
                        $videoSpecialPrice = $astrologer->special_price;
                        $videoPrice = $astrologer->price;
                      @endphp
                      <li>
                        @if($hasSpecialPrice)
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut  text-video {{$type=='video'?'active':''}}"  data-type="video" data-price="{{ $videoSpecialPrice }}" onclick="{{ $onClickTabFunction }}" id="videotab">
                            <span class="text-black"><i class="bi bi-camera-video-fill video_icon me-1"></i> Video Call</span>
                            <br>
                            <small>
                              <strike class="text-black">₹{{ $videoPrice }}</strike>
                            </small>
                                <br>
                            <span class="text-danger fw-bold">₹{{ $videoSpecialPrice }}/min</span>
                          </button>
                        @else
                          <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut text-video {{$type=='video'?'active':''}}"  data-type="video" data-price="{{ $videoPrice }}" onclick="{{ $onClickTabFunction }}" id="videotab">
                            <span class="text-black"><i class="bi bi-camera-video-fill video_icon me-1"></i> Video</span>
                            <br>
                            <span class="text-danger fw-bold">₹{{ $videoPrice }}/min</span>
                          </button>
                        @endif
                      </li>
                    @endif
                    <!-- video button end -->
                  </ul>
                @else
                  <ul class="icon_list">
                    <li>
                      <button class="book_nowbut border border-danger" id="waittab">
                        <span class="text-danger" id="waitingP">Wait: {{ $waitingTime }}</span>
                      </button>
                    </li>
                  </ul>
                @endif
                @php
                  $totalReviews = $astrologerReviews->count();
                  $rating = $astrologer->rating;
                @endphp
                <div class="star_box mt-3 d-block d-md-none px-0">
                  <div class="d-flex align-items-center justify-content-center order-4 order-md-2 review-as" style="flex-wrap: nowrap; white-space: nowrap; gap: 8px;">
                    <p class="fs-12 p-0 m-0 font-weight-normal color-darkgray d-flex align-items-center">
                      <span>Reviews :</span>&nbsp;
                      <span class="reviews-count fw-semibold">{{$totalReviews}}</span>
                    </p>
                    @if($rating > 0)
                      <span class="fs-11">|</span>
                      <p class="fs-11 p-0 m-0 d-flex align-items-center">
                        <span class="stars me-1">
                          @for($i = 1; $i <= $rating; $i++)
                            <i class="bi bi-star-fill"></i>
                          @endfor
                        </span>
                        <span class="fw-semibold">{{$rating}}</span>
                      </p>
                    @endif
                    <span class="fs-11">|</span>
                    <span class="fs-12 m-0">
                      Exp : <span class="fs-12 colorblack ml-1 fw-semibold">{{ $astrologer->year_exp }} Years</span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Include Bootstrap Icons in your <head> -->
      </div>

      <div class="col-md-12 col-lg-5 mt-lg-0 mt-3">
       <div class="d-none d-md-block border radius-8 px-3 py-1 start-two">
         <div class="star_box star-nn">
         <!-- Guarantee icons list -->
         <ul class="p-0 mt-2 mt-lg-4" style="flex-wrap: wrap; text-align: center;">
         <li class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light mt-1" style="transition: all 0.3s ease; height:52px">
           <span>Reviews :</span>
           <span class="reviews-count fw-semibold">{{$totalReviews}}</span>
           @if($rating > 0)
             <span class="font-16">|</span>
             <p class="font-16 p-0 m-0 d-flex align-items-center">
               <span class="stars me-1">
                 @for($i = 1; $i <= $rating; $i++)
                  <i class="bi bi-star-fill"></i>
                 @endfor
               </span>
               <span class="fw-semibold">{{$rating}}</span>
             </p>
           @endif
           <span class="font-16">|</span>
           <span class="font-16 m-0">
             Exp : <span class="colorblack ml-1 fw-semibold">{{ $astrologer->year_exp }} Years</span>
           </span>
         </li>

         <li class="d-flex align-items-center gap-2 px-3 py-2 rounded-3  bg-light mt-2" style="transition: all 0.3s ease; height:52px">
           <i class="bi bi-person-check-fill text-purple fs-5"></i>
           <span class="font-15 text-dark fw-medium">Verified Expert Astrologers</span>
         </li>

         <li class="d-flex align-items-center gap-2 px-3 py-2 rounded-3  bg-light mt-2" style="transition: all 0.3s ease; height:52px">
           <i class="bi bi-lock-fill text-warning fs-5"></i>
           <span class="font-15 text-dark fw-medium">100% Secure Payments</span>
         </li>
       </ul>
      </div>
       </div>
      </div>
    </div>
  </div>
</section>
