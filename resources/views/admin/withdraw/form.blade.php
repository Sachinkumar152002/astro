
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}

<div class="d-flex justify-content-between">
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  <div class="">
  @if($item && ($item->status == 'done' || $item->status == 'cancel' ) )
    <div class=""></div>
  @else
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  @endif
  </div>
</div>

@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">

<div class="row">
    <div class="form-group col-md-3">
      {!! Form::label('name','Name', ['class'=>'required']) !!}
      {!! Form::text('name', $astrologer ? $astrologer->display_name : 'Astrologer' ,['class'=>'form-control', 'readonly'=>true]) !!}
      {!! Form::hidden('user_id', $astrologer ? $astrologer->id : 0) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('phone','Phone', ['class'=>'']) !!}
      {!! Form::text('name', $astrologer ? $astrologer->phone : '' ,['class'=>'form-control', 'readonly'=>true]) !!}
    </div>
    <div class="form-group col-md-3">
      {!! Form::label('wallet','Wallet (excluded)', ['class'=>'']) !!}
      {!! Form::number('wallet', $totalAmount ,['class'=>'form-control', 'readonly'=>true]) !!}
    </div>
    {{--
    <div class="form-group col-md-4">
      {!! Form::label('withdrawedAmount','Withdrawal Amount', ['class'=>'']) !!}
      {!! Form::number('withdrawedAmount', $withdrawedAmount ,['class'=>'form-control', 'readonly'=>true]) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('availableAmount','Available Amount', ['class'=>'']) !!}
      {!! Form::number('availableAmount', $availableAmount, ['class'=>'form-control', 'readonly'=>true]) !!}
    </div>
    --}}
    <div class="form-group col-md-3">
      {!! Form::label('amount','Requested Amount', ['class'=>'required']) !!}
      {!! Form::number('amount', old('amount'),['class'=>'form-control', 'readonly'=> ($item ? true : false), 'min' => '1', 'max' => $availableAmount]) !!}
    </div>
    <div class="form-group col-12">
      {!! Form::label('description', 'Description', ['class'=>'required']) !!}
      {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'id'=>'description']) !!}
    </div>
    @if($item && ($item->status == 'done' || $item->status == 'cancel'))
      <div class="form-group col-md-4">
        {!! Form::label('status', 'Status', ['class'=>'required']) !!}
        <select class="time_select nosearch form-control" name="status">
          <option value="pending" selected >{{ ucfirst($item->status) }}</option>
        </select>
      </div>
    @else
    <div class="form-group col-md-4">
      {!! Form::label('status', 'Status', ['class'=>'required']) !!}
      <select class="time_select nosearch form-control" name="status">
        <option value="">Select</option>
        <option value="pending" {{$item && $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="done" {{$item && $item->status == 'done' ? 'selected' : '' }}>Done</option>
        <option value="cancel" {{$item && $item->status == 'cancel' ? 'selected' : '' }}>Cancel</option>
      </select>
    </div>
    @endif
  </div>
</div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
