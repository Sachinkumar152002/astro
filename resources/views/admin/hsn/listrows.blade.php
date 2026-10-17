@if($results->count())
@foreach($results as $key=>$value)
<tr>
  <th scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('hsn', 'update'))
      <a href="{{route('admin.hsn.edit', [$value->id])}}" class="text-primary">
        {{$value->name}}
      </a>
      @else
      {{$value->name}}
      @endif
  </td>
  <td>{{$value->hsn_code}}</td>
  <td>{{$value->gst_percentage}}%</td>
  <td>{{ISTDatetimeFormat($value->updated_at)}}</td>
  <td class="text-right text-nowrap">
    @if(adminCan('hsn', 'update'))
      <a href="{{route('admin.hsn.edit', [$value->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('hsn', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.hsn.copy', [$value->id])}}')" class="text-seconday">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('hsn', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$value->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
