
@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <div class="form-group">
        {!! Form::label('main_content', 'Main') !!}
        {!! Form::textarea('main_content', old('main_content'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('love_content', 'Love') !!}
        {!! Form::textarea('love_content', old('love_content'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('career_content', 'Career') !!}
        {!! Form::textarea('career_content', old('career_content'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('finance_content', 'Finance') !!}
        {!! Form::textarea('finance_content', old('finance_content'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('health_content', 'Health') !!}
        {!! Form::textarea('health_content', old('health_content'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
  </div>
</div>
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
