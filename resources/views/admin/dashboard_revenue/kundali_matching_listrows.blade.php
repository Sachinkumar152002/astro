@if($rows->count())
@foreach($rows as $key => $row)
<tr>
  <td>{{ $key + 1 + (($rows->currentPage() - 1) * $rows->perPage()) }}</td>
  <td>{{ $row->boy_name ?: '-' }}</td>
  <td>{{ $row->girl_name ?: '-' }}</td>
  <td>{{ $row->boy_birth_date ?: '-' }}</td>
  <td>{{ $row->girl_birth_date ?: '-' }}</td>
  <td>{{ $row->lang ?: '-' }}</td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="6"><p>No Records</p></td>
</tr>
@endif
