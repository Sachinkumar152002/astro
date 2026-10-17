@extends(themeView().'account.explore.layout')

@section('title', 'IIAG | '.$course->name)
@section('keywords', '')
@section('description','')

@section('content')
  <section>
     <div class="container-fluid course-sidebar1">

       <div class="row">
         <div class="col-md-4 order-2">
        <div class="course-sidebar">
           <div class="sticky-sidebar top-0">
             <div class="card card-bg">
               <div class="f-heading-main p-2 float-shadow-2">
                 <h2 class="ms-0 text-white fs-4 text-center" style="padding-top: 8px;">Course Content</h2>
               </div>
               <div class="card-body p-3">
                 @php
                  $modules = $course->modules;
                  $totalchapters = 0;
                  $totaltopics = 0;
                 @endphp
                  <ul class="group-list d-flex flex-wrap gap-md-4 gap-2 p-0 justify-content-end">
                    <li class="list-group-item fw-semibold text-blue underline p-0" id="totalchapters">{{$totalchapters}} Chapters</li>
                    <li class="list-group-item fw-semibold text-blue underline p-0" id="totaltopics">{{$totaltopics}} Topics</li>
                 </ul>
                 <div class="accordion" id="accordionExample">
                   @php
                     $modulesIdArray = [];
                     $first = null;
                   @endphp
                  @foreach($modules as $key => $module)
                    @php
                      array_push($modulesIdArray, $module->id);
                      $contents = $module->content;
                    @endphp
                    @if(count($contents))
                    @php
                     $totalchapters++;
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
                        <div class="row display_courses_explore d-flex align-items-center">
                          <div class="col-md-2">
                            <div class="a-btn-bg d-flex align-items-center justify-content-center">
                                <i class="bi bi-folder2-open text-white fw-bold fs-5"></i>
                            </div>
                          </div>
                          <div class="col-md-10">
                            <p class="fw-semibold px-0 course-titles fs-6 pb-0 mb-0 line-h">{{ $module->name }}</p>
                            @php
                            $contenttotaltopics=0;
                            @endphp
                            <p class="px-0 fw-semibold d-md-block fs-7  mb-0 margin-t mt-0 text-topic" id="contenttotaltopics{{$key}}">Topic: {{$contenttotaltopics}}</p>
                          </div>
                        </div>
                       </button>
                     </h2>
                     <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" style="" data-bs-parent="#accordionExample">
                      <div class="accordion-body course-acco pb-0 p-0 m-0">
                      <ul class="list-group course-list-g pb-0 border-top border-dark rounded-0 bg-g p-2">
                        @foreach ($contents as $contentKey => $content)
                          @if($video = $content->videos)
                            @php
                              if($first==null){
                                $first = $video;
                              }
                              $totaltopics++;
                              $contenttotaltopics++;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center  border-bottom">
                              @if($video->type == 'youtube')
                                @php
                                  $url = 'https://www.youtube.com/embed/'.$video->video_id;
                                @endphp
                                <span class="d-flex align-items-center pointer" onclick="youtubeVideoPlay('{{$url}}','{{$video->video_id}}&rel=0', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')">
                                  <div class="">
                                  <div class="content-icon d-flex align-items-center justify-content-center">
                                  <i class="bi bi-arrow-right"></i>
                                  </div>
                                  </div>
                                  {{ $content->title }}
                                </span>
                                <div class="d-flex">
                                  @if($file = $content->files)
                                    <a href="{{asset($file->file)}}" target="_blank" class="pointer">
                                      <i class="fs-4 bi bi bi-file-earmark-fill text-gold pointer fs-2 ms-1" ></i>
                                    </a>
                                  @endif
                                    <i onclick="youtubeVideoPlay('{{$url}}','{{$video->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')" class="pointer fs-4 bi bi-play-circle ms-2"></i>
                                </div>
                                @elseif($video->type == 'vimeo')
                                  @php
                                    $url = $video->player_embed_url ? : 'https://player.vimeo.com/video/'.$video->video_id;
                                  @endphp
                                  <span class="d-flex align-items-center pointer" onclick="vimeoVideoPlay('{{$url}}','{{$video->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')">
                                    <div class="">
                                    <div class="content-icon d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-right"></i>
                                    </div>
                                    </div>
                                    {{ $content->title }}
                                  </span>
                                  <div class="d-flex">
                                    @if($file = $content->files)
                                      <a href="{{asset($file->file)}}" class="pointer">
                                        <i class="fs-4 bi bi bi-file-earmark-fill text-gold pointer fs-2 ms-1" ></i>
                                      </a>
                                    @endif
                                      <i onclick="vimeoVideoPlay('{{$url}}','{{$video->video_id}}', '{{$course->id}}', '{{$module->id}}', '{{$content->id}}')" class="pointer fs-4 bi bi-play-circle ms-2"></i>
                                  </div>
                                @endif
                            </li>
                          @endif
                        @endforeach
                      </ul>
                      <script type="text/javascript">
                        document.getElementById('contenttotaltopics{{$key}}').innerText='Topic: {{$contenttotaltopics}}';
                      </script>
                      @php
                        $hasPassed = $module->hasPassed;
                      @endphp
                      <div class="d-flex align-items-center justify-content-between p-2 border-top border-dark">
                        <p class="m-0 p-0 fs-7 fw-bold">I passed this chapter</p>
                          <label class="switch" for="checkbox{{$module->id}}">
                          <input type="checkbox" id="checkbox{{$module->id}}" {{$hasPassed?'checked disabled':''}}/>
                        <div class="slider round"
                          @if(!$hasPassed)
                            onclick="passedModule('{{$course->id}}', '{{$module->id}}', this)"
                          @endif
                        >
                        </div>
                      </div>
                      <!-- toggle-btn-start -->
                     </div>
                   </div>
                 </div>
                 @endif
                 @endforeach
               </div>
             </div>
           </div>
         </div>
       </div>
       </div>
       <div class="col-md-8 order-1 order-md-12">

           @php
           $firstType = '';
           $firstVideo = null;
           $firstVideoUrl = '';
           if($first){
              $firstType = $first->type;
              if($firstType == 'youtube'){
                $firstVideoUrl = 'https://www.youtube-nocookie.com/embed/'.$first->video_id;
              }elseif($firstType == 'vimeo'){
                $firstVideoUrl = $first->player_embed_url;
              }elseif($firstType == 'other'){
                $firstVideoUrl = $first->video_url;
              }
            }
           @endphp
          <!-- <div id="main-iframe"></div> -->
            <div class="player-box w-100 h-100" id="main-frame"></div>
       </div>
     </div>
     </div>
   </section>
