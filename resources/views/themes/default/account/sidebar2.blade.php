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
                            <a class="nav-link text-dark" href="{{route('user.dashboard')}}"><i class="fa fa-dashboard"></i> My Dashboard</a>
                        </li>
                        <li class="nav-item list-group-item pl-0">
                            <a class="nav-link text-dark" href="{{route('user.profile')}}"><i class="fa fa-user"></i> My profile</a>
                        </li>
                        <li class="nav-item list-group-item pl-0">
                            <a class="nav-link text-dark" href="{{route('user.recharge-history')}}"><i class="fa fa-wallet"></i> Wallet</a></li>
                        <li class="nav-item list-group-item pl-0">
                                <a class="nav-link text-dark" href="{{route('user.newsletter')}}"><i class="fa fa-newspaper"></i> Newsletter</a></li>
                        <li class="nav-item list-group-item pl-0">
                            <a class="nav-link text-dark" href="{{route('user.calls-history')}}"><i class="fa fa-phone-volume"></i> Calls History</a>
                        </li>
                        {{-- <li class="nav-item list-group-item pl-0">
                            <a class="nav-link text-dark" href="chat.html"><i class="fa fa-comment-dots"></i> Chats</a>
                        </li> --}}
                        <li class="nav-item list-group-item pl-0">
                            <a class="nav-link text-dark" href="{{route('logout')}}"><i class="fa fa-close"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>