
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
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
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>

</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">

<div class="row">
    <div class="form-group col-md-3">
      {!! Form::label('name','Name', ['class'=>'required']) !!}
      {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('amount','Amount', ['class'=>'required']) !!}
      {!! Form::number('amount', old('amount'),['class'=>'form-control', 'required'=>'required', 'min'=>'0']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('talk_amount','Talk amount', ['class'=>'']) !!}
      {!! Form::number('talk_amount', old('talk_amount'),['class'=>'form-control', 'min'=>'0']) !!}
    </div>
    {{--
    <div class="form-group col-md-3">
      {!! Form::label('loyality_points','Loyality Points') !!}
      {!! Form::number('loyality_points', old('loyality_points'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('special_amount','Special Amount') !!}
      {!! Form::number('special_amount', old('special_amount'),['class'=>'form-control']) !!}
    </div>
    --}}
    <div class="form-group col-md-3">
      {!! Form::label('from_date','Talk amount from date') !!}
      {!! Form::date('from_date', old('from_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('to_date','Talk amount to date') !!}
      {!! Form::date('to_date', old('to_date'),['class'=>'form-control', 'max'=> '9999-12-31']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('status', 'Status', ['class'=>'required']) !!}
      {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control nosearch']) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('one_time', 'Is One time', ['class'=>'required']) !!}
      {!! Form::select('one_time',yesNoArray(), old('one_time'), ['class'=>'form-control nosearch']) !!}
    </div>
  </div>
</div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
