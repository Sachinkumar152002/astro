@extends('layouts.admin')

@section('content')
{!! Form::model($category, ['route' => ['admin.category.submit',$lang], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($category)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Category Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name','Name', ['class'=>'required']) !!}
            {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required', 'id'=>'name']) !!}
          </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
              {!! Form::label('parent_id','Parent') !!}
              {!! Form::select('parent_id',$categories,  old('parent_id'),['class'=>'form-control']) !!}
            </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('is_featured', 'Featured', ['class'=>'required']) !!}
            {!! Form::select('is_featured',yesNoArray(), old('is_featured'), ['class'=>'form-control', 'required']) !!}
            </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
           {!! Form::label('show_in_navigation', 'Show in navigation', ['class'=>'required']) !!}
           {!! Form::select('show_in_navigation',yesNoArray(), old('show_in_navigation'), ['class'=>'form-control', 'required']) !!}
       </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('status', 'Status', ['class'=>'required']) !!}
          {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control', 'required']) !!}
        </div>
      </div>
    </div>
    </div>
    <div id="seo_tab" class="tab-pane fade">
      <div class="row">
        <div class="form-group col-md-6">
        {!! Form::label('slug','Slug', ['class'=>'required']) !!}
        {!! Form::text('slug', old('slug'),['class'=>'form-control', 'required'=>'required', 'id'=>'slug']) !!}
      </div>
      <div class="form-group col-md-6">
          {!! Form::label('meta_title','Meta Title') !!}
          {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
      </div>
      </div>
      <div class="form-group">
          {!! Form::label('meta_keywords','Meta Keywords') !!}
          {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
      </div>
      <div class="form-group">
          {!! Form::label('meta_description','Meta Description') !!}
          {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
      </div>
    </div>
  </div>

{{-- <div class="form-group">
  {!! Form::label('form_type', 'From Type'); !!}
  {!! Form::select('form_type',formTypes(), old('form_type'), ['class'=>'form-control', 'required']) !!}
</div> --}}
{!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
@if(!$category)
  $('#name').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif
</script>
@endpush
