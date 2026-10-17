@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th> <input type="checkbox" name="" value=""> </th>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a class="text-primary" href="{{route('admin.'.$url_key.'.edit', [$row->id])}}">
      {{$row->name}}
    </a>
  </td>
  <td>{{$row->code}}</td>
  <td>{{$row->fixed_perc?'Percentage':'Fixed'}}</td>
  <td>{{couponApplyOn($row->apply_on)}}</td>
  @if($row->one_time)
  <td>One Time</td>
  @elseif($row->only_one_time)
  <td>Only One Time</td>
  @else
  <td>Unlimited</td>
  @endif
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      <a href="{{route('admin.'.$url_key.'.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$url_key.'.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
