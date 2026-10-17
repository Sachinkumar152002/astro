@if($articles->count())
@foreach($articles as $index=>$article)
@if($article->type == 0)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($articles->currentPage()-1)*$articles->perPage())}}</td>
  {{--<td>
    @if($article->image_id && $article->image)
    <a href="javascript:void(0)" data-img="{{$article->image->image(400,400)}}" class="img-view">
        <img src="{{$article->image->image(100,100)}}" alt="{{$article->title}}" class="img-fluid">
    </a>
    @endif
  </td>--}}}
  <td>
    @if(adminCan('text-testimonial', 'update'))
    <a href="{{route('admin.testimonial.edit', [$article->id])}}">
    {{$article->title}}
    </a>
    @else
    @endif
  </td>
  <td>
    {!! statusBadge($article->status) !!}
  </td>
  <td>
    @php
      $pages = [];
      foreach($article->pages as $key => $page){
        array_push($pages, videoTstimonialPage($page->page));
      }
    @endphp
    @if(count($pages))
    {{implode(', ', $pages)}}
    @endif
  </td>
  <td>{{$article->created_at!=$article->updated_at ? $article->updated_at : $article->created_at}}</td>
  <td class="text-right">
    @if(adminCan('text-testimonial', 'update'))
      <a href="{{route('admin.testimonial.edit', [$article->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('text-testimonial', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.testimonial.copy', [$article->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('text-testimonial', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$article->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@else
<tr >
  <th scope="row">{{$index+1+(($articles->currentPage()-1)*$articles->perPage())}}</th>
  <td>
    @if($article && $article->imageTestimonail)
      <img src="{{$article && $article->imageTestimonail ? $article->imageTestimonail->image(50, 50) : ''}}" class="img-fluid" alt="{{$article->name}}">
    @endif
  </td>
  <td>
    {{$article->name}}
  </td>
  <td>
    {{videoStatus($article->vStatus)}}
  </td>
  <td>{{testimonialType($article->type)}}</td>
  <td>{{$article->created_at!=$article->updated_at ? $article->updated_at : $article->created_at}}</td>
  <td class="text-right">
    <a href="{{route('admin.testimonial.edit', [$article->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> | <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.testimonial.copy', [$article->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$article->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endif
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif
