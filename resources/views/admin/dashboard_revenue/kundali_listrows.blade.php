@if($rows->count())
@foreach($rows as $key => $row)
<tr>
  <td>{{ $key + 1 + (($rows->currentPage() - 1) * $rows->perPage()) }}</td>
  <td>{{ $row->name ?: '-' }}</td>
  <td>{{ $row->gender ?: '-' }}</td>
  <td>{{ $row->date ?: '-' }}</td>
  <td>{{ $row->time ?: '-' }}</td>
  <td>{{ $row->city ?: '-' }}</td>
  <td>{{ $row->lang ?: '-' }}</td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
