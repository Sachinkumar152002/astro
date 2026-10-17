@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th> <input type="checkbox" name="" value=""> </th>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('landing-data', 'update'))
    <a class="text-primary" href="">
      {{$row->fullName}}
    </a>
    @else
    {{$row->fullName}}
    @endif
  </td>
  <td>{{$row->email}}</td>
  <td>{{$row->mobile}}</td>
  <td>{{$row->updated_at}}</td>
  @php
    if (trim($row->module) == 'Rudraksha Consultation') {
        $routeModul = 'rudraksh-landing-page';
    } elseif(trim($row->module) == 'Gemstones Consultation'){
      $routeModul = 'gemstone-landing-page';
    }else {
        $routeModul = 'rudraksh-landing-page';
    }
@endphp

  <td>
    @if(adminCan($nav, 'update'))
      <a href="{{route('admin.landing.page.edit', [$row->module, $row->id])}}" class="text-success">
        <i class="fas fa-eye"></i>
      </a>
      @endif
      @if(adminCan($nav, 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
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
