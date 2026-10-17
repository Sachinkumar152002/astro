@if($results->count())
@foreach($results as $index=>$row)
  @php
    $user = $row->user;
    $astrologer = $row->astrologer;
  @endphp
  <tr>
    <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
    <td>
      @if($astrologer)
          <a href="{{ route('admin.Astrologer.edit', [$astrologer->id]) }}" target="_blank">
            {{ $astrologer->display_name }}
          </a>
      @else
        {{ "Astrologer" }}
      @endif
    </td>
    <td>
      @if($user)
          <a href="{{route('admin.customer.edit', [$user->id])}}" target="_blank">
            {{ $user->name }}
          </a>
      @else
        {{ "User" }}
      @endif
    </td>
    <td>₹ {{ $row->per_min_charge}}</td>
    @if($row->free)
     @php
       $totalPrice = $row->charges - $row->discount;
     @endphp
     <td>₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</td>
    @else
      <td>₹ {{ $row->charges}}</td>
    @endif
    <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
    <td class="text-right">
      @if(adminCan(strtolower($userType).'-chat-consultation', 'update'))
      <a href="{{route('admin.aiconsultation.chat.view', [$userType, $row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
    </td>
  </tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
