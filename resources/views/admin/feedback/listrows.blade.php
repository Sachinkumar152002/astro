@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <td scope="row"> <input type="checkbox" name="" value=""> </td>
  <td>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('feedback', 'update'))
    <a href="{{route('admin.'.$nav.'.view', [$row->id])}}" class="text-primary">
      {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
  <td>{{$row->email}}</td>
  <td>{{$row->phone}}</td>
  <td>{{$row->created_at}}</td>
  <td class="text-right">
    @if(adminCan('feedback', 'update'))
      <a href="{{route('admin.'.$nav.'.view', [$row->id])}}" class="text-primary">
        <i class="fa fa-eye"></i>
      </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif
