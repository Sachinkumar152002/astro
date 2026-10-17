@if($medias->count())
@foreach($medias as $index=>$media)
<tr>
  <th scope="row">{{$index+1+(($medias->currentPage()-1)*$medias->perPage())}}</th>
  @php
    $imageUrl = $media->image(200,200);
  @endphp
  <td>
    <a href="javascript:void(0)" data-img="{{$media->image(800,800)}}" class="media-img-view">
    <img src="{{$imageUrl}}" alt="{{$media->title}}" class="img-fluid">
    </a>

  </td>
  <td><a href="javascript:void(0)" onclick="copyToClipboard('{{$media->originalPath}}')" title="Click to copy path"><i class="far fa-copy"></i></a></td>
  <td>{{$media->title}}</td>
  <td>{{isset($types[$media->type]) ?  $types[$media->type] : '' }}</td>
  {{-- <td>{{$media->created_at}}</td> --}}
  <td>{{$media->updated_at!=$media->created_at ? $media->updated_at : $row->created_at}}</td>
  @php
  $mediaObj = json_encode([
    'media_id'=>$media->id,
    'title'=>$media->title,
    'type'=>$media->type,
    'caption'=>$media->caption,
    'image'=>$imageUrl
  ]);
  @endphp
  <td class="text-right">
    <a href="javascript:void(0)" class="text-success" onclick="editCategory('{{$mediaObj}}')">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$media->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
