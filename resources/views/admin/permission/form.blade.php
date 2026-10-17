
@extends('layouts.admin')
@section('content')
{!! Form::model($permission, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($permission)
{{ Form::hidden('id', old('id')) }}
@endif
<style>
.toggle-check{ float:right;}
</style>
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Details</h1>
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
      <div class="form-group col-md-6">
        <label for="permission_role_id" class=" control-label">Role</label>
        <select class="form-control nosearch" name="role_id" id="permission_role_id">
          @foreach($roles as $key => $value)
            <option value="{{$key}}" {{$role_id==$key?'selected':'' }}>{{$value}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="row bg-primary radius-8 text-white p-3">

      <div class="col-1">

      </div>
      <div class="col-5">
        <span class="ml-2"><strong>Module</strong></span>

      </div>

      <div class="col-2">
        <span class="ml-2"><strong>Add</strong></span>

      </div>
      <div class="col-2">
        <span class="ml-2"><strong>Update</strong></span>

      </div>
      <div class="col-2">
        <span class="ml-2"><strong>Delete</strong></span>

      </div>
    </div>
      @foreach($modules as $key => $value)
      @php
      $item=null;
        if(in_array($value->id, $all)){
          $item=$permission->permission($permission->id, $value->id);
        }
      @endphp
      <div class="row my-3" style="border-bottom:1px dashed #000">
        <div class="col-1">
          <input onclick="handleModule(this)"type="checkbox" {{$item ?'checked':''}} class="toggle-check" id="module{{$key}}" {{$item ?'checked':''}} value="{{$value->id}}" name="permissions[module_id][]">
          <label class="mx-2 t-lable" for="module{{$key}}"></label>
        </div>
        <div class="col-5">
          <span class="ml-2"><strong>{{$value->name}}</strong></span>

        </div>

        {{--<div class="col-2">

            <input type="checkbox" {{$item ? '' : 'disabled'}} {{$item && $item->can_view ? 'checked' : ''}} class="toggle-check can" id="view{{$key}}" value="1" name="permissions[can_view][{{$value->id}}]">
            <label class="mx-2 t-lable" for="view{{$key}}"></label>
        </div>--}}
        <div class="col-2">

            <input type="checkbox" {{$item ? '' : 'disabled'}} {{$item && $item->can_add ? 'checked' : ''}} class="toggle-check can" id="add{{$key}}" value="1" name="permissions[can_add][{{$value->id}}]">
            <label class="mx-2 t-lable" for="add{{$key}}"></label>
        </div>
        <div class="col-2">

            <input type="checkbox" {{$item ? '' : 'disabled'}} {{$item && $item->can_update ? 'checked' : ''}} class="toggle-check can" id="update{{$key}}" value="1" name="permissions[can_update][{{$value->id}}]">
            <label class="mx-2 t-lable" for="update{{$key}}"></label>
        </div>
        <div class="col-2">

            <input type="checkbox" {{$item ? '' : 'disabled'}} {{$item && $item->can_delete ? 'checked' : ''}} class="toggle-check can" id="delete{{$key}}" value="1" name="permissions[can_delete][{{$value->id}}]">
            <label class="mx-2 t-lable" for="delete{{$key}}"></label>
        </div>

      </div>
      @endforeach
</div>
</div>
  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  function handleModule(that){
    // $(that).parent().parent().parent().find('.can').prop('disabled', true).prop('checked', false);
    $(that).parent().parent().find('.can').prop('disabled', true).prop('checked', false);
    if($(that).prop('checked')){
      // $(that).parent().parent().parent().find('.can').prop('disabled', false).prop('checked', true);
      $(that).parent().parent().find('.can').prop('disabled', false).prop('checked', true);
    }
  }
</script>
@endpush
