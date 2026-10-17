@if($advertisements->count())
@foreach($advertisements as $index=>$advertisement)
<tr>
  <!-- <td> <input type="checkbox" name="" value=""> </td> -->
  <td scope="row">{{$index+1+(($advertisements->currentPage()-1)*$advertisements->perPage())}}</td>
  <td><img src="{{$advertisement->image?$advertisement->image->image(100,200):no_image()}}"></td>
  <td>
    @if(adminCan('advertisement', 'update'))
      <a href="{{route('admin.advertisement.edit', [$advertisement->id])}}">
      {{$advertisement->title}}
      </a>
    @else
      {{$advertisement->title}}
    @endif
  </td>
  <td>{{$advertisement->page}}</td>
  <td>{{$advertisement->display_on}}</td>
  <td>{!! statusBadge($advertisement->status) !!}</td>
  <td>{{$advertisement->order}}</td>
  <td>{{$advertisement->start_date?date('d/m/Y', strtotime($advertisement->start_date)):'-'}}</td>
  <td>{{$advertisement->end_date?date('d/m/Y', strtotime($advertisement->end_date)):'-'}}</td>
  <td>{{ISTDatetimeFormat($advertisement->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan('advertisement', 'update'))
      <a href="{{route('admin.advertisement.edit', [$advertisement->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('advertisement', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.advertisement.copy', [$advertisement->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('advertisement', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$advertisement->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
