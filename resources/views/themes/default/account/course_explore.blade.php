
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
          <img src="{{asset('assets/images/IIAG_New_Logo-For_Web.svg')}}" alt="IIAG Logo">

        </a>
        <div class=" ms-2">
          <p class="m-0 p-0 fw-bold">Status</p>
          <p class="m-0 p-0"><span id="learnt-percentage">{{$percentage}}</span>% Learnt</p>
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
                  <a class="nav-link active" href="/"> Course

                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " aria-current="page" href="{{route('user.course.assignment', [$course->slug])}}"> Assignment </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="/talk-to-astrologers"> Mettings </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link " href="/astrology"> Quizzes </a>
                </li>
                <li class="nav-item pe-0">
                  <a class="pe-0 nav-link " href="/vastu-shastra"> Financial </a>
                </li>
                <li class="nav-item pe-0">
                  <a class="pe-0 nav-link " href="/account/dashboard"><i class="bi bi-person-circle fs-5"></i></a>
                </li>

              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</section>
<!-- header-section-end -->
  <section>
     <div class="container-fluid pt-0">
       <div class="row">
         <div class="col-md-4 course-sidebar">
           <div class="sticky-sidebar top-0">
             <div class="card card-bg">
               <div class="f-heading-main p-2 float-shadow-2">
                 <h2 class="ms-0 text-white fs-5">Course Content</h2>
               </div>
               <div class="card-body p-3" style="border: 2px solid #9b2303; border-radius: 0px 0px 10px 10px;">
                 @php
                  $modules = $course->modules;
                 @endphp
                  <ul class="group-list d-flex flex-wrap gap-md-4 gap-2 p-0 justify-content-end">
                   <li class="list-group-item fw-semibold text-blue underline p-0">{{count($modules)}} Modules</li>
                   <li class="list-group-item fw-semibold text-blue underline p-0">{{$course->topics->count()}} lectures</li>
                 </ul>
                 <div class="accordion" id="accordionExample">
                   @php
                   $modulesIdArray = [];
                   @endphp
                  @foreach ($modules as $key => $module)
                    @php
                      array_push($modulesIdArray, $module->id);
                    @endphp
                  <div class="accordion-item c-detais p-0">
                     <h2 class="accordion-header">
                       <button class="accordion-button collapsed p-2"
                       @if($key)
                        onclick="canWatchModule('{{$course->id}}', '{{$module->id}}')"
                        @else
                        onclick="watchFirstModule('{{$course->id}}', '{{$module->id}}')"
                       @endif
                       type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="{{$key?'false':'true'}}" aria-controls="collapse{{ $key }}">
                        <div class="row d-flex align-items-center">
                          <div class="col-md-2">
                            <div class="a-btn-bg2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-folder2-open text-white fw-bold fs-5"></i>
                            </div>
                          </div>
                          <div class="col-md-10 pt-2">
                            <p class="fw-semibold px-0 course-titles fs-7 pb-0 mb-0 line-h">{{ $module->name }}</p>
                            <p class="d-none px-0 fw-semibold d-md-block fs-8 mb-0 margin-t mt-0 text-orange">Topic: {{$module->content->count()}}</p>
                          </div>
                        </div>
                       </button>
                     </h2>
                     <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" style="" data-bs-parent="#accordionExampleS">
                      <div class="accordion-body course-acco pb-0 p-0 m-0">
                      <ul class="list-group course-list-g pb-0 border-top border-dark rounded-0 bg-g p-2">
                        @foreach ($module->content as $contentKey => $content)
                          @if($content->videos)
                            <li class="list-group-item d-flex justify-content-between align-items-center  border-bottom">
                              @if($content->videos->type == 'youtube')
                                <span class="d-flex align-items-center pointer" onclick="youtubeVideoPlay('https://www.youtube.com/embed/{{$content->videos->video_id}} rel=0&amp;','{{$content->videos->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')">
                                  <div class="">
                                  <div class="content-icon d-flex align-items-center justify-content-center">
                                  <i class="bi bi-arrow-right"></i>
                                  </div>
                                  </div>
                                  {{ $content->title }}
                                </span>
                                <div class="d-flex">
                                  @if($content->files)
                                    <a href="{{asset($content->files->file)}}" class="pointer">
                                      <i class="fs-4 bi bi bi-file-earmark-fill text-gold pointer fs-2 ms-1" ></i>
                                    </a>
                                  @endif
                                    <i onclick="youtubeVideoPlay('https://www.youtube.com/embed/{{$content->videos->video_id}}','{{$content->videos->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')" class="pointer fs-4 bi bi-play-circle ms-2"></i>
                                </div>
                                @elseif($content->videos->type == 'vimeo')
                                  @php
                                    //$url = vimeoVideo($content->videos->video_id);
                                    $url = 'https://player.vimeo.com/video/'.$content->videos->video_id.'?h='.$content->videos->video_hash;
                                  @endphp
                                  <span class="d-flex align-items-center pointer" onclick="vimeoVideoPlay('{{$url}}','{{$content->videos->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')">
                                    <div class="">
                                    <div class="content-icon d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-right"></i>
                                    </div>
                                    </div>
                                    {{ $content->title }}
                                  </span>
                                  <div class="d-flex">
                                    @if($content->files)
                                      <a href="{{asset($content->files->file)}}" class="pointer">
                                        <i class="fs-4 bi bi bi-file-earmark-fill text-gold pointer fs-2 ms-1" ></i>
                                      </a>
                                    @endif
                                      <i onclick="vimeoVideoPlay('{{$url}}','{{$content->videos->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')" class="pointer fs-4 bi bi-play-circle ms-2"></i>
                                  </div>
                                @endif
                            </li>
                          @endif
                        @endforeach
                      </ul>
                      <!-- toggle-btn-start -->
                      <div class="d-flex align-items-center justify-content-between p-2 border-top border-dark">
                        <p class="m-0 p-0 fs-7 fw-bold">I passed this chapter</p>
                          <label class="switch" for="checkbox">
                          <input type="checkbox" id="checkbox{{$module->id}}" {{$module->exploreModule?'checked':''}}/>
                        <div class="slider round"
                          @if(!$module->exploreModule)
                            onclick="passedModule('{{$course->id}}', '{{$module->id}}', this)"
                          @endif
                        ></div>
                      </div>
                      <!-- toggle-btn-start -->
                     </div>
                   </div>
                 </div> @endforeach
               </div>
             </div>
           </div>
         </div>
       </div>
       <div class="col-md-8 ">
           @php
            $firstType = $first->type;
            $firstVideo = null;
            if($firstType == 'youtube'){
              $firstVideoUrl = 'https://www.youtube.com/embed/'.$first->video_id;
            }elseif($firstType == 'vimeo'){
              $firstVideoUrl = vimeoVideo($first->video_id);
            }elseif($firstType == 'other'){
              $firstVideoUrl = $first->video_url;
            }
           @endphp
        <div class="player-box w-100 h-100" id="main-frame">
          <!-- <div id="main-iframe"></div> -->
        </div>
       </div>
     </div>
     </div>
   </section>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
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
      <script>
        var mainFrame = $('#main-frame');
        var canWatchVideo = true;
        // var iframe = $('#main-iframe').find('iframe');
        var clearIntervalGetCurrentTime = '';
        var watchedUrl = '';
          function vimeoVideoPlay(url, video_id=0, course_id=0, module_id=0, content_id=0){
            if(!canWatchVideo){
              Swal.fire({
                  icon: 'error',
                  title: "Watch previous chapters to unlock this chapter.",
                  confirmButtonText: 'Okay'
              });
              return 0;
            }
            var watchedUrl = @json(asset('/account'));
            watchedUrl = `${watchedUrl}/course/${course_id}/module/${module_id}/content/${content_id}/video/${video_id}`;
            mainFrame.html('');
            let iframe = $("<iframe>", {src:url});
            iframe.attr('width', '100%')
            iframe.attr('height', '100%')
            mainFrame.append(iframe)
            clearInterval(clearIntervalGetCurrentTime)
            let player = new Vimeo.Player(iframe);
            function setIntervalCurrentTime(){
              clearIntervalGetCurrentTime = setInterval(function (){
                player.getCurrentTime().then(function(currentTime){
                  //parseInt
                  updateWatchTime(watchedUrl, {currentTime:parseInt(currentTime)})
                  console.log('currentTime', currentTime);
                });
              }, 5000);
            }
            player.on('play', function(e){
              console.log('Played the video');
              setIntervalCurrentTime();
            });
            player.on('pause', function(e){
              console.log('pause the video');
              clearInterval(clearIntervalGetCurrentTime)
            });
            player.getVideoTitle().then(function(title){
              console.log('title:', title);
            });
            player.getDuration().then(function(duration){
              //in seconds
              updateWatchTime(watchedUrl, {duration:parseInt(duration)})
              console.log('duration', duration);
            });
            player.on('ended', function(data){
              clearInterval(clearIntervalGetCurrentTime)
              console.log('data', data)
            });
          }
          function youtubeVideoPlay(url, video_id=0, course_id=0, module_id=0, content_id=0){
            if(!canWatchVideo){
              Swal.fire({
                  icon: 'error',
                  title: "Watch previous chapters to unlock this chapter.",
                  confirmButtonText: 'Okay'
              });
              return 0;
            }
            var watchedUrl = @json(asset('/account'));
            watchedUrl = `${watchedUrl}/course/${course_id}/module/${module_id}/content/${content_id}/video/${video_id}`;
            clearInterval(clearIntervalGetCurrentTime)
            mainFrame.html('');
            let iframe = $("<div>", {id:'main-iframe'});
            mainFrame.append(iframe)
            let player = new YT.Player('main-iframe', {
              height: '100%',
              width: '100%',
              videoId: video_id,
              events: {
                  'onReady': onPlayerReady,
                  'onStateChange': onPlayerStateChange
              }
            });
            function setIntervalCurrentTime(){
              clearIntervalGetCurrentTime = setInterval(function (){
                let currentTime = player.getCurrentTime();
                updateWatchTime(watchedUrl, {currentTime:parseInt(currentTime)});
              }, 3000);
            }
            function onPlayerReady(event) {
              var duration = event.target.getDuration();
              updateWatchTime(watchedUrl, {duration:parseInt(duration)})
              console.log('duration', duration)
            }
            function onPlayerStateChange(event) {
              if (event.data == YT.PlayerState.PLAYING) {
                setIntervalCurrentTime();
                console.log('youtube video plyaing')
              }
              if (event.data == YT.PlayerState.PAUSED) {
                console.log('youtube video paused');
                clearInterval(clearIntervalGetCurrentTime)
              }
              if (event.data == YT.PlayerState.ENDED) {
                console.log('youtube video end');
                clearInterval(clearIntervalGetCurrentTime)
              }
            }
          }
      </script>
      <script type="text/javascript">
        $(document).ready(function (){
          var modulesIdArray = @json($modulesIdArray);
          var firstType = @json($firstType);
          var firstVideoUrl = @json($firstVideoUrl);
          var co_id = @json($course->id);
          var mod_id = @json($module->id);
          let con_id = @json($content->id);
          var vid_id = @json($first->video_id);
          if(firstType == 'youtube'){
            youtubeVideoPlay(firstVideoUrl, vid_id, co_id, mod_id, con_id);
          }else if(firstType == 'vimeo'){
            vimeoVideoPlay(firstVideoUrl, vid_id, co_id, mod_id, con_id);
            console.log('vimeo')
          }else{
            iframe.attr('src', firstVideoUrl);
          }
        });
      </script>
      <script type="text/javascript">
        function updateWatchTime(url, formData){
          $.ajax({
              url: url,
              method: "POST",
              data: formData,
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                console.log(response);
              },
              error: function(xhr) {
                console.log(xhr)
              }
          });
        }

        function canWatchModule(course_id, module_id){
          var modulesIdArray = @json($modulesIdArray);
          let moduleIdIndex = modulesIdArray.indexOf(parseInt(module_id));
          let subModulesIdArray = modulesIdArray.slice(0, moduleIdIndex);
          let url = @json(asset('/account'));
          url += `/course/${course_id}/module/${module_id}`
          $.ajax({
              url: url,
              method: "POST",
              data: {modulesIds:subModulesIdArray},
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                console.log(response);
                  if(response.success){
                    canWatchVideo =  true;
                    $('#learnt-percentage').html(response.percentage)
                  }else{
                    canWatchVideo =  false;
                  }
              },
              error: function(xhr) {
                console.log(xhr)
              }
          });
        }
        function passedModule(course_id, module_id, elem){
          let url = @json(asset('/account'));
          url += `/course/${course_id}/module/${module_id}/passed`;
          $.ajax({
              url: url,
              method: "POST",
              data: {},
              headers:{
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response){
                if(response.success){
                  $(elem).attr('onclick', '');
                  $('#checkbox'+module_id).prop('checked', true);
                  $('#checkbox'+module_id).prop('disabled', true);
                }
              },
              error: function(xhr){
                console.log(xhr)
              }
          });
        }
        function watchFirstModule(course_id, module_id){
          canWatchVideo =  true;
        }
      </script>
     <script>
       window.OneSignalDeferred = window.OneSignalDeferred || [];
       OneSignalDeferred.push(async function(OneSignal) {
         await OneSignal.init({
           appId: "6ccdbecc-acca-4215-bc87-2d88db7581da",
         });
       });
     </script>
     <script>
      document.addEventListener('DOMContentLoaded', function () {
        var offcanvasElement = document.getElementById('offcanvasWithBothOptions');
        offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
         document.body.classList.add('overflow-hidden');
        });
        offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
         document.body.classList.remove('overflow-hidden');
        });
      });
     </script>

     <script type="text/javascript">
       $(document).ready(function() {
         $('#main-iframe').find('iframe').attr('width', '100%');
         $('#main-iframe').find('iframe').attr('height', '');
       })

       function getVimeoUrl(videoId) {
         if (!videoId) {
           console.error("Video ID is undefined or missing");
           return 0;
        }
         if(iframe){
           $('#main-iframe').html(iframe);
           $('#main-iframe').find('iframe').attr('width', '100%');
           $('#main-iframe').find('iframe').attr('height', '');
         }else{
           window.open(videoId, '_blank');
         }
       }

       $(function() {
       var Accordion = function(el, multiple) {
         this.el = el || {};
         // more then one submenu open?
         this.multiple = multiple || false;

         var dropdownlink = this.el.find('.dropdownlink');
         dropdownlink.on('click',
                         { el: this.el, multiple: this.multiple },
                         this.dropdown);
       };

       Accordion.prototype.dropdown = function(e){
         var $el = e.data.el,
             $this = $(this),
             //this is the ul.submenuItems
             $next = $this.next();

         $next.slideToggle();
         $this.parent().toggleClass('open');

         if(!e.data.multiple){
           $el.find('.submenuItems').not($next).slideUp().parent().removeClass('open');
         }
       }
       var accordion = new Accordion($('.accordion-menu'), false);
     })
     </script>
 </body>
 </html>
