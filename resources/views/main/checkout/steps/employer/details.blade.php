<div class="course_summary_wrap" id="step-2-details" >
    <div class="summary_inner">
    <div class="summary_heading ">@if(Auth::guard('employer')->check()) Details @else Login/Signup @endif <a href="javascript:void(0)" class="pull-right" style="display: none;" onclick="moveToStep(2)"><i class="fa fa-edit pull-right"></i></a></div>
    </div>
    <div class="course-summary-section" style="display: none;">
    <div class="frame-section">
    <ul class="signuptabs ">
    @if(Auth::guard('employer')->check())
    <li class="active">
    <a href="#student-details" data-toggle="tab">
    My Details </a>
    </li>
    @else
    <li class="active">
    <a href="#sign-in" data-toggle="tab">
    Sign in  </a>
    </li>
    <li>
    <a href="#sign-up" data-toggle="tab">
    Sign up </a>
    </li>
    @endif
    </ul>
    <div class="tab-content">
    @if(Auth::guard('employer')->check())
    <div class="tab-pane active" id="student-details">
     <form class="form-signup" role="form" id="details-form" method="POST" action="{{ route('checkout.employer.details') }}" >
    {{ csrf_field() }}
    <div class="row">
    <div class="col-md-6"><input class="form-styling" type="text" name="name" value="{{auth::guard('employer')->user()->name}}" required  placeholder="Full name"/></div>
    <div class="col-md-6"><input class="form-styling" type="email" name="email" value="{{auth::guard('employer')->user()->email}}" disabled placeholder="Email"/></div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <input class="form-styling" type="text" name="phone" placeholder="Phone Number" required minlength="10" maxlength="10" value="{{auth::guard('employer')->user()->mobile}}" />
    </div>
    <div class="col-md-6">
    <input class="form-styling" type="text" name="address" placeholder="Address" required=""  value="{{auth::guard('employer')->user()->address}}" />
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="country" required="" id="bill_country" data-placeholder="Country" data-prev="{{auth::guard('employer')->user()->country}}">
    <option value="" disabled selected>Country</option>
    </select>
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="state" required="" id="bill_state" placeholder="select" data-placeholder="State" data-prev="{{auth::guard('employer')->user()->state}}">
    <option value="" disabled selected>State</option>

    </select>
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="city" id="bill_city"  placeholder="select" data-placeholder="City" data-prev="{{auth::guard('employer')->user()->city_id}}">
    <option value="" disabled selected>State</option>
    </select>
    </div>
    <div class="col-md-6">
    <input class="form-styling" type="text" name="pincode" placeholder="Pincode" required=""  value="{{auth::guard('employer')->user()->pincode}}" />
    </div>
    </div>
    <button class="btn-signin"  type="submit" id="details-submit">Submit</button>
    </form>
    </div>
    @else
    <div class="tab-pane active" id="sign-in">

    <form class="form-signin" role="form" id="login-form" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <input class="form-styling" type="email" name="email" placeholder="Email ID" value="{{ old('email') }}" required />
    <input class="form-styling" type="password" name="password" placeholder="Password" required/>
    <button class="btn-signin" id="login-submit" type="submit">Sign in</button>
    </form>
    </div>
    <div class="tab-pane" id="sign-up">

     <form class="form-signup" role="form" id="registration-form" method="POST" action="{{ route('register') }}" >
    {{ csrf_field() }}
    <div class="row">
    <div class="col-md-6"><input class="form-styling" type="text" name="name" value="{{ old('name') }}" required  placeholder="Full name"/></div>
    <div class="col-md-6"><input class="form-styling" type="email" name="email" value="{{ old('email') }}" required placeholder="Email"/></div>
    <div class="col-md-6"><input class="form-styling" type="password" name="password" required placeholder="Password" id="register_password" /></div>
    <div class="col-md-6"><input class="form-styling" type="password" name="password_confirmation" required placeholder="Confirm password"/></div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <input class="form-styling" type="text" name="phone" placeholder="Phone Number" required minlength="10" maxlength="10"/>
    </div>
    <div class="col-md-6">
    <input class="form-styling" type="text" name="address" placeholder="Address" required="" />
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="country" required="" id="bill_country" data-placeholder="Country">
    <option value="" disabled selected>Country</option>
    </select>
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="state" required="" id="bill_state" placeholder="select" data-placeholder="State">
    <option value="" disabled selected>State</option>

    </select>
    </div>
    <div class="col-md-6">
    <select class=" form-styling" data-show-subtext="true" data-live-search="true" name="city" id="bill_city"  placeholder="select" data-placeholder="City">
    <option value="" disabled selected>State</option>
    </select>
    </div>
    <div class="col-md-6">
    <input class="form-styling" type="text" name="pincode" placeholder="Pincode" required="" />
    </div>
    </div>
    <div class="form-group">
                            <input class="mb-10" name="tnc" value="Y" required="" type="checkbox">
                            <label>I accept all <a href="{{route('terms')}}" target="_blank"> terms &amp; conditions.</a> </label>
                             </div>
    <button class="btn-signin"  type="submit" id="register-submit">Register</button>
    </form>
    </div>
    @endif
    </div>
    </div>
    </div>
</div>