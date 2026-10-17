@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image}}" class="img-view">
        <img src="{{$row->image}}" width="75" height="75" alt="{{$row->title}}" class="img-fluid">
    </a>
  </td>
  <td>
      @if(adminCan('video-testimonial', 'update'))
      <a href="{{route('admin.videotestimonial.edit', [$row->id])}}" class="text-primary">
        {{$row->title}}
      </a>
      @else
      {{$row->title}}
      @endif
  </td>
  <td>
    @php
      $pages = [];
      foreach($row->pages as $key => $page){
        array_push($pages, videoTstimonialPage($page->page));
      }
      //print_r($pages);
    @endphp
    {{ implode(', ', $pages) }}
  </td>
  <td>{{$row->city}}</td>
  <td>{{$row->order}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      @if(adminCan('video-testimonial', 'update'))
      <a href="{{route('admin.videotestimonial.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('video-testimonial', 'delete'))
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
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
