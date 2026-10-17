 <div class="col-md-3">
    <div class="nav-side-menu">
        <div class="brand">{{Auth::guard('employer')->user()->name}}</div>
        <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
        <div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <div class="profile-image">
                    <img src="{{Auth::guard('employer')->user()->image_id ? (Auth::guard('employer')->user()->avatar ? Auth::guard('employer')->user()->avatar->image(200,200) : Auth::guard('employer')->user()->no_image) : Auth::guard('employer')->user()->no_image}}">
                </div>
                <li {{isset($empnav) && $empnav=='dashboard' ? 'class=active' : ''}}><a href="{{route('employer.account.dashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li {{isset($empnav) && $empnav=='profile' ? 'class=active' : ''}}><a href="{{route('employer.account.profile')}}"><i class="fa fa-user"></i>My Profile</a></li>
                <li {{isset($empnav) && $empnav=='companies' ? 'class=active' : ''}}><a href="{{route('employer.account.companies')}}"><i class="fa fa-list"></i>Companies</a></li>
                <li {{isset($empnav) && $empnav=='postjob' ? 'class=active' : ''}}><a href="{{route('employer.post.job')}}"><i class="fa fa-list"></i>Post Job</a></li>
                <li {{isset($empnav) && $empnav=='joblist' ? 'class=active' : ''}}><a href="{{route('employer.job.list')}}"><i class="fa fa-list"></i>Job List</a></li>
                <li {{isset($empnav) && $empnav=='changepassword' ? 'class=active' : ''}}><a href="{{route('employer.account.changepassword')}}"><i class="fa fa-lock"></i>Change Password</a></li>
                <li ><a href="javascript:void(0)" onclick="$('#logout-form').submit()" title="Logout"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>