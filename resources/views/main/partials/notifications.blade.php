 @if(Auth::guard('web')->check() || Auth::guard('employer')->check())
<div class="shopping-cart1" style="display: none;">
 @if(Auth::guard('web')->check() && Auth::guard('web')->user()->recent_notification)
 <h4> Notifications <a href="{{route('account.notifications')}}"><span>View All</span></a> </h4>
 @foreach(Auth::guard('web')->user()->recent_notification as $notifiation)
  <div class="notification-section  ">
    <div class="Content-section-section">
       {{$notifiation->message}}
    </div>
  </div>
  @endforeach
 @elseif(Auth::guard('employer')->check() && Auth::guard('employer')->user()->recent_notification)
  @foreach(Auth::guard('employer')->user()->recent_notification as $notifiation)
 <h4> Notifications <a href="{{route('account.notifications')}}"><span>View All</span></a> </h4>
  <div class="notification-section  ">
    <div class="Content-section-section">
       {{$notifiation->message}}
    </div>
  </div>
  @endforeach
 @endif
</div>
@endif