@extends('layouts.app')
@section('content')
<section class="emp-details">
  <div class="container">
    <div class="row">
      <div class="full-box">
        <div class="col-md-3">
            @include('main.employer.partials.filters')
        </div>
        <div class="col-md-9">
          <div class="right-box">
            <div class="search-job-box hidden">
              <label>Search Profile</label>
              <div class="select-box-right">
                <div class="select-bx">
                  <form class="form-search" onsubmit="return false;">
                    {!! Form::select('industry', $industries, $industry_id, ['class'=>'search-query' , 'onchange'=>'filterStudent()', 'id'=>'industry']) !!}
                    {!! Form::select('location', $locations, $location_id, ['class'=>'search-query' , 'onchange'=>'filterStudent()', 'id'=>'location']) !!}
                  </form>
                </div>
              </div>
            </div>
            <div id="studentList">
               loading...
            </div>
        </div></div>
      </div>
    </div>
  </div>
</section>
<!--Candidate Listing End Here-->

@include('main.employer.partials.profileview')
@endsection

@push('scripts')
<script src="{{asset('js/videoplayer.js')}}"></script>
<script type="text/javascript">
  var _filter_url = '{{route('employer.search.student')}}';
  var _filter_url_origin = '{{route('employer.search.student')}}';
  $(document).ready(function(){
        filterStudent();
  });

  var filterStudent = function() {
    var industry = new Array();
    $('#industries li.active').each(function(index){
      industry[index] = $(this).data('value');
    });
    var location = new Array();
    $('#locations li.active').each(function(index){
      location[index] = $(this).data('value');
    });
    var skills = new Array();
    $('#skills li.active').each(function(index){
      skills[index] = $(this).data('value');
    });
    var salary_range = new Array();
    $('#salary_range li.active').each(function(index){
      salary_range[index] = $(this).data('value');
    });
    var timings = new Array();
    $('#timings li.active').each(function(index){
      timings[index] = $(this).data('value');
    });
    var experience = new Array();
    $('#experience li.active').each(function(index){
      experience[index] = $(this).data('value');
    });
    var trained = new Array();
    $('#trained li.active').each(function(index){
      trained[index] = $(this).data('value');
    });
    var videoresume = new Array();
    $('#videoresume li.active').each(function(index){
      videoresume[index] = $(this).data('value');
    });
    var filterData = {
        'industry': industry,
        'location': location,
        'skills': skills,
        'salary_range': salary_range,
        'timings': timings,
        'experience': experience,
        'trained': trained,
        'videoresume': videoresume
    };
     axios.post(_filter_url, filterData)
            .then(function (response) {
                $('#studentList').html(response.data);
                reinitPagination();
                _reinitVideo();
            })
            .catch(function (error) {});
    }

    $('.dropdown-option').on('click', function(){
        $(this).toggleClass('active');
        _filter_url = _filter_url_origin;
        filterStudent();
    });

    function _reinitVideo(){
    // Video Player
    // $('.video-player').html('').removeClass('selected');
    $(".video-player").videoplayer({
      callback : {
        play : function() {
          // console.log("playing");
        },
        arrow : function(name, playerId) {
          // console.log(name, playerId);
          switch(name) {
          case "left":
            break;
          case "right":
            break;
          case "up":
            break;
          case "down":
            break;
          }
        },
        menuclicked : function(optname, playerId) {
          console.log(optname, playerId);
        }
      }
    });
    $(".video-player"+extraClass).removeClass('new');
  }

  var reinitPagination = function(){
       $('.pagination a').on('click', function(e){
         e.preventDefault();
          _filter_url = $(this).attr('href');
           filterStudent();
       });
  }

  var viewStudentProfile = function(student_id) {
    $('#classTable tbody').html('');
    axios.post('{{route('student.profile.view')}}', {student_id:student_id})
      .then(function (response) {
          var studentData = response.data;
          for(i in studentData) {
              $('#classTable tbody').append('<tr><td>'+i+'</td><td>'+studentData[i]+'</td></tr>');
          }
          $('#profile_view').modal('show');
      })
      .catch(function (error) {
          toastr.error("Error in getting data, Please try again after some time.");
      });
  }

  var downloadStudentResume = function(student_id, slug) {
      window.open('{{route('student.profile.down')}}/'+student_id+'/'+slug, '_blank');
  }
</script>
@endpush