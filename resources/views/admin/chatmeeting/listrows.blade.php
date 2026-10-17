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
    <td>{{ $row->scheduled_for?'Later':'Now'}}</td>
    <td>{{ $row->timeslot}} Min</td>
    @if($row->free)
     @php
       $totalPrice = $row->total_price - $row->discount;
     @endphp
     <td>₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</td>
    @else
     <td>₹ {{ $row->total_price}}</td>
    @endif
    <td>{{$row->reschedule?'rescheduled':$row->status}}</td>
    {{--<td>
      <div>
        @if(count(consultationRequests($row->id, 'cancel', 'astrologer')))
          @if(strtolower($userType)=='astrologer')
          A:
          @else
          I:
          @endif
          @foreach(consultationRequests($row->id, 'cancel', 'astrologer') as $key => $item)
            @if($item->status == 1)
            <i class="far fa-window-close text-success"></i>
            @elseif($item->status == 2)
            <i class="far fa-window-close text-danger"></i>
            @else
            <i class="far fa-window-close"></i>
            @endif
          @endforeach
        @endif
      </div>
        <div>
          @if(count(consultationRequests($row->id, 'cancel', 'user')))
          U:
            @foreach(consultationRequests($row->id, 'cancel', 'user') as $key => $item)
              @if($item->status == 1)
              <i class="far fa-window-close text-success"></i>
              @elseif($item->status == 2)
              <i class="far fa-window-close text-danger"></i>
              @else
              <i class="far fa-window-close"></i>
              @endif
            @endforeach
          @endif
        </div>
    </td>
    <td>
      <div>
        @if(count(consultationRequests($row->id, 'reschedule', 'astrologer')))
        A:
          @foreach(consultationRequests($row->id, 'reschedule', 'astrologer') as $key => $item)
            @if($item->status == 1)
            <i class="far fa-window-close text-success"></i>
            @elseif($item->status == 2)
            <i class="far fa-window-close text-danger"></i>
            @else
            <i class="far fa-window-close"></i>
            @endif
          @endforeach
        @endif
      </div>
        <div>
          @if(count(consultationRequests($row->id, 'reschedule', 'user')))
          U:
            @foreach(consultationRequests($row->id, 'reschedule', 'user') as $key => $item)
              @if($item->status == 1)
              <i class="far fa-window-close text-success"></i>
              @elseif($item->status == 2)
              <i class="far fa-window-close text-danger"></i>
              @else
              <i class="far fa-window-close"></i>
              @endif
            @endforeach
          @endif
        </div>
    </td>--}}
    <td>{{$row->has_illegal_activity ? "Yes" : "No"}}</td>
    <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
    <td class="text-right">
      @if(adminCan(strtolower($userType).'-chat-consultation', 'update'))
      <a href="{{route('admin.consultation.chat.view', [$userType, $row->id])}}" class="text-success">
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
