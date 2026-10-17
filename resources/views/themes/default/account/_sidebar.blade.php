<div class="col-lg-3 col-12 ps-md-0 mt-0 d-lg-block d-none sticky_top sticky-sidebar1">
  <div class="card border-0 user-card card-wallet">
  <div class="row align-items-center g-0">
 <div class="col-lg-3 col-3">
   <div class="profile-img-box">
     <img src="{{auth()->guard('web')->user() && auth()->guard('web')->user()->image ? auth()->guard('web')->user()->image->image(100,100):prfileImg()}}" class="img-fluid rounded-circle user-img" alt="{{auth()->guard('web')->user()->uuid}}">
   </div>
     </div>
     <div class="col-lg-12 col-9">
     <div class="user_profile">
       <h5>{{auth()->guard('web')->user()->name?:'User'}}</h5>
       <p>UUID: <strong>{{auth()->guard('web')->user()->uuid}} </strong></p>
       <span class="text last-log fs-7">Your last login was</span>
       <p class="last-log fs-7">{{auth()->guard('web')->user()?date('d M Y H:i A', strtotime(auth()->guard('web')->user()->last_logined)):''}}</p>

     </div>
     </div>
 </div>
</div>

<div class="dashbord_card mt-3">
<div class="sticky_top sticky-sidebar1">

          <div class="">


         <ul class="p-0  p-3 pb-0 sidebar_account">
            <li class="list-group-item mb-3 fs-6 border-bottom">
             <a href="{{route('user.dashboard')}}" class="text text-black">
            <i class="bi bi-grid"></i> Dashboard
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.profile')}}" class="text text-black">
        <i class="bi bi-person"></i> My Profile
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.addresses')}}" class="text text-black">
        <i class="bi bi-journal-bookmark"></i> Addresses
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.purchase.item')}}" class="text text-black">
        <i class="bi bi-cart-check"></i> Orders
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.wishlist')}}" class="text text-black">
        <i class="bi bi-heart"></i> Wishlist
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.recharge')}}" class="text text-black">
        <i class="bi bi-credit-card"></i> Wallet
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.wallethistory')}}" class="text text-black">
        <i class="bi bi-hourglass"></i> Wallet History
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.recharge-history')}}" class="text text-black">
        <i class="bi bi-receipt"></i> Recharge History
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.consultations')}}" class="text text-black">
        <i class="bi bi-people"></i> Consultations
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.aiconsultations')}}" class="text text-black">
        <i class="bi bi-people"></i> Ai Consultations
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.suggested-remedies')}}" class="text text-black">
        <i class="bi bi-heart-pulse"></i> Suggested Remedies
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.booked-puja')}}" class="text text-black">
        <i class="bi bi-calendar-check"></i> Booked Puja
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a  href="{{ route('user.notification') }}" class="text text-black">
        <i class="bi bi-bell"></i> Notification
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.newsletter')}}" class="text text-black">
        <i class="bi bi-newspaper"></i> Newsletter
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{ route('user.support') }}" class="text text-black">
        <i class="bi bi-headset"></i> Support
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('user.refer.earn')}}" class="text text-black">
        <i class="bi bi-gift"></i> Refer & Earn
        </a>
      </li>
      <li class="list-group-item mb-3 fs-6 border-bottom">
        <a href="{{route('logout')}}" class="text text-black">
        <i class="bi bi-gear-fill"></i> Logout
        </a>
      </li>
    </ul>
      </div>
    </div>
    </div>
  </div>

<div class="modal fade modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{route('user.avatar')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
          <div class="file-field input-field col-md-10 m10">
            <div class="">
              <!-- <span>File</span> -->
              <input type="file" class="w-100" name="file" accept=".png,.jpg,.jpeg,.webp" required>
              <input name="type" type="hidden"  value="user" >
            </div>
              <div class="file-path-wrapper">
                  <!-- <input class="file-path validate" type="text"> -->
                  <!-- <span class="helper-text" data-error="Invalid Fill" data-success="">Only png/jpg allowed</span> -->
              </div>
            </div>
            <div class="file-field input-field col-md-2 m2">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!-- sidebar-start -->
<div class="contaier d-block d-lg-none">
  {{--<a class="" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <div class="d-block  v-btn-box">
          <button type="button" name="button text-white" class="arrow-b btn verticalButton" >Menu</button>
      </div>
    </a>--}}
          <div class="offcanvas offcanvas-start p-canvas mt-pad" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog" style="background: #eeeeee;">
          <div class="offcanvas-body pt-5 off-body-m">
            <div class="p-2">
              <button type="button" class="btn-close text-reset ast_dash_cls" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="card border-0 user-card card-walle">
            <div class="row align-items-center g-0">
           <div class="col-lg-3 col-2">
             <div class="profile-img-box">
               <img src="{{auth()->guard('web')->user() && auth()->guard('web')->user()->image ? auth()->guard('web')->user()->image->image(100,100):prfileImg()}}" class="img-fluid rounded-circle user-img" alt="{{auth()->guard('web')->user()->uuid}}">

             </div>
               </div>
               <div class="col-lg-12 col-12">
               <div class="user_profile k_user_text">
                 <h6 class="">{{auth()->guard('web')->user()->name?:'User'}}</h6>
                 <span class="text last-log">Your last login was</span>
                 <p class="last-log">{{auth()->guard('web')->user()?date('d M Y H:i A', strtotime(auth()->guard('web')->user()->last_logined)):''}}</p>

               </div>
               </div>
           </div>
            <div class="row">
              <div class="col-lg-12 mt-3 d-md-block d-none text-center">
                <a href="{{route('user.recharge')}}"><button class="main_button">Add money</button></a>
              </div>
            </div>
          </div>

          <div class="card mt-2">
          <div class="">
            <div class="card-header  border-dark float-shadow-2 py-3" id="headingOne">
                    <h3 class="text mb-0 fw-bold text-black">My Account</h3>
                    </div>
                    <div class="card-body dash-sildbar side-scrollbar">


                   <ul class="p-0  p-md-3 pb-0">
                      <li class="list-group-item mb-3 border-bottom">
                       <a href="{{route('user.dashboard')}}" class="text text-dark">
                      <i class="bi bi-grid"></i><span class="ms-2">Dashboard</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.profile')}}" class="text text-dark">
                  <i class="bi bi-person"></i><span class="ms-2">My Profile</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.addresses')}}" class="text text-dark">
                  <i class="bi bi-journal-bookmark"></i><span class="ms-2">Addresses</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.purchase.item')}}" class="text text-dark">
                  <i class="bi bi-cart-check"></i><span class="ms-2">Orders<span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.wishlist')}}" class="text text-dark">
                  <i class="bi bi-heart"></i><span class="ms-2">Wishlist</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.recharge-history')}}" class="text text-dark">
                  <i class="bi bi-wallet"></i><span class="ms-2">Wallet<span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.wallethistory')}}" class="text text-dark">
                  <i class="bi bi-hourglass"></i><span class="ms-2">Wallet History</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.recharge')}}" class="text text-dark">
                  <i class="bi bi-hourglass"></i><span class="ms-2">Recharge History</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.consultations')}}" class="text text-dark">

                        <i class="bi bi-people"></i><span class="ms-2">Consultations</span>

                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.aiconsultations')}}" class="text text-dark">

                        <i class="bi bi-people"></i><span class="ms-2">Ai Consultations</span>

                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.booked-puja')}}" class="text text-dark">
                        <i class="bi bi-calendar-check"></i><span class="ms-2">Booked Puja</span>
                  </a>
                </li>
                {{--<li class="list-group-item mb-3 ">
                  <a href="{{ route('user.purchasecourse') }}" class="text text-dark">
                  <i class="bi bi-book"></i>  <span class="ms-2">Courses</span>
                  </a>
                </li>--}}
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.newsletter')}}" class="text text-dark">
                  <i class="bi bi-newspaper"></i><span class="ms-2">Newsletter</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a  href="{{ route('user.notification') }}" class="text text-dark">
                  <i class="bi bi-bell"></i><span class="ms-2">Notification</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{ route('user.support') }}" class="text text-dark">
                  <i class="bi bi-headset"></i><span class="ms-2">Support</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('user.refer.earn')}}" class="text text-dark">
                  <i class="bi bi-gift"></i><span class="ms-2">Refer & Earn</span>
                  </a>
                </li>
                <li class="list-group-item mb-3 border-bottom">
                  <a href="{{route('logout')}}" class="text text-dark">
                  <i class="bi bi-gear-fill"></i><span class="ms-2">Logout</span>
                  </a>
                </li>
              </ul>
                </div>
              </div>
              </div>
              </div>
            </div>
                  </div>
<!-- sidebar-end -->
