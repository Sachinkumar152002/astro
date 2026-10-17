<div id="sidebar">
    <div class="social facebook">
        <a href="https://www.facebook.com/smartskilltech" target="_blank">
            <p>Like on Facebook <i class="fa fa-facebook ">&nbsp;</i></p>
        </a>
    </div>
    <div class="social twitter">
        <a href="https://twitter.com/SmartSkillTech" target="_blank">
            <p>Follow on Twitter<i class="fa fa-twitter">&nbsp;</i></p>
        </a>
    </div>
    <div class="social linkedin">
        <a href="https://www.linkedin.com/company/smart-skill-tech-pvt.-ltd." target="_blank">
            <p>Connect on LinkedIn<i class="fa fa-linkedin">&nbsp;</i></p>
        </a>
    </div>
    <div class="social facebook">
        <a href="javascript:void(Tawk_API.toggle())">
            <p>Connect on Chat<i class="fa fa-comments">&nbsp;</i></p>
        </a>
    </div>
</div>
{{--<div id="headre-top">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                Last day for courses from <i class="fa fa-inr">&nbsp;</i> 999-| Start a learning adventure this
                February!
            </div>
        </div>
    </div>
    <div class="hide-head"><img src="{{asset('rewamp/images/Cross.png')}}"/></div>
</div>--}}

<header id="bottom-in">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo-m" href="{{route('index')}}"><img width="190" src="{{asset('rewamp/images/logo.jpg')}}"
                                                                          alt="logo"
                                                                          title=""/></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">&nbsp;</span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle all-courses" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Courses
                    </button>
                    <div class="dropdown-menu drop-bottom drop-header" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/course/software-developer-program">Software Developer
                            Program</a>
                        <a class="dropdown-item" href="/course/data-entry-operator-program">Data Entry Operator Program</a>
                        <a class="dropdown-item" href="/course/japanese-work-culture-program">Japanese Work Culture Program</a>
                        <a class="dropdown-item" href="/course/process-coordinator-program">Process Coordinator
                            Program</a>
                        <a class="dropdown-item" href="/course/smart-employment-enhancement-program">Smart Employment
                            Enhancement Program</a>
                        <a class="dropdown-item" href="javascript:void(0)">Video Resume
                            Training Program</a>
                    </div>
                </div>
                <ul class="navbar-nav right">
                    <li class="nav-item active">
                        <a class="nav-link {{isset($nav) && $nav=='home'?'active':''}}" href="{{route('index')}}">Home
                            <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='about'?'active':''}}">
                        <a class="nav-link" href="{{route('about')}}">About Us
                        </a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='courses'?'active':''}}">
                        <a class="nav-link" href="{{route('courses')}}">Courses</a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='gallery'?'active':''}}">
                        <a class="nav-link" href="{{route('gallery')}}">Gallery</a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='partners'?'active':''}}">
                        <a class="nav-link" href="{{route('partners')}}">Partners</a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='blog'?'active':''}}">
                        <a class="nav-link" href="{{route('blogs')}}">Blog</a>
                    </li>
                    <li class="nav-item {{isset($nav) && $nav=='contact'?'active':''}}">
                        <a class="nav-link" href="{{route('contact')}}">Contact Us</a>
                    </li>

                </ul>
                <div class="">
                    <ul class="btn-head">
                        @if(Auth::guard('web')->check())
                            <li class="">
                                <a style="color: #000000 !important;border: none;box-shadow: none;padding: 0px !important;" href="{{route('cart.view')}}" class="btn btn-custom"><img
                                            src="{{asset('rewamp/images/cart.png')}}">
                                    <span class="badge badge1">{{count(session('sst_cart_items', []))}}</span></a>
                            </li>

                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle all-courses" data-toggle="dropdown"
                                   href="{{route('account.dashboard')}}" aria-haspopup="true" aria-expanded="false"><i
                                            class="fa fa-user-circle">&nbsp;</i><span> {{Auth::user()->name}}</span> <b
                                            class="caret">&nbsp;</b></a>
                                <div class="dropdown-menu drop-bottom drop-header" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{route('account.dashboard')}}"><i
                                                class="fa fa-dashboard">&nbsp;</i>
                                        Dashboard</a>
                                    <a class="dropdown-item" href="{{route('account.profile')}}"><i class="fa fa-user">&nbsp;</i>
                                        My
                                        Profile</a>
                                    <a class="dropdown-item" href="{{route('account.courses')}}"><i class="fa fa-cubes">&nbsp;</i>
                                        My
                                        Courses</a>
                                    <a class="dropdown-item" href="{{route('account.wishlist')}}"><i class="fa fa-heart">&nbsp;</i>
                                        My
                                        Wishlists</a>
                                    <a class="dropdown-item" href="{{route('account.scorecard')}}"><i
                                                class="fa fa-file">&nbsp;</i> My Scorecard</a>

                                    <a class="dropdown-item" href="{{route('account.notifications')}}"><i
                                                class="fa fa-bell">&nbsp;</i>
                                        Notifications</a>

                                    <a class="dropdown-item" href="{{route('account.certificate')}}"><i
                                                class="fa fa-certificate">&nbsp;</i>
                                        Certificate</a>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="$('#logout-form').submit()"
                                       title="Logout"><i
                                                class="fa fa-power-off">&nbsp;</i> Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display:none;">{{ csrf_field() }}</form>

                                </div>
                            </div>
                        @else
                            <li class="">
                                <a style="color: #000000 !important;border: none;box-shadow: none;padding: 0px !important;" href="{{route('cart.view')}}">
                                    <img alt="Cart" src="{{asset('rewamp/images/cart.png')}}">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class=" sign" href="javascript:void(0)" onclick="openModal('login')"
                                   style="color:#222  !important;">Sign in</a>
                            </li>
                            <li class="">
                                <a class="all-courses" onclick="openModal('registration')"
                                   href="javascript:void(0)">Register</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>


