`@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">Reply Details</h1>


{!! Form::model($replies, ['route' => ['admin.support.replies.submit', $support->id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($replies)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Supoort Reply</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-12">
        <div class="form-group">
            {!! Form::label('subject','Subject', ['class'=>'required']) !!}
            {!! Form::text('subject', $support->subject ,['class'=>'form-control', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
            {!! Form::label('message','Message', ['class'=>'required']) !!}
            {!! Form::textarea('message', $support->message ,['class'=>'form-control', 'disabled'=>'disabled']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('reply', 'Reply', ['class'=>'required']) !!}
          {!! Form::textarea('reply', old('reply'), ['class' => 'form-control']) !!}
        </div>
      </div>
    </div>
  </div>
</div>


  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>

@endpush
`
