@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row"> <input type="checkbox" name="" value=""> </th>
  <th>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>{{$row->module}}</td>
  <td>
    @if(adminCan('contact-enquiry', 'update'))

    {{$row->fullName}}
    @else
    {{$row->fullName}}
    @endif
  </td>
  <td>{{$row->email}}</td>
  <td>{{$row->mobile}}</td>
 <!--  <td>{{ \Carbon\Carbon::parse($row->dob)->format('d F Y') }}</td>
  <td>{{ \Carbon\Carbon::parse($row->birthTime)->format('h:i A') }}</td>
  <td>{{$row->birthPlace}}</td> -->
  <td>{{$row->amount}}</td>
  <td>
    @if($row->payment_status == 0)
        Pending
    @elseif($row->payment_status == 1)
        Success
    @elseif($row->payment_status == 2)
        Failed
    @else
        Unknown
    @endif
</td>

  <td>{{$row->message}}</td>
  <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d F Y, h:i A') }}</td>
  <td class="text-right">
      {{-- @if(adminCan('contact-enquiry', 'update'))
      <a href="{{route('admin.enquiry.view', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('contact-enquiry', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif --}}
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
