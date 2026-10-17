@if($results->count())
@foreach($results as $index=>$suggestion)
@php
  $astrologer = \App\Model\Astrologer::find($suggestion->astrologer_id);
  $user = \App\User::find($suggestion->user_id);
  $astrologerName = $astrologer ? ($astrologer->display_name ?? $astrologer->firstname ?? 'Astrologer #' . $suggestion->astrologer_id) : 'Astrologer #' . $suggestion->astrologer_id;
  $userName = $user ? ($user->name ?? $user->firstname ?? 'User #' . $suggestion->user_id) : 'User #' . $suggestion->user_id;
@endphp
<tr>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>{{ $astrologerName }}</td>
  <td>{{ $userName }}</td>
  <td>{{ $suggestion->referral_code ?? '-' }}</td>
  <td>{{ date('d M Y, h:i A', strtotime($suggestion->created_at)) }}</td>
  <td class="text-right">
    <a href="{{route('admin.suggested_remedies.show', [$suggestion->id])}}" class="text-primary" title="View Details">
      <i class="bi bi-eye"></i>
    </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="6"><p>No Records</p></td>
</tr>
@endif
