
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
    <div class="form-group col-md-6">
      {!! Form::label('name','Name', ['class'=>'required']) !!}
      {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('ratio','Astrologer Ratio', ['class'=>'required']) !!}
      {!! Form::number('ratio', old('ratio'),['class'=>'form-control', 'required'=>'required', 'min'=>'0']) !!}
    </div>
    <div class="form-group col-6">
      {!! Form::label('commission_settlement_period', 'Commission Settlement Time', ['class'=>'required']) !!}
      <select class="form-control nosearch" id="commission_settlement_period" name="commission_settlement_period">
        @foreach(astrologerCommisionSettlementInDays() as $key=>$value)
          <option value="{{ $key }}" {{$item && $item->commission_settlement_period==$key?"selected":''}}>{{ $value }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
