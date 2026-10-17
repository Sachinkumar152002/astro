@extends('layouts.app')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-tagsinput.css')}}">
@endpush

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            @include('main.account.partials.sidebar')
            <div class="col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::model($user, ['route' => 'account.jobprofile.update', 'method'=>'post', 'id'=>'profile-form']) !!}
                        <div class="row">

                            <div class="col-sm-12 col-md-12 box-white">
                                <h4> Job Profile</h4>
                                <div class="mb-30">

                                    <div class="clearfix"></div>

                                    <div class="card-sec mt-20">
                                        <div class="card-header1">
                                            <h3>Experience</h3>
                                            <a data-toggle="modal" data-target="#add-exp" href="#"><i class="fa fa-plus"></i></a>
                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head"> Sr. Web Designer</div>
                                                <div class="comname"> Smart Skill Tech Pvt. Ltd.</div>
                                                <div>Feb 2017 – Present <span>1 yr 8 mos</span></div>
                                                <div class="short-text">India</div>
                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head"> Sr. Web Designer</div>
                                                <div class="comname"> Smart Skill Tech Pvt. Ltd.</div>
                                                <div>Feb 2017 – Present <span>1 yr 8 mos</span></div>
                                                <div class="short-text">India</div>
                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head"> Sr. Web Designer</div>
                                                <div class="comname"> Smart Skill Tech Pvt. Ltd.</div>
                                                <div>Feb 2017 – Present <span>1 yr 8 mos</span></div>
                                                <div class="short-text">India</div>
                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head"> Sr. Web Designer</div>
                                                <div class="comname"> Smart Skill Tech Pvt. Ltd.</div>
                                                <div>Feb 2017 – Present <span>1 yr 8 mos</span></div>
                                                <div class="short-text">India</div>
                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head"> Sr. Web Designer</div>
                                                <div class="comname"> Smart Skill Tech Pvt. Ltd.</div>
                                                <div>Feb 2017 – Present <span>1 yr 8 mos</span></div>
                                                <div class="short-text">India</div>
                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                    </div>
                                    <div class="card-sec mt-20">
                                        <div class="card-header1">
                                            <h3>Education</h3>
                                            <a data-toggle="modal" data-target="#add-exp" href="#"><i class="fa fa-plus"></i></a>
                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head">K.L. Mehta D.N.P. School</div>
                                                <div class="comname">12th, Commerce</div>
                                                <div class="comname">52%</div>


                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>
                                        <div class="infobox">
                                            <div class="com-logo">
                                                <img src="https://via.placeholder.com/72x72 " />
                                            </div>
                                            <div class="cominfo">
                                                <div class="head">Arena Multemedia</div>
                                                <div class="comname">Advance Diploma in Multemedia, Web & Graphic Design</div>



                                            </div>
                                            <div class="edit-sec pull-right">
                                    <a href="#"><i class="fa fa-pencil edit"></i></a>
                                    </div>

                                        </div>



                                    </div>
                                    <div class="modal fade" id="add-exp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header modal-header2">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <h3 class="modal-title" id="myModalLabel">Modal title</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form>

                                                    <div class="row">
                                                      <div class="col-md-12">
                                                        <div class="form-group">
                                                          <input type="text" class="form-control" placeholder="Title" id="title">
                                                        </div>
                                                      </div>


                                                      <div class="col-md-12">
                                                        <div class="form-group">
                                                          <input type="text" class="form-control" placeholder="Company Name" id="company-name">
                                                        </div>
                                                      </div>

                                                      <div class="col-md-12">
                                                        <div class="form-group">
                                                          <input type="text" class="form-control" placeholder="Location" id="location">
                                                        </div>
                                                      </div>

                                                    </div>











                                                  </form>
                                            </div>
                                            <div class="modal-footer">

                                              <button type="button" class="btn btn-primary">Submit</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>











                                </div>
                                <!-- Split button -->
                            </div>
                        </div>
                       {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{asset('js/dropzone.js')}}"></script>
<script src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>
<script type="text/javascript">
var media_image_type = 'user';
$("#profile-form").validate({
    submitHandler : function(form){
        $('#profile-btn-submit').addClass('disable').attr('disabled', 'disabled');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                toastr.success('Successfully Updated');
                setTimeout(function(){
                    window.location.reload();
                }, 3000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                {{--  for(i in errors){
                    toastr.error(errors[i]);
                }  --}}
                console.log(errors);
            });
             return false;
        }
});
$('#hobbies').tagsinput({
  confirmKeys: [44],
   trimValue: true
});

</script>
@endpush