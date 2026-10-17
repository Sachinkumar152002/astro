
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
     #toast-container>.toast-success{
       opacity: 10 !important;
     }
     #toast-container>.toast-error{
      opacity: 10 !important;
    }
    .h-con{
      padding: 5px 48px;
    }

    @media only screen and (max-width: 600px) {
      .nav-im{
        height: 40px !important;
      }
}
#rotateMessage {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background: #ffcc00;
    color: #000;
    font-size: 18px;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}
 </style>
 </head>
 <body>
   @php
   $user=auth()->guard('web')->user();
   @endphp
   <!-- header-start -->
 <section class="position-sticky start-0 top-0 header-top" style="z-index:99;">
  <div class="container-fluid h-con">
    <div class="row">
      <div class="col-lg-6 col-6 navbar-brand ps-1">
      <div class="d-flex gap-5 align-items-center">
        <a href="/">
          <img src="{{asset('assets/img/logo.png')}}" class="nav-im" alt="Logo">
        </a>
        <div class="ms-2 d-none d-md-block">
          @if(isset($course) && $course)
            <p class="m-0 p-0 fw-bold">{{$course->name}}</p>
          @endif
          @if(isset($showStatus) && $showStatus)
            <!-- <p class="m-0 p-0 fw-bold">Status</p> -->
            <p class="m-0 p-0"><span id="learnt-percentage">{{$percentage}}</span>% Learnt</p>
          @endif
        </div>
      </div>
      </div>
      <div class=" col-lg-6 col-6 d-flex justify-content-end mx-auto py-1">
        <nav class="navbar navbar_explore navbar-expand-lg bg-body-dark p-0" id="header-nav">
          <div class="container-fluid container-xl p-0">

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav align-items-center desk-n">
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'explore' ? 'active' : ''}}" href="{{route('user.course.explore', [$course->slug])}}"> Course
                    <!-- <i class="bi color_home bi-house-fill"></i> -->
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'assignment' ? 'active' : ''}}" aria-current="page" href="{{route('user.course.assignment', [$course->slug, 'assignment'])}}"> Assignment </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'quizz' ? 'active' : ''}}" aria-current="page" href="{{route('user.course.assignment', [$course->slug, 'quizz'])}}"> Quizz </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{isset($nav) && $nav == 'meeting' ? 'active' : ''}}" href="{{route('user.course.meeting', [$course->slug])}}"> Mettings </a>
                </li>
                <li class="nav-item pe-0">
                  @if($course->modules->count()==$course->passedModules($user->id, 'user')->count())
                  <a class="pe-0 nav-link" target="_blank" href="{{route('user.course.certificatee', $course->slug)}}"> Certificate</a>
                  @else
                  <a class="pe-0 nav-link pointer" onclick="javascript:toastr.error('You have not completed the course. Please watch all content.')"> Certificate </a>
                  @endif
                </li>
                <li class="nav-item pe-0">
                  <a class="pe-0 nav-link text-topic" href="{{route('user.dashboard')}}"><i class="bi bi-person-circle fs-5"></i></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="ms-2 d-block d-md-none">
          @if(isset($showStatus) && $showStatus)
          <p class="m-0 p-0 fw-bold">Status</p>
          <p class="m-0 p-0"><span id="learnt-percentage">{{$percentage}}</span>% Learnt</p>
          @endif
        </div>
            <li data-bs-toggle="offcanvas" data-bs-target="#mobilemenu" aria-controls="offcanvasWithBothOptions" class="d-block d-md-none small_menu">
                          <a href="#">
                              <i class="bi bi-list"></i>
                          </a>
                          </li>

                          <div class="offcanvas offcanvas-start menu-offf" data-bs-scroll="true" tabindex="-1" id="mobilemenu" aria-labelledby="offcanvasWithBothOptionsLabel" style="z-index:9999; width:70%;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex justify-content-start">
    </div>

     <ul class="navbar-nav mobile-menu d-block d-md-none">
                <li class="nav-item py-2 border-bottom">
                  <a class="nav-link fs-6 {{isset($nav) && $nav == 'explore' ? 'active' : ''}}" href="{{route('user.course.explore', [$course->slug])}}"> Course
                    <!-- <i class="bi color_home bi-house-fill"></i> -->
                  </a>
                </li>
                <li class="nav-item py-2 border-bottom">
                  <a class="nav-link fs-6 {{isset($nav) && $nav == 'assignment' ? 'active' : ''}}" aria-current="page" href="{{route('user.course.assignment', [$course->slug, 'assignment'])}}"> Assignment </a>
                </li>
                <li class="nav-item py-2 border-bottom">
                  <a class="nav-link fs-6 {{isset($nav) && $nav == 'quizz' ? 'active' : ''}}" aria-current="page" href="{{route('user.course.assignment', [$course->slug, 'quizz'])}}"> Quizz </a>
                </li>
                <li class="nav-item py-2 border-bottom">
                  <a class="nav-link fs-6 {{isset($nav) && $nav == 'meeting' ? 'active' : ''}}" href="{{route('user.course.meeting', [$course->slug])}}"> Mettings </a>
                </li>
                <li class="nav-item py-2 border-bottom pe-0">
                  @if($course->modules->count()==$course->passedModules($user->id, 'user')->count())
                  <a class="pe-0 nav-link fs-6" target="_blank" href="{{route('user.course.certificatee', $course->slug)}}"> Certificate</a>
                  @else
                  <a class="pe-0 nav-link fs-6 pointer" onclick="javascript:toastr.error('You have not completed the course. Please watch all content.')"> Certificate </a>
                  @endif
                </li>
                <li class="nav-item py-2 border-bottom pe-0">
                  <a class="pe-0 nav-link " href="{{route('user.dashboard')}}"><i class="bi bi-person-circle fs-5"></i></a>
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
<div id="rotateMessage">Please rotate your screen for the best experience in landscape mode.</div>

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
 <script>
     function checkOrientation() {
         let rotateMessage = document.getElementById('rotateMessage');
         if (window.innerHeight > window.innerWidth) {
             rotateMessage.style.display = 'block';
         } else {
             rotateMessage.style.display = 'none';
         }
     }

     window.addEventListener('resize', checkOrientation);
     window.addEventListener('load', checkOrientation);
 </script>
 </body>
 </html>
