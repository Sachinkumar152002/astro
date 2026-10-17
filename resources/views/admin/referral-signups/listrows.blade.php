@if($results->count())
  @foreach($results as $index => $row)
    @php
      $refType = 'N/A';
      $refName = 'N/A';
      $refLink = null;
      if(!empty($row->ref_user_id)) {
        $refType = 'User';
        $refName = $row->ref_user_name ?: 'N/A';
        $refLink = route('admin.customer.edit', [$row->ref_user_id]);
      } elseif(!empty($row->ref_astrologer_id)) {
        $refType = 'Astrologer';
        $refName = $row->ref_astrologer_name ?: 'N/A';
        $refLink = route('admin.Astrologer.edit', [$row->ref_astrologer_id]);
      }
    @endphp
    <tr>
      <td scope="row">{{ $index + 1 + (($results->currentPage() - 1) * $results->perPage()) }}</td>
      <td>
        <strong>{{ $row->name ?: 'N/A' }}</strong>
        <div class="small text-muted">ID: {{ $row->id }}</div>
      </td>
      <td>{{ $row->email ?: '—' }}</td>
      <td>
        @if(!empty($row->phone))
          +{{ $row->phone_code ?: '91' }} {{ $row->phone }}
        @else
          —
        @endif
      </td>
      <td><span class="badge bg-info text-dark">{{ $row->used_referral_code }}</span></td>
      <td>
        @if($refType === 'Astrologer')
          <span class="badge bg-warning text-dark">Astrologer</span>
        @elseif($refType === 'User')
          <span class="badge bg-primary">User</span>
        @else
          <span class="badge bg-secondary">N/A</span>
        @endif
      </td>
      <td>
        <div class="small">
          @if($refLink && $refName !== 'N/A')
            <a href="{{ $refLink }}">{{ $refName }}</a>
          @else
            {{ $refName }}
          @endif
        </div>
      </td>
      <td>
        {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') : '—' }}
      </td>
      <td class="text-right">
        <a href="{{ route('admin.customer.edit', [$row->id]) }}" class="btn btn-sm btn-info" title="View Customer">
          <i class="bi bi-eye"></i>
        </a>
      </td>
    </tr>
  @endforeach
@else
  <tr>
    <td class="text-center" colspan="9"><p>No Records</p></td>
  </tr>
@endif
