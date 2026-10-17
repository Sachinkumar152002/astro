@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('faq', 'update'))
    <a class="text-primary" href="{{route('admin.faq.edit', [$row->id])}}">
    {{$row->question}}
    </a>
    @else
    {{$row->question}}
    @endif
  </td>
  <td>
    @php
    $pages = [];
    foreach($row->pages as $key => $page){
      array_push($pages, faqTypes($page->page));
    }
    @endphp
    {{implode(', ', $pages)}}
  </td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->order}}</td>
  <td>{{$row->updated_at != $row->created_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
      @if(adminCan('faq', 'update'))
      <a href="{{route('admin.faq.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('faq', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.faq.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('faq', 'delete'))
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
  <td class="text-center" colspan="6"><p>No Records</p></td>
</tr>
@endif
