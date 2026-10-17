@php
  $astrologer=auth()->guard('astrologer')->user();
@endphp
<div class="col-sm-3 mt-4 mt-md-0">
  <div class=" d-none d-lg-block">

    <div class="accordion astro-filter" id="astrofilter">
      <!-- <div class="card border-0 user-card card-wallet">
  <div class="row align-items-center g-0">
    <div class="col-lg-3 col-12">
      <div class="profile-img-box">


      <img src="{{auth()->guard('astrologer')->user() && auth()->guard('astrologer')->user()->image ? auth()->guard('astrologer')->user()->image->image(100,100):prfileImg()}}" class="img-fluid rounded-circle user-img" alt="{{auth()->guard('astrologer')->user()->uuid}}">
   </div>
    </div>
    <div class="col-lg-12 col-12">
      <div class="d-lg-none d-block v-btn-box">
        <button type="button" name="button text-white" class="arrow-b btn verticalButton hide-in-webview">Menu</button>
        </div>
      <div class="col-lg-12 col-12">
        <div class="user_profile">
        <h5>{{auth()->guard('astrologer')->user()->firstname?:'User'}}</h5>
        @if(auth()->guard('astrologer')->user()->is_verified)
          <p>UUID: <strong>{{auth()->guard('astrologer')->user()->uuid}} </strong></p>
        @endif
        <span class="text last-log fs-7">Your last login was</span>
        <p class="last-log fs-7">{{auth()->guard('astrologer')->user()?date('d M Y h:i A', strtotime(auth()->guard('astrologer')->user()->last_logined)):''}}</p>
      </div>
    </div>
    </div>
    <div>
  </div>

  </div>
