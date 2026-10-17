
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <title>@yield('title', config('app.name'))</title>
     <meta name="description" content="@yield('description')"/>
     <meta name="keywords" content="@yield('keywords')"/>
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
     <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
     <!-- slick slider css -->
     <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slick.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slick-theme.css')}}" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
     <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
    <style >
       .serch-box {
       display: none; /* Hide by default */
       position: absolute;
       background-color: #fff;
       border: 1px solid #ccc;
       width: 100%;
       z-index: 1000;
     }

     .serch-box ul {
       list-style: none;
       padding: 0;
       margin: 0;
     }

     .serch-box ul li {
       padding: 8px;
       cursor: pointer;
     }

     .serch-box ul li:hover {
       background-color: #f1f1f1;
     }
   </style>
 </head>
 <body>
   <!-- header-start -->
 <section class=" position-sticky start-0 top-0 desktop header-top" style="z-index:99;">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-sm-10 navbar-brand ps-1">
      <div class="d-flex align-items-center">
        <a href="/">
          <img src="{{asset('assets/images/IIAG_New_Logo-For_Web.svg')}}" alt="IIAG logo">
        </a>
        <div class=" ms-2">
          @if(isset($showStatus) && $showStatus)
          <p class="m-0 p-0 fw-bold">Status</p>
          <p class="m-0 p-0"><span id="learnt-percentage">{{$percentage}}</span>% Learnt</p>
          @endif
        </div>
      </div>
      </div>
      <div class=" col-lg-6 col-sm-2 d-flex justify-content-end mx-auto py-1">
        <nav class="navbar navbar-expand-lg bg-body-dark p-0" id="header-nav">
          <div class="container-fluid container-xl p-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav align-items-center desk-n">
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'explore' ? 'active' : ''}}" href="{{route('astrologer.course.explore', [$course->slug])}}"> Course
                    <!-- <i class="bi color_home bi-house-fill"></i> -->
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'assignment' ? 'active' : ''}}" aria-current="page" href="{{route('astrologer.course.assignment', [$course->slug, 'assignment'])}}"> Assignment </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'quizz' ? 'active' : ''}}" aria-current="page" href="{{route('astrologer.course.assignment', [$course->slug, 'quizz'])}}"> Quizz </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'meeting' ? 'active' : ''}}" href="{{route('astrologer.course.meeting', [$course->slug])}}"> Mettings </a>
                </li>
                {{--
                <li class="nav-item pe-0">
                  <a class="pe-0 nav-link " href="{{route('user.course.financial')}}"> Financial </a>
                </li>
                --}}
                <li class="nav-item pe-0">
                  <a class="pe-0 nav-link " href="{{route('astrologer.dashboard')}}"><i class="bi bi-person-circle fs-5"></i></a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</section>

@yield('content')

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
 <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
 <script type="module" src="{{asset('assets/js/custom.js')}}"></script>
 <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
 <script src="{{asset('js/toastr.min.js')}}"></script>
 <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 @stack('scripts')
 </body>
 </html>
