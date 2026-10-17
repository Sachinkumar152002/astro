@extends(themeView().'layout')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}

<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
        @if($astrologer->is_verified)
      <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
          <div class="tab-scroll-wrapper ps-2">
          <ul id="myTab" role="tablist" class="nav dashboard_tab nav-tabs dash-tab astro-account-tab">
        <li role="presentation" class="nav-item">
          <a id="phone-tab" data-bs-toggle="tab" href="#phone" role="tab" aria-controls="phone" aria-selected="true" class="nav-link active"> Phone Call</a>
        </li>
        <li role="presentation" class="nav-item">
          <a id="video-tab" data-bs-toggle="tab" href="#video" role="tab" aria-controls="video" aria-selected="false" class="nav-link" tabindex="-1"> Video Call</a>
        </li>
        <li role="presentation" class="nav-item">
          <a id="chat-tab" data-bs-toggle="tab" href="#chat" role="tab" aria-controls="chat" aria-selected="false" class="nav-link" tabindex="-1"> Chat</a>
        </li>

      </ul>
    </div>
      <div id="myTabContent" class="tab-content">

      <div id="phone" role="tabpanel" aria-labelledby="phone-tab" class="tab-pane mb-5 fade show active tab-text">
        <div class="card mb-3 mt-4 bg-light">
        <div class="card-body">
        <div class="mb-2">
            <h6 class="my-3"><i class="fa fa-phone-volume"></i> Phone Call Status</h6>
            <div class="row">
              <div class="col-md-2 col-3 p-1">
                <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'online'])}}')" href="javascript:void(0)">
                  <button class="badge p-3 px-2 px-md-4 ms-0 fs-7 ms-md-2 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->call==1?'bg-success text-white':'bg-lightt text-dark'}}">I'm Online</button>
                </a>

              </div>
              <div class="col-md-2 col-3 p-1">
                <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'offline'])}}')" href="javascript:void(0)">
                  <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->call==2?'bg-dark text-white':'bg-lightt text-dark'}}">I'm Offine</button>
                </a>

              </div>
              <div class="col-md-2 col-3 p-1">
                <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'busy'])}}')" href="javascript:void(0)">
                  <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100  status-btn-astro rounded-pill {{$astrologer->call==3?'bg-danger text-white':'bg-lightt text-dark'}}">I'm Busy</button>
                </a>

              </div>
              <div class="col-md-2 col-3 p-1">
                <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['call', 'hide'])}}')" href="javascript:void(0)">
                  <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->call==0?'bg-info text-white':'bg-lightt text-dark'}}">Hide</button>
                </a>

              </div>
            </div>
        </div>
        </div>
        </div>


        <!-- empty-images -->
        <div class="no-data text-center d-none">
         <img src="/assets/images/status.webp" alt="status" class="nodata">
         <h5 class="mt-3 text-dark"> No Status Found!</h5>
         <p class="text-secondary"> Status history will be displayed here.</p>
      </div>
      </div>
      <div id="video" role="tabpanel" aria-labelledby="video-tab" class="tab-pane mb-5 fade tab-text">
        <div class="card mb-3 mt-4 bg-light">
        <div class="card-body">
        <div class="mb-2">
            <h6 class="my-3"><i class="fa fa-phone-volume"></i> Video Call Status</h6>
            <div class="row">
              <div class="col-md-2 col-3 p-1">
                <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'online'])}}')" href="javascript:void(0)">
                  <button class="badge badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill  rounded-pill {{$astrologer->video==1?'bg-success text-white':'bg-lightt text-dark'}}">I'm Online</button>
                </a>

              </div>
              <div class="col-md-2 col-3 p-1">
                  <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'offline'])}}')" href="javascript:void(0)">
                    <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->video==2?'bg-dark text-white':'bg-lightt text-dark'}}">I'm Offine</button>
                  </a>

                </div>
                <div class="col-md-2 col-3 p-1">
                  <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'busy'])}}')" href="javascript:void(0)">
                    <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro  rounded-pill {{$astrologer->video==3?'bg-danger text-white':'bg-lightt text-dark'}}">I'm Busy</button>
                    </a>

                </div>
                <div class="col-md-2 col-3 p-1">
                  <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['video', 'hide'])}}')" href="javascript:void(0)">
                    <button class="badge p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->video==0?'bg-info text-white':'bg-lightt text-dark'}}">Hide</button>
                  </a>

                </div>
            </div>
        </div>
      </div>
      </div>
      </div>
      <div id="chat" role="tabpanel" aria-labelledby="chat-tab" class="tab-pane mb-5 fade show tab-text">
        <div class="card mb-3 mt-4 bg-light">
        <div class="card-body">
            <h6 class="my-3"><i class="fa fa-comments"></i> Chat Status</h6>
          <div class="row">
            <div class="col-md-2 col-3 p-1">
            <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'online'])}}')" href="javascript:void(0)">
              <button class="badge  p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->chat==1?'bg-success text-white':'bg-lightt text-dark'}}">I'm Online</button>
            </a>

          </div>
          <div class="col-md-2 col-3 p-1">
            <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'offline'])}}')" href="javascript:void(0)">
              <button class="badge  p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->chat==2?'bg-dark text-white':'bg-lightt text-dark'}}">I'm Offine</button>
            </a>

          </div>
          <div class="col-md-2 col-3 p-1">
            <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'busy'])}}')" href="javascript:void(0)">
              <button class="badge  p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->chat==3?'bg-danger text-white':'bg-lightt text-dark'}}">I'm Busy</button>
            </a>

          </div>
          <div class="col-md-2 col-3 p-1">
            <a class="text-dark" onclick="handleStatus(this, '{{route('astrologer.im.status', ['chat', 'hide'])}}')" href="javascript:void(0)">
              <button class="badge  p-3 px-2 px-md-4 ms-0 ms-md-2 fs-7 border-0 w-md-100 status-btn-astro rounded-pill {{$astrologer->chat==0?'bg-info text-white':'bg-lightt text-dark'}}">Hide</button>
            </a>
        </div>
        </div>
      </div>
      </div>
      </div>
    </div>
    </div>
    @else
      <div class="col-sm-9">
        <h4 class="h4">Your account is under review</h4>
      </div>
    @endif
    </div>
  </div>
</section>

@endsection
@push('scripts')
<script type="text/javascript">
  function handleStatus(that, url){
    $.ajax({
      url:url,
      method:'GET',
      success:function(response){
        $(that).parent().parent().find('button').removeClass('bg-lightt text-dark bg-success bg-dark bg-danger bg-info text-white').addClass('bg-lightt text-dark')
        if(response.status==1){
          $(that).find('button').removeClass('bg-lightt text-dark').addClass('bg-success text-white');
        }else if(response.status==2){
          $(that).find('button').removeClass('bg-lightt text-dark').addClass('bg-dark text-white');
        }else if(response.status==3){
          $(that).find('button').removeClass('bg-lightt text-dark').addClass('bg-danger text-white');
        }else{
          $(that).find('button').removeClass('bg-lightt text-dark').addClass('bg-info text-white');
        }

      },
      error:function(error){
        console.log(error)
      }
    });
  }
</script>
@endpush