</div> -->
      <div class="dashbord_card mt-0">
        <div id="Expertise" class="collapse show" aria-labelledby="headingOne" data-parent="#astrofilter">
          <div class="card-body  astro-list">

            <ul class="p-0  p-3 pb-0">

              <li class="list-group-item mb-3  border-bottom">
              <a class="fs-6 text text-dark" href="{{route('astrologer.dashboard')}}"><i class="bi bi-grid"></i> My Dashboard</a>
              </li>
              <li class="list-group-item mb-3  border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.profile')}}">
                  <i class="bi bi-person"></i> My profile
                </a>
              </li>
              {{--<li class="list-group-item mb-3 border-bottom pb-2">
              <a class="fs-6 text text-dark" href="{{route('astrologer.status')}}">
                <i class="bi bi-person-check"></i></i> Available Status
               </a>
             </li>--}}
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.consultations')}}"><i class="bi bi-chat-left-text"></i> Consultations</a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.earnings')}}"><i class="bi bi-credit-card"></i>
                  Earnings</a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.puja')}}"><i class="bi bi-calendar-heart"></i> Assigned Puja</a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.booked.puja')}}"><i class="bi bi-calendar-check"></i> Booked Puja</a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a class="fs-6 text text-dark" href="{{route('astrologer.puja.earnings')}}"><i class="bi bi-cash-coin"></i> Puja Earnings</a>
              </li>
              @if($astrologer->can_go_live && (request()->has("_token") || true))
                <li class="list-group-item mb-3 border-bottom pb-2">
                  <a class="fs-6 text text-dark" href="{{route('astrologer.live.index')}}"><i class="bi bi-chat-left-text"></i> Live</a>
                </li>
              @endif
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.notification')}}" class="fs-6 text text-dark">
                <i class="bi bi-bell"></i> Notification
                </a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.newsletter')}}" class="fs-6 text text-dark">
                <i class="bi bi-newspaper"></i> Newsletter
                </a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.support')}}" class="fs-6 text text-dark">
                <i class="bi bi-headset"></i>  Support
                </a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.referral.listing')}}" class="fs-6 text text-dark">
                <i class="bi bi-people"></i>  Referral code
                </a>
              </li>
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.withdrawal')}}" class="fs-6 text text-dark">
                <i class="bi bi-cash-stack"></i> Withdrawal
                </a>
              </li>
              @if(!auth()->guard('astrologer')->user()->is_delete_requested)
              <li class="list-group-item mb-3 border-bottom pb-2">
                <a href="{{route('astrologer.deletion')}}" class="fs-6 text text-dark">
                <i class="bi bi-trash"></i> Delete My Account
                </a>
              </li>
              @endif
              <li class="list-group-item mb-3">
              <a class="fs-6 text text-dark" href="{{route('astrologer.logout')}}"><i class="bi bi-gear"></i> Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- sidebar-start -->
  <div class="contaier d-block d-lg-none">
    {{--<a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
        <div class="d-lg-none d-block v-btn-box">
            <button type="button" name="button text-white" class="arrow-b btn verticalButton hide-in-webview">Menu</button>
        </div>
      </a>--}}
            <div class="offcanvas offcanvas-start p-canvas ipad-m2" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
            <div class="offcanvas-body off-body-side">
              <div class="p-2">
                <button type="button" class="btn-close text-reset ast_dash_cls" data-bs-dismiss="offcanvas" aria-label="Close" style="top:10px;"></button>
              </div>
              <div class="card border-0 user-card d-n-card py-2">
              <div class="row align-items-center g-0">
             <div class="col-lg-3 col-3">
                 <div class="profile-img-box">
                 <img src="{{auth()->guard('astrologer')->user() && auth()->guard('astrologer')->user()->image ? auth()->guard('astrologer')->user()->image->image(100,100):prfileImg()}}" class="img-fluid rounded-circle user-img" alt="{{auth()->guard('astrologer')->user()->uuid}}">
                 </div>
               </div>
                 <div class="col-lg-9 col-12 pt-3 text-center">
                   <div class="card-body">
                     <h6 class="fw-bold text-black">{{auth()->guard('astrologer')->user()->firstname?:'User'}}</h6>
                     <span class="text last-log text-black">Your last login was</span>
                     <p class="text last-log text-purple">{{auth()->guard('astrologer')->user()?date('d M Y h:i A', strtotime(auth()->guard('astrologer')->user()->last_logined)):''}}</p>
                   </div>
                 </div>
             </div>
              <div class="row">
                <div class="col-md-12 k_redem">
                <a href="{{route('astrologer.earnings')}}" class="text-center">
                  <button class="btn btn-secondary add-money">Redeem</button>
                </a>
                </div>
              </div>
            </div>

            <div class="card mt-2">
            <div class="">
            <div class="card-header border-dark float-shadow-2 py-3" id="headingOne">
                      <h3 class="fs-6 text-black mb-0 fw-bold">My Account</h3>
                      </div>
                      <div class="card-body dash-sildbar side-scrollbar">

                        <ul class="p-0 pb-0">

                          <li class="list-group-item mb-3  border-bottom">
                          <a class="fs-6 text text-dark" href="{{route('astrologer.dashboard')}}"><i class="bi bi-grid"></i> My Dashboard</a>
                          </li>
                          <li class="list-group-item mb-3  border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.profile')}}"><i class="bi bi-person"></i> My
                              profile</a>
                          </li>
                          {{--<li class="list-group-item mb-3 border-bottom pb-2">
                          <a class="fs-6 text text-dark" href="{{route('astrologer.status')}}"><i class="bi bi-person-check"></i></i>
                             Available Status</a>
                          </li>--}}
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.earnings')}}"><i class="bi bi-credit-card"></i>
                              Earnings</a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.puja')}}"><i class="bi bi-calendar-heart"></i> Assigned Puja</a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.booked.puja')}}"><i class="bi bi-calendar-check"></i> Booked Puja</a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.puja.earnings')}}"><i class="bi bi-cash-coin"></i> Puja Earnings</a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a class="fs-6 text text-dark" href="{{route('astrologer.consultations')}}"><i class="bi bi-chat-left-text"></i> Consultations</a>
                          </li>
                          @if($astrologer->can_go_live && request()->has("_token"))
                            <li class="list-group-item mb-3 border-bottom pb-2">
                              <a class="fs-6 text text-dark" href="{{route('astrologer.live.index')}}"><i class="bi bi-chat-left-text"></i> Live</a>
                            </li>
                          @endif
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a href="{{route('astrologer.notification')}}" class="fs-6 text text-dark">
                            <i class="bi bi-bell"></i> Notification
                            </a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a href="{{route('astrologer.newsletter')}}" class="fs-6 text text-dark">
                            <i class="bi bi-newspaper"></i> Newsletter
                            </a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a href="{{route('astrologer.support')}}" class="fs-6 text text-dark">
                            <i class="bi bi-headset"></i>  Support
                            </a>
                          </li>
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a href="{{route('astrologer.referral.listing')}}" class="fs-6 text text-dark">
                            <i class="bi bi-people"></i> Register user and astrologer using referral code
                            </a>
                          </li>
                          @if(!auth()->guard('astrologer')->user()->is_delete_requested)
                          <li class="list-group-item mb-3 border-bottom pb-2">
                            <a href="{{route('astrologer.deletion')}}" class="fs-6 text text-dark">
                            <i class="bi bi-trash"></i> Delete My Account
                            </a>
                          </li>
                          @endif
                          <li class="list-group-item mb-3">
                          <a class="fs-6 text text-dark" href="{{route('astrologer.logout')}}"><i class="bi bi-gear"></i> Logout</a>
                          </li>
                        </ul>
                  </div>
                </div>
                </div>
                </div>
              </div>
                    </div>
  <!-- sidebar-end -->
