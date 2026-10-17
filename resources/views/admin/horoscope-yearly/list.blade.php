@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-lg-12">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th class="sortable " data-sort="zodiac_sign_id">Zodiac Sign <i class="icon"></i></th>
          <th class="sortable " data-sort="Year">Year <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>
        <tr id="filter_row">
          <th></th>
          <th>
            {!! Form::select('zodiac_sign_id',$zodiacSigns, old('zodiac_sign_id'), ['class'=>'form-control']) !!}
           </th>
          <th><input type="text" class="form-control" name="year"></th>
          <th><input type="date" class="form-control" name="updated_at"></th>
          <th>
            <button class="btn btn-success" onclick="listTable()">Filter</button>
          </th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="5" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
