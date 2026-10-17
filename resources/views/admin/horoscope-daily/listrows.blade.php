@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>

  <td>{{$row->sign->name}}</td>
  <td>{{$row->date}}</td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
      <a href="{{route('admin.horoscope-daily.edit', [$row->id])}}" class="text-success">
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