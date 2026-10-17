<div class="col-sm-3">
    <div class="accordion astro-filter" id="astrofilter">
      <div class="card mb-3">
        <div class="card-header bg-light border-bottom border-dark" id="headingOne">
          <p class="mb-0" data-toggle="collapse" data-target="#Expertise" aria-expanded="true"
            aria-controls="Expertise"><strong>My Account</strong>
          </p>
        </div>
        <div id="Expertise" class="collapse show" aria-labelledby="headingOne" data-parent="#astrofilter">
          <div class="card-body p-0">
            <ul class="nav flex-column list-group list-group-flush">

              <li class="nav-item list-group-item pl-0">
              <a class="nav-link text-dark" href="{{route('astrologer.dashboard')}}"><i class="bi bi-grid-fill"></i> My Dashboard</a>
              </li>
              <li class="nav-item list-group-item pl-0">
              <a class="nav-link text-dark" href="{{route('astrologer.profile')}}"><i class="bi bi-person-fill"></i> My
                  profile</a>
              </li>
              <li class="nav-item list-group-item pl-0">
              <a class="nav-link text-dark" href="{{route('astrologer.calls')}}"><i class="bi bi-telephone-fill"></i></i>
                  Calls</a>
              </li>
              <li class="nav-item list-group-item pl-0">
              <a class="nav-link text-dark" href="{{route('astrologer.consultation')}}"><i class="bi bi-boxes"></i>
                  Consultation</a>
              </li>
              {{-- <li class="nav-item list-group-item pl-0">
                <a class="nav-link text-dark" href="{{route('astrologer.chat')}}"><i class="fa fa-comment-dots"></i>
                  Chats</a>
              </li> --}}
              <li class="nav-item list-group-item pl-0">
                <a class="nav-link text-dark" href="{{route('astrologer.earnings')}}"><i class="bi bi-credit-card"></i>
                  Earnings</a>
              </li>
              <li class="nav-item list-group-item pl-0">
                <a class="nav-link text-dark" href="{{route('astrologer.puja')}}"><i class="bi bi-calendar-heart"></i> Puja</a>
              </li>
              <li class="nav-item list-group-item pl-0">
                <a class="nav-link text-dark" href="{{route('astrologer.booked.puja')}}"><i class="bi bi-calendar-check"></i> Booked Puja</a>
              </li>
              <li class="nav-item list-group-item pl-0">
              <a class="nav-link text-dark" href="{{route('astrologer.logout')}}"><i class="bi bi-door-closed-fill"></i> Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
