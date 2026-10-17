<style media="screen">
.notif-heading {
display: flex;
align-items: center;
gap: 8px;
}

.fs-16 {
font-size: 16px;
font-weight: 600;
color: #333;
margin: 0;
}
.bor-bottom{
  border-bottom: 1px solid #ddd;
}
</style>
@extends(themeView().'layout')

@section('title', 'Notifications | '.config("app.name"))

@section('content')
<section class="dashboad_section">
<div class="container">
<div class="row justify-content-between">
     @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12 mt-5 mt-md-0">


        <div class="order-box my-4 mt-md-0 border-n">
          <div class="px-4 py-3 text-center text-md-start">
            <div class="row pb-md-2 pb-4">
              <div class="notif-heading d-flex align-items-center gap-2">
                <!-- Bootstrap Bell Icon -->
                <i class="bi bi-bell-fill  fs-5" style="color:#0d6efd;"></i>
                <!-- Heading -->
                <h2 class="fs-16 mb-0">Notifications</h2>
              </div>
                  @foreach($notifications as $key => $value)
                    @php
                      $imageUrl = $value->image ? $value->image->image(125, 125) : asset('assets/img/logo.png');
                      $date = date('d F Y | h:i A', strtotime($value->created_at));
                      $message = $value->message;
                      $link = $value->link;
                    @endphp
                  <div class="bor-bottom">
                  <div class="mt-2 ">
                    <p class="testimonials-p noti_p pb-1 mb-2 dash_noti">{{$message}}</p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="mb-0 mt-1 mt-md-0">{{$date}}</p>
                    @if($link)
                      <a href="{{$link}}" class="main_button mt-2 mt-md-0">View Detail</a>
                    @endif
                  </div>
                  </div>
              @endforeach
            </div>
          </div>
        </div>
      <div class="text-center">
        {!! $notifications->links('vendor.pagination.bootstrap-5') !!}
      </div>
      @if($notifications->count() == 0)
        <div class="col-12 text-center">
          <img src="/assets/images/wish.webp" alt="No Item" class="nodata">
          <h5 class="mt-3 text-dark"> No Notifications Found!</h5>
        </div>
      @endif
    </div>
  </section>
@endsection

@push('scripts')
<script type="text/javascript">
  function handleViewDetail(imageUrl, date, message, link){
    if(link){
      document.getElementById('link').href = link;
    }else{
      document.getElementById('link').removeAttribute('href')
    }
    document.getElementById('imageUrl').src = imageUrl;
    document.getElementById('date').innerText = date;
    document.getElementById('message').innerText = message;
    $('#notification').modal('show');
  }
</script>
@endpush
