
@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">New Article</h1>
<div class="clearfix"></div>
{!! Form::model(false, ['route' => ['admin.article.save',  $lang ], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}

<div class="clearfix"></div>
  <div class="form-group">
      {!! Form::label('title','Title') !!}
      {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group">
    {!! Form::label('type', 'Type') !!}
    {!! Form::select('type',articleTypes(), old('type'), ['class'=>'form-control', 'required']) !!}
  </div>
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
