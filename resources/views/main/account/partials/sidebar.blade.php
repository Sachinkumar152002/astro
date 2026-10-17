 <div class="col-md-3">
    <div class="nav-side-menu">
        <div class="brand">{{auth()->user()->name}}</div>
        <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
        <div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <div class="profile-image">
                    <img src="{{auth()->user()->details ? (auth()->user()->details->avatar ? auth()->user()->details->avatar->image(200,200) : auth()->user()->no_image) : auth()->user()->no_image}}">
                </div>
                <li {{isset($nav) && $nav=='dashboard' ? 'class=active' : ''}}><a href="{{route('account.dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li {{isset($nav) && $nav=='profile' ? 'class=active' : ''}}><a href="{{route('account.profile')}}"><i class="fa fa-user"></i>My Profile</a></li>
                <li {{isset($nav) && $nav=='jobprofile' ? 'class=active' : ''}}><a href="{{route('account.job.profile')}}"><i class="fa fa-graduation-cap"></i>My Job Profile</a></li>
                <li {{isset($nav) && $nav=='courses' ? 'class=active' : ''}}><a href="{{route('account.courses')}}"><i class="fa fa-cubes"></i>My Courses</a></li>
                <li {{isset($nav) && $nav=='wishlist' ? 'class=active' : ''}}><a href="{{route('account.wishlist')}}"><i class="fa fa-cubes"></i>My Wishlists</a></li>
               {{-- <li {{isset($nav) && $nav=='scorecard' ? 'class=active' : ''}}><a href="{{route('account.scorecard')}}"><i class="fa fa-file"></i>My Scorecard</a></li> --}}
                <li {{isset($nav) && $nav=='orders' ? 'class=active' : ''}}><a href="{{route('account.orders')}}"><i class="fa fa-list"></i>My Orders</a></li>
                <li {{isset($nav) && $nav=='notifications' ? 'class=active' : ''}}><a href="{{route('account.notifications')}}"><i class="fa fa-bell"></i>Notifications</a></li>
                {{--<li {{isset($nav) && $nav=='certificate' ? 'class=active' : ''}}><a href="{{route('account.certificate')}}"><i class="fa fa-certificate"></i>Certificate</a></li>--}}
               {{-- <li {{isset($nav) && $nav=='jobs' ? 'class=active' : ''}}><a href="{{route('account.jobs')}}"><i class="fa fa-suitcase"></i>My Jobs</a></li>--}}
               <li {{isset($nav) && $nav=='changepassword' ? 'class=active' : ''}}><a href="{{route('account.changepassword')}}"><i class="fa fa-lock"></i>Change Password</a></li>
                <li ><a href="javascript:void(0)" onclick="$('#logout-form').submit()" title="Logout"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>