@endsection
@push('scripts')
<script src="https://player.vimeo.com/api/player.js"></script>
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
<script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
  <script>
  document.getElementById('totalchapters').innerText = '{{$totalchapters}} Chapters';
  document.getElementById('totaltopics').innerText = '{{$totaltopics}} Topics';
    @if(!$first)
      toastr.error('No content fount.');
      setTimeout(function (){
        {{--
          window.location.href = "{{route('user.purchasecourse')}}";
        --}}
      }, 2000);
    @endif
    var mainFrame = $('#main-frame');
    var canWatchVideo = true;
    // var iframe = $('#main-iframe').find('iframe');
    var clearIntervalGetCurrentTime = '';
    var watchedUrl = '';
    function vimeoVideoPlay(url, video_id=0, course_id=0, module_id=0, content_id=0){
      if(!canWatchVideo){
        toastr.error("Watch previous chapters to unlock this chapter.");
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
          });
        }, 3000);
      }
      player.on('play', function(e){
        setIntervalCurrentTime();
      });
      player.on('pause', function(e){
        clearInterval(clearIntervalGetCurrentTime)
      });
      // player.getVideoTitle().then(function(title){
      //   console.log('title:', title);
      // });
      player.getDuration().then(function(duration){
        //in seconds
        updateWatchTime(watchedUrl, {duration:parseInt(duration)})
      });
      player.on('ended', function(data){
        clearInterval(clearIntervalGetCurrentTime)
      });
    }

      function youtubeVideoPlay(url, video_id = 0, course_id = 0, module_id = 0, content_id = 0) {
          if(!canWatchVideo){
              toastr.error("Watch previous chapters to unlock this chapter.");
              return 0;
          }
          var watchedUrl = @json(asset('/account'));
          watchedUrl = `${watchedUrl}/course/${course_id}/module/${module_id}/content/${content_id}/video/${video_id}`;
          clearInterval(clearIntervalGetCurrentTime);
          mainFrame.html('');
          url = `https://www.youtube.com/embed/${video_id}?origin=https://plyr.io&iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1`;
          let iframe = $("<iframe>", {src:url});
          iframe.attr('width', '100%')
          iframe.attr('height', '100%')
          mainFrame.append(iframe);
          const player = new Plyr('#main-frame');
          player.on('ready', function() {
            var duration = player.duration;
            updateWatchTime(watchedUrl, { duration: parseInt(duration) });
            player.on('play', function () {
                setIntervalCurrentTime();
            });
            player.on('pause', function () {
                clearInterval(clearIntervalGetCurrentTime);
            });
            player.on('ended', function () {
                clearInterval(clearIntervalGetCurrentTime);
            });
            function setIntervalCurrentTime() {
                clearIntervalGetCurrentTime = setInterval(function () {
                    const currentTime = player.currentTime;
                    updateWatchTime(watchedUrl, { currentTime: parseInt(currentTime) });
                }, 3000);
            }
          });
          player.on('error', function (error) {
              console.error('Error with the video:', error);
          });
      }
  </script>
  <script type="text/javascript">
    $(document).ready(function (){
      var modulesIdArray = @json($modulesIdArray);
      var firstType = @json($firstType);
      var firstVideoUrl = @json($firstVideoUrl);
      var co_id = @json($course?$course->id:0);
      var mod_id = @json($module?$module->id:0);
      let con_id = @json($content?$content->id:0);
      var vid_id = @json($first?$first->video_id:0);
      if(firstType == 'youtube'){
        setTimeout(()=>{
          youtubeVideoPlay(firstVideoUrl, vid_id, co_id, mod_id, con_id);
        }, 300);
      }else if(firstType == 'vimeo'){
        setTimeout(()=>{
          vimeoVideoPlay(firstVideoUrl, vid_id, co_id, mod_id, con_id);
        }, 300);
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
      if(!canWatchVideo){
        toastr.error("You can't passed this module.");
        setTimeout(function(){
          $('#checkbox'+module_id).prop('checked', false);
        }, 500)
        return false;
      }
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
              toastr.success("You have successfully passed this module.");
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
    if(offcanvasElement){
      offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
       document.body.classList.add('overflow-hidden');
      });
      offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
       document.body.classList.remove('overflow-hidden');
      });
    }
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

  </script>
@endpush