{{--<header id="header">
    <!-- header top bar -->
    <div class="row">
        <div class="">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 pull-right text-right bgcolor1 borderbottom">
                <ul class="list-inline contact">
                    <li><a href="{{config('sst.contact.tel')}}"><i class="fa fa-phone"></i>
                            <span>{{config('sst.contact.phone')}}</span></a></li>
                    <li><a href="{{config('sst.email.mailto')}}"><i
                                    class="fa fa-envelope"></i><span> {{config('sst.email.info')}}</span></a></li>
                    @if(Auth::guard('web')->check())
                        <li class="list-style"><a href="#" id="cart1" onclick="$('.shopping-cart1').toggle();"><i
                                        style="font-size:18px; position:relative; top:3px;"
                                        class="fa fa-bell fa-bell1"></i>
                                @if(count(Auth::guard('web')->user()->recent_notification)>0)<span
                                        class="badge badge1">{{count(Auth::guard('web')->user()->recent_notification)}}</span>@endif
                            </a>
                        </li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"
                                                href="{{route('account.dashboard')}}"><i
                                        class="fa fa-user-circle"></i><span> {{Auth::user()->name}}</span> <b
                                        class="caret"></b></a>
                            <ul class="dropdown-menu dropdown-menu-header">
                                <li><a href="{{route('account.dashboard')}}"><i class="fa fa-dashboard"></i>
                                        Dashboard</a></li>
                                <li><a href="{{route('account.profile')}}"><i class="fa fa-user"></i> My Profile</a>
                                </li>
                                <li><a href="{{route('account.job.profile')}}"><i class="fa fa-graduation-cap"></i> My
                                        Job Profile</a></li>
                                <li><a href="{{route('account.courses')}}"><i class="fa fa-cubes"></i> My Courses</a>
                                </li>
                                --}}{{--
                                --}}{{--<li><a href="{{route('account.scorecard')}}"><i class="fa fa-file"></i> My Scorecard</a></li>--}}{{----}}{{--

                                                                <li><a href="{{route('account.orders')}}"><i class="fa fa-list"></i> My Orders</a></li>
                                                                <li><a href="{{route('account.notifications')}}"><i class="fa fa-bell"></i>
                                                                        Notifications</a></li>
                                                                --}}{{--
                                --}}{{--<li><a href="{{route('account.certificate')}}"><i class="fa fa-certificate"></i> Certificate</a></li>--}}{{----}}{{--

                                                                --}}{{--
                                <li><a href="{{route('account.jobs')}}"><i class="fa fa-suitcase"></i> My Jobs</a></li>

                                <li><a href="{{route('account.changepassword')}}"><i class="fa fa-lock"></i>Change
                                        Password</a></li>
                                <li><a href="javascript:void(0)" onclick="$('#logout-form').submit()" title="Logout"><i
                                                class="fa fa-power-off"></i> Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display:none;">{{ csrf_field() }}</form>
                                </li>
                            </ul>
                        </li>
                    @elseif(Auth::guard('employer')->check())
                        <li><i class="fa fa-bell"></i></li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"
                                                href="{{route('account.dashboard')}}">
                                <i class="fa fa-user-circle"></i>
                                <span>{{Auth::guard('employer')->user()->name}} </span><b class="caret"></b></a>
                            <ul class="dropdown-menu dropdown-menu-header">
                                <li><a href="{{route('employer.account.dashboard')}}"><i class="fa fa-dashboard"></i>
                                        Dashboard</a></li>
                                <li><a href="{{route('employer.account.profile')}}"><i class="fa fa-user"></i> My
                                        Profile</a></li>
                                <li><a href="{{route('employer.account.companies')}}"><i class="fa fa-list"></i>Companies</a>
                                </li>
                                <li><a href="{{route('employer.post.job')}}"><i class="fa fa-list"></i> Post Job</a>
                                </li>
                                <li {{isset($empnav) && $empnav=='joblist' ? 'class=active' : ''}}><a
                                            href="{{route('employer.job.list')}}"><i class="fa fa-list"></i>Job List</a>
                                </li>
                                <li><a href="{{route('employer.account.changepassword')}}"><i class="fa fa-lock"></i>Change
                                        Password</a></li>
                                <li><a href="javascript:void(0)" onclick="$('#logout-form').submit()" title="Logout"><i
                                                class="fa fa-power-off"></i> Logout</a>
                                    <form id="logout-form"
                                          action="{{ route(Auth::guard('employer')->check() ? 'employer.logout' : 'logout') }}"
                                          method="POST" style="display:none;">{{ csrf_field() }}</form>
                                </li>
                            </ul>
                        </li>
                    @else

                        <li><a href="javascript:void(0)" onclick="openModal('login')"><i
                                        class="fa fa-user-circle"></i><span> Login/Sign Up</span></a></li>
                    @endif
                </ul>

                @include('main.partials.notifications')

            </div>
        </div>
    </div>
    <!-- Logo & cart button -->
    <div class="clearfix"></div>
    <div class="container">
        <div class="row">
            <!-- Logo -->
            <div class="col-lg-6 col-md-6 logo logo-text">
                <a href="{{route('index')}}" title="{{config('sst.site_name')}}">
                    <img alt="Smart-Skill-Development-Programs" src="{{asset(config('sst.logo'))}}"
                         class="img-responsive pull-left">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 pull-right animated wow slideInRight cart-btn-header">
                <!-- Cart Button -->
                <div class="input-group cart-btn-home">
                    <a href="{{route('cart.view')}}" class="btn btn-custom"><i class="fa fa-shopping-cart"></i> Cart
                        <span class="badge badge1">@if(Auth::guard('employer')->check() ) {{session('sst_cart_plan', '')?1:0}} @else {{count(session('sst_cart_items', []))}} @endif</span></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="slide-collapse"
                    data-target="#slide-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:void(0)"></a>
        </div>
        <div class="container">
            <div class="collapse navbar-collapse js-navbar-collapse" id="slide-navbar-collapse">
                <ul class="nav navbar-nav menu">
                    <li class="{{isset($nav) && $nav=='home'?'active':''}}"><a href="{{route('index')}}"><i
                                    class="fa fa-home"></i> <span>Home</span></a></li>
                    <li class="{{isset($nav) && $nav=='about'?'active':''}}"><a href="{{route('about')}}"><i
                                    class="fa fa-info-circle"></i> <span>About us</span></a></li>
                    <li class="{{isset($nav) && $nav=='courses'?'active':''}}"><a href="{{route('courses')}}"><i
                                    class="fa fa-graduation-cap"> </i> <span>Courses</span></a>
                    </li>
                    <li class="{{isset($nav) && $nav=='gallery'?'active':''}}"><a href="{{route('gallery')}}"><i
                                    class="fa fa-image"></i> <span>Gallery</span> </a></li>
                    <li class="{{isset($nav) && $nav=='partners'?'active':''}}"><a href="{{route('partners')}}"><i
                                    class="fa fa-handshake-o"></i> <span>Partners </span></a></li>
                    <li class="{{isset($nav) && $nav=='blog'?'active':''}}"><a href="{{route('blog')}}" target="_blank"><i
                                    class="fa fa-newspaper-o"></i> <span>Blog</span> </a></li>
                    <li class="{{isset($nav) && $nav=='contact'?'active':''}}"><a href="{{route('contact')}}"><i
                                    class="fa fa-envelope"></i> <span>Contact us</span> </a></li>
                    <li class="{{isset($nav) && $nav=='emp'?'active':''}}"><a class="dropdown-toggle"
                                                                              data-toggle="dropdown" href="#"><i
                                    class="fa fa-users"></i> <span>Employer Zone <span class="caret"></span></span> </a>
                        <ul class="dropdown-menu submenu" role="menu">
                            <li><a href="{{route('emp')}}"><i class="fa fa-home"></i> Home</a></li>
                            <li><a href="{{route('employer.search')}}"><i class="fa fa-search"></i> Search Candidate</a>
                            </li>
                            <li><a href="{{route('employer.post.job')}}"><i class="fa fa-clipboard"></i> Post Job</a>
                            </li>
                            <li><a href="{{route('employer.plan')}}"><i class="fa fa-check"></i> Subscribe</a></li>
                            <li><a href="{{route('employer.events')}}"><i class="fa fa-calendar"></i> Events</a></li>
                            <li><a href="{{route('employer.faqs')}}"><i class="fa fa-question"></i> FAQ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="menu-overlay"></div>
</header>--}}
