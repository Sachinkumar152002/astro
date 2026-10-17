@extends('layouts.admin')


@section('content')
{!! Form::model($data, ['route' => 'admin.hsn.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($data)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $moduleName }} Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-4">
        <div class="form-group">
            {!! Form::label('name','Name', ['class'=>'required']) !!}
            {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-4">
        <div class="form-group">
            {!! Form::label('hsn_code','HSN Code', ['class'=>'required']) !!}
            {!! Form::text('hsn_code', old('hsn_code'),['class'=>'form-control', 'maxlength'=>'8']) !!}
        </div>
      </div>
        <div class="col-4">
          <div class="form-group">
              {!! Form::label('gst_percentage','GST Percentage', ['class'=>'required']) !!}
              {!! Form::text('gst_percentage', old('gst_percentage'),['class'=>'form-control']) !!}
          </div>
        </div>
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('short_description', 'Short Description', ['class'=>'required']) !!}
          {!! Form::textarea('short_description', old('short_description'), ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('description', 'Description', ['class'=>'required']) !!}
          {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
        </div>
      </div>
    </div>
  </div>
</div>


  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
  <script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
