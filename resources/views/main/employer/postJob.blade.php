@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

@endpush

@section('content')
<section class="middle-section">
  <div class="container">
    <div class="row">
      @include('main.employer.account.sidebar')
      <div class="col-md-9">
        <div class="row">
          <div class="col-sm-12 col-md-12 box-white">
            <h4>Enter Job details</h4>
            <div class="">
              {!! Form::model($job, ['route' => 'employer.job.save', 'method'=>'post', 'id'=>'profile-form']) !!}
                @if($job)
                <input type="hidden" name="job_id" value="{{$job->id}}">
                @endif
                <div class="col-md-6 border-right">
                  <h3> About the Job</h3>
                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('company_id','Company') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                      {!! Form::select('company_id', $companies, old('company_id'),['class'=>'form-control selectpicker', 'data-live-search'=>"true"]) !!}
                      </div>
                  </div>
                   <div class="form-group row">
                    <div class="col-md-4">
                        {!! Form::label('title','Title') !!}<em class="text-danger"> *</em>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('industry_id','Industry') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                      {!! Form::select('industry_id', $industries, old('industry_id'),['class'=>'form-control selectpicker', 'data-live-search'=>"true"]) !!}
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-4">
                          {!! Form::label('job_type','Job type') !!}<em class="text-danger"> *</em>
                      </div>
                      <div class="col-md-8">
                          <div class="form-check">
                              <input type="radio" name="job_type" class="form-check-input" id="job_type_1" value="1" {{$job->job_type==1?'checked':''}} required>
                              {!! Form::label('job_type_1','Regular(In-office/On-field)') !!}
                          </div>
                          <div class="form-check">
                              <input type="radio" name="job_type" class="form-check-input" id="job_type_2" value="2" {{$job->job_type==2?'checked':''}} required>
                              {!! Form::label('job_type_2','Work from home') !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('job_locations','Location') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                      {!! Form::select('job_locations[]', $locations, old('job_locations', $previous_location),['class'=>'form-control selectpicker', 'data-live-search'=>"true", 'multiple', 'required']) !!}
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-4">
                          {!! Form::label('is_part','Part Time allowed') !!}
                      </div>
                      <div class="col-md-8">
                          <div class="form-check">
                              <input type="radio" name="is_part" class="form-check-input" id="is_part_2" value="0" {{$job->is_part==0 ? 'checked' : ''}} required>
                              {!! Form::label('is_part_2','No') !!}
                          </div>

                          <div class="form-check">
                              <input type="radio" name="is_part" class="form-check-input" id="is_part_1" value="1" {{$job->is_part==1 ? 'checked' : ''}} required>
                              {!! Form::label('is_part_1','Yes') !!}
                          </div>
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('vacancy','Vacancy') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::number('vacancy', old('vacancy'),['class'=>'form-control', 'min'=>0, 'required']) !!}
                      </div>
                  </div>

                   <div class="form-group row">
                      <div class="col-md-4">
                          {!! Form::label('is_walkin','Is WalkIn') !!}
                      </div>
                      <div class="col-md-8">
                          <div class="form-check">
                              <input type="checkbox" name="is_walkin" class="form-check-input" id="is_walkin_2" value="1" {{$job->is_walkin==1?'checked':''}}  onchange="this.checked ? $('#autoUpdate').show() : $('#autoUpdate').hide() ">
                              {!! Form::label('is_walkin_2','Yes') !!}
                          </div>

                      </div>
                  </div>

                  <div class="form-group row" id="autoUpdate" style="display:none;">
                      <div class="col-md-4">{!! Form::label('walik_in_dates','Walk In Dates') !!}</div>
                      <div class="col-md-8">
                             <div class="col-md-12">
                              <h5>Start date</h5>
                              {!! Form::date('start_date', old('start_date'),['class'=>'form-control']) !!}
                            </div>
                              <div class="col-md-12">
                              <h5>End date</h5>
                                {!! Form::date('end_date', old('end_date'),['class'=>'form-control']) !!}
                              </div>
                      </div>
                  </div>

                </div>

                <div class="col-md-6">
                       <h3></h3>
                  <div class="form-group row">

                      <div class="col-md-4">{!! Form::label('description','Description') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::textarea('description', old('description'),['class'=>'form-control', 'required','rows'=>3]) !!}
                      </div>
                  </div>
                   <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('salary_min','Salary min p.a.') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::number('salary_min', old('salary_min'),['class'=>'form-control', 'min'=>0, 'onchange'=>"$('#salary_max').val(this.value);$('#salary_max').attr('min', this.value);", 'required']) !!}
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('salary_max','Salary max p.a.') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::number('salary_max', old('salary_max'),['class'=>'form-control', 'min'=>0, 'required']) !!}
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('exp_min','Minimum Experience (yrs.)') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::number('exp_min', old('exp_min'),['class'=>'form-control', 'min'=>0, 'onchange'=>"$('#exp_max').val(this.value);$('#exp_max').attr('min', this.value);", 'required']) !!}
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('exp_max','Maximum Experience (yrs.)') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                          {!! Form::number('exp_max', old('exp_max'),['class'=>'form-control', 'min'=>0, 'required']) !!}
                      </div>
                  </div>
                   <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('qualification','Qualification Required') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                      {!! Form::select('qualification[]', $qualification, old('qualification', $previous_qualification),['class'=>'form-control selectpicker', 'data-live-search'=>"true", 'multiple', 'required']) !!}
                      </div>
                  </div>
                   <div class="form-group row">
                      <div class="col-md-4">{!! Form::label('skills','Skills Required') !!}<em class="text-danger"> *</em></div>
                      <div class="col-md-8">
                      {!! Form::select('skills[]', $skills, old('skills', $previous_skills),['class'=>'form-control selectpicker', 'data-live-search'=>"true", 'multiple',  'required']) !!}
                      </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 text-center col-md-offset-5">
                    <div class="change-button"><button type="submit" class="btn btn-primary" id="profile-btn-submit">Submit</button></div>
                </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
jQuery.validator.addMethod("notEqual", function(value, element, param) {
return this.optional(element) || value != param;
}, "Please select");
$("#profile-form").validate({
    rules: {
      company_id: { notEqual: 0 },
      industry_id: { notEqual: 0 },
      job_locations: { notEqual: 0 },
      vacancy: { notEqual: 0 },
      salary_min: { notEqual: 0 },
      salary_max: { notEqual: 0 },
      exp_max: { notEqual: 0 },
    },
    submitHandler : function(form){
        $('#profile-btn-submit').addClass('disable').attr('disabled', 'disabled');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                toastr.success('Successfully post');
                setTimeout(function(){
                    window.location = '{{route('employer.job.list')}}'
                }, 3000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                // console.log(errors);
                $('#profile-btn-submit').removeClass('disable').removeAttr('disabled');
            });
             return false;
        }
});
</script>

<script>
$(document).ready(function(){
 $('.selectpicker').selectpicker();
});
</script>
@endpush