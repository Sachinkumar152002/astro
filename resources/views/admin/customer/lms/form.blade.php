
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.customer.course.submit', $user_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>


  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>
<div class="clearfix"></div>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              {!! Form::label('course_id', 'Course', ['class'=>'required']) !!}
              {!! Form::select('course_id',$courses, old('course_id'), ['class'=>'form-control']) !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('expire_date', 'Expiry Date', ['class'=>'required']) !!}
            {!! Form::date('expire_date', old('expire_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
          </div>
      </div>
      <div class="col-md-1">
          <div class="form-group mt-4">
            <span class="ml-2"><strong>Status</strong></span>
              <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
              <label class="mx-2 t-lable" for="status"></label>
          </div>
      </div>
      @if($item && $course->type!=1)
        @if($course->modules->count()==$course->passedModules($item->user_id, $item->user_type)->count())
          <div class="col-md-1">
              <div class="form-group mt-4">
                <span class="ml-2"><strong>Passed</strong></span>
                  <input type="checkbox" disabled class="toggle-check" id="passcourse" checked value="1" name="passcourse">
                  <label class="mx-2 t-lable" for="passcourse"></label>
              </div>
          </div>
          <div class="col-md-1 form-group my-auto pt-3">
            <a target="_blank" href="{{route('admin.customer.course.certificate.download', [$item->user_id, $item->id])}}" class="text-white btn-primary p-2 rounded mt-3"><i class="bi bi-eye" data-bs-toggle="tooltip" title="View Certificate" style="font-size:16px"></i></a>
          </div>
        @else
          <div class="col-md-3">
              <a target="_blank" href="{{route('admin.customer.course.pass', [$item->user_id, $item->id])}}" class="btn btn-primary text-white">Pass Course</a>
          </div>
        @endif
      @endif
    </div>
  </div>
</div>
<div class="mb-3">
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
