
@extends(themeView().'layout')


@section('content')
<div class="container py-5">
  <div class="main-heading">
    <h2>Notifications</h2>
  </div>
  <div class="row mt-4">
    <div class="col-md-12 m-auto px-3 py-4 radius" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
      @forelse($notification as $key => $value)
        @if($value->link)
          <a href="{{$value->link}}" class="d-flex justify-content-between border-bottom py-2">
            <div class="d-flex align-items-center">
               <i class="bi bi-bell me-2 mt-1 no-icon text-orange"></i>
               <div>
                <h6 class="mb-0">{{$value->message}}</h6>
              </div>
            </div>
             <div class="text-end">
              <p class="mb-0">{{date('d F Y h:i A', strtotime($value->created_at))}}</p>
            </div>
          </a>
        @else
          <div class="d-flex justify-content-between border-bottom py-2 pointer">
            <div class="d-flex align-items-center">
               <i class="bi bi-bell me-2 mt-1 no-icon text-orange"></i>
               <div>
                <h6 class="mb-0">{{$value->message}}</h6>
              </div>
            </div>
             <div class="text-end">
              <p class="mb-0">{{date('d F Y h:i A', strtotime($value->created_at))}}</p>
            </div>
          </div>
        @endif
        @empty
        <div class="text-center">
          <button class="view_all px-2 fs-14">Hooray! My notification box is sparkling clean</button>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
