@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>{{$row->from_number}}</td>
  <td>{{$row->to_number}}</td>
  <td>{{$row->created_at}}</td>
  <td class="text-right">
      <a href="{{route('admin.callhistory.view', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif
