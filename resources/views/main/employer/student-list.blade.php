 @if(count($students)>0)
    <div class="pull-right">{{$students->links()}}</div>
    @foreach($students as $student)
            <div class="detail-box">
              <div class="detail-view-point">
                <div class="clien-logo-info">
                  <div class="clientlogo">
                    <img src="{{$student->details ? ($student->details->avatar ? $student->details->avatar->image(200,200) : $student->no_image) : $student->no_image}}" alt="student" height="90px" width="100px"/>
                  </div>
                  <div class="com-name-details">
                    <div class="job-title"><strong>{{ucfirst($student->name)}}</strong></div>
                    <div class="com-name com-name1">
                      <p>Looking for <strong>{{$student->details && $student->details->industry ? $student->details->industry->name : 'n/a'}}</strong></p>
                      <p><strong>Skill includes</strong>: {{$student->details && $student->details->skills ? $student->details->skills_text : 'n/a'}}</p>
                    </div>
                  </div>
                  <h5 class="more-info"><a href="javascript:void(0)" onclick="@if(Auth::guard('employer')->check()) viewStudentProfile({{$student->id}})@else alert('Please login as employer') @endif">View Candidate Details</a></h5>
                </div>
                <div class="video-resume">
                  @if($student->details && $video = $student->details->videofile)
                    <div class="card video-player" data-id="{{$student->details->id}}" data-title="" data-src="{{$video}}"></div>
                  @elseif($student->details && $student->details->youtube)
                  <iframe width="100%" height="144" src="{{$student->details->youtube}}" frameborder="0" allowfullscreen></iframe>
                  @else
                  <img src="{{asset('images/no-video.png')}}" class="img-responsive" alt="No Video">
                  @endif
                </div>
                <div class="clearfix"></div>
                <div class="detail-left">
                  <ul>
                    <li><span class="heading-title"><strong>Expected Salary</strong></span><span class="text">{{$student->details ? $student->details->expected_salary : 'n/a' }}</span></li>
                    <li><span class="heading-title"><strong>Preferred Location</strong></span><span class="text">{{$student->details && $student->details->locations ? $student->details->locations : 'n/a'}}</span></li>
                    <li><span class="heading-title"><strong>Speaks</strong></span><span class="text">{{$student->details && $student->details->speaks ? $student->details->speaks : 'n/a'}}</span></li>
                  </ul>
                </div>
                <div class="detail-right">
                  <ul>
                    <li><span class="heading-title"><strong>Prefered Shift</strong></span><span class="text">{{$student->details && $student->details->my_shift ? $student->details->my_shift : 'n/a'}}</span></li>
                    <li><span class="heading-title"><strong>Qualification</strong></span><span class="text">{{$student->details && $student->details->qualification ? $student->details->qualification->name : 'n/a'}}</span></li>
                    <li><span class="heading-title"><strong>Writes</strong></span><span class="text">{{$student->details && $student->details->writes ? $student->details->writes : 'n/a'}}</span></li>
                  </ul>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="details-tab-section blue-bg1">
                <ul>
                  <li><i class="glyphicon glyphicon-save"></i>

                    <a href="javascript:void(0)" onclick="@if(Auth::guard('employer')->check()) @if(auth()->guard('employer')->user()->currentPlan(auth()->guard('employer')->user()->id)) downloadStudentResume({{$student->id}}, '{{str_slug($student->name)}}') @else alert('Please subscribe') @endif @else alert('Only allowed for paid subscription') @endif">
                    Download Resume</a>
                  </li>

                <li><i class="glyphicon glyphicon-phone"></i> <a href="javascript:void(0)" onclick="@if(Auth::guard('employer')->check())  @if(auth()->guard('employer')->user()->currentPlan(auth()->guard('employer')->user()->id)) $(this).text('{{$student->details ? $student->details->phone : 'n/a' }}') @else alert('Please subscribe') @endif @else alert('Please login as employer') @endif">{{$student->details ? substr($student->details->phone,0,4).'xxxxxx' : 'n/a' }}</a></li>
                 <li ><button type="submit" class="box-btn" onclick="$(this).find('span').removeClass('glyphicon-heart-empty').addClass('red glyphicon-heart')"><span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span> </button></li>
              </ul>
            </div>
          </div>
          @endforeach
          <div class="pull-right">{{$students->links()}}</div>
          @else
          <p>No Result</p>
          @endif
