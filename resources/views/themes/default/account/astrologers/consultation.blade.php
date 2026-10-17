@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
@include(themeView().'astrologers._partials.top')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-sm-9" id="profile-app">
            <div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 style="display: none;" v-show="successmessage!=''"
                                class="alert alert-success text-center" v-html="successmessage"></h3>
                            <p style="display: none;" v-show="errormessage!=''" class="alert alert-danger"
                                v-html="errormessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs " id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active text-dark" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Video Consultation</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-dark" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Chat consultation</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-dark" id="bank-tab" data-bs-toggle="tab" href="#bank" role="tab"
                        aria-controls="bank" aria-selected="true">Call Consultation</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane mb-5 fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form class="mt-4">
                        <div class="row">
                            <div class="col-sm-12">
                              <table class="table table-success table-striped table-responsive">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Astrolgers Name</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Schedule Date</th>
                    <th colspan="2">Start Time</th>
                    <th colspan="2">End Time</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($video_consultation as $item)
                  <tr>
                    <th scope="row">1</th>
                    <td>{{$item->astrologer->display_name}}</td>
                    <td>{{$item->user->name}}</td>
                    <td>{{$item->meeting_date}}</td>
                    <td>{{$item->start_time}}</td>
                      <td>{{$item->end_time}}</td>
                    <td><a class="text-primary text-decoration-underline" href="{{$item->meeting_link}}">Join Now</a></td>
                  </tr>
              @endforeach
                </tbody>
                </table>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="tab-pane mb-5 fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="mt-4">
                        <div class="row">
                          <table class="table table-success table-striped table-responsive">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Astrolgers Name</th>
                <th scope="col">User Name</th>
                <th scope="col">Schedule Date</th>
                <th colspan="2">Schedule Time</th>
              </tr>
            </thead>
            <tbody>
              @foreach($chat_consultation as $item)
              <tr>
                <th scope="row">1</th>
                <td>{{$item->user->name}}</td>
                <td>{{$item->astrologer->display_name}}</td>
                <td>{{$item->start_time}}</td>
                <td>{{$item->end_time}}</td>
                <td><a class="text-primary text-decoration-underline" href="/astrologer/{{$item->chat_key}}/startchat">Join Now</a></td>
              </tr>
              @endforeach
            </tbody>
            </table>
                        </div>
                    </form>
                </div>

                <div class="tab-pane mb-5 fade show" id="bank" role="tabpanel" aria-labelledby="profile-tab">
                    <form class="mt-4">
                        <div class="row">
                          <table class="table table-success table-striped table-responsive">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Astrolgers Name</th>
                <th scope="col">User Name</th>
                <th scope="col">Schedule Date</th>
                <th colspan="2">Schedule Time</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>@mdo</td>
                <td><a class="text-primary text-decoration-underline" href="#">Join Now</a></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
                <td>@mdo</td>
                <td><a class="text-primary text-decoration-underline" href="#">Join Now</a></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>@twitter</td>
                <td>@twitter</td>
                <td>@twitter</td>
                <td>@mdo</td>
                <td><a class="text-primary text-decoration-underline" href="#">Join Now</a></td>
              </tr>
            </tbody>
            </table>

                        </div>
                    </form>


                </div>

            </div>
            <div id="media-img-manager" tabindex="-1" role="dialog" aria-labelledby="MediaImageManager"
                aria-hidden="true" class="modal fade text-center">
                <div role="document" class="modal-dialog modal-media">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="MediaImageManager" class="modal-title">Upload</h4>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="dropzone" id="file-upload"></div>
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <small class="text-danger">* Only .jpg, .jpeg, .png@{{upload_type!='image_id'?' ,.pdf, .dox,
                                .docx':''}} images are allowed</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
