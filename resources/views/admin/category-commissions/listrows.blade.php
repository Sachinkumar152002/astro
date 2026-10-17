@if($results->count())
@foreach($results as $index=>$commission)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if($commission->category)
      {{$commission->category->name ?? 'N/A'}}
    @else
      <span class="text-muted">N/A</span>
    @endif
  </td>
  <td>
    @if($commission->commission_mode == 'PERCENTAGE')
      {{number_format($commission->commission_value, 2)}}%
    @else
      ₹{{number_format($commission->commission_value, 2)}}
    @endif
  </td>
  <td>
    @if($commission->max_cap)
      ₹{{number_format($commission->max_cap, 2)}}
    @else
      <span class="text-muted">No Limit</span>
    @endif
  </td>
  <td>
    @if($commission->status == 1)
      <span class="badge bg-success">Active</span>
    @else
      <span class="badge bg-danger">Inactive</span>
    @endif
  </td>
  <td>{{$commission->updated_at ? $commission->updated_at->format('d-m-Y H:i') : $commission->created_at->format('d-m-Y H:i')}}</td>
  <td class="text-right">
    @if(adminCan('category_commissions', 'update'))
      <a href="{{route('admin.category_commissions.edit', [$commission->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('category_commissions', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategoryCommission({{$commission->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
