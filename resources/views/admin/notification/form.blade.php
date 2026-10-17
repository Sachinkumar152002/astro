
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6 d-flex align-items-center">
    @if($user_id || $astrologer_id)
      <a href="{{route('admin.'.$nav.'.index', [0, 0])}}" class="mx-4 text-white btn btn-primary fw-bold">All</a>
    @endif
    <h1 class="text-primary float-left mb-0">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <div class="row">
        @if($user)
          <div class="form-group col-6">
            {!! Form::label('user','User', ['class'=>'required']) !!}
            {!! Form::text('user', $user->name,['class'=>'form-control', 'readonly'=>'readonly']) !!}
            <input type="hidden" name="user_id" value="{{$user_id}}">
          </div>
        @endif
        @if($astrologer)
          <div class="form-group col-6">
            {!! Form::label('astrologer','Astrologer', ['class'=>'required']) !!}
            {!! Form::text('astrologer', $astrologer->display_name, ['class'=>'form-control', 'readonly'=>'readonly']) !!}
            <input type="hidden" name="astrologer_id" value="{{$astrologer_id}}">
          </div>
        @endif
        <div class="form-group col-6">
          {!! Form::label('message','Message', ['class'=>'required']) !!}
          <textarea class="form-control" name="message" id="message" rows="2" cols="40">{{$item?$item->message:''}}</textarea>
        </div>
        <div class="form-group col-6">
          {!! Form::label('link','Link', ['class'=>'']) !!}
          {!! Form::text('link', old('link'), ['class'=>'form-control']) !!}
        </div>

        @php
          $for = '';
          if($item){
            $for = $item->for;
          }else{
            if($user_id){
              $for = 1;
            }elseif($astrologer_id){
              $for = 2;
            }
          }

        @endphp

        <div class="form-group col-6 {{$user_id || $astrologer_id ? 'd-none' : ''}}">
          {!! Form::label('for', 'For', ['class'=>'required']) !!}
          <select class="form-control nosearch" id="for" name="for">
            <option value="">Select</option>
            <option value="0" {{$for==0 ? "selected" : ""}}>All</option>
            <option value="1" {{$for==1 ? "selected" : ''}}>User</option>
            <option value="2" {{$for==2 ? "selected" : ''}}>Astrologer</option>
          </select>
        </div>
        {{--
        @if($item)
          <div class="form-group col-6">
            {!! Form::label('view', 'Viewed', ['class'=>'required']) !!}
            <select class="form-control nosearch" id="view" name="view">
              <option value="0" {{$item && $item->view == 0 ? "selected" : ''}}>No</option>
              <option value="1" {{$item && $item->view == 1 ? "selected" : ''}}>Yes</option>
            </select>
          </div>
        @endif
        --}}
        @if($user_id==0 && $astrologer_id==0)
          <div class="form-group col-6">
            {!! Form::label('on', 'On', ['class'=>'required']) !!}
            <select class="form-control nosearch" id="on" name="on">
              @foreach(notificationOn() as $key=>$value)
                <option value="{{ $key }}" {{$item && $item->on==$key?"selected":''}}>{{ $value }}</option>
              @endforeach
            </select>
          </div>
        @endif

        <div class="form-group col-md-2">
          <span class="ml-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable" for="status"></label>
        </div>
      </div>
    </div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
