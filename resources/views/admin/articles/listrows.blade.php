@if($articles->count())
@foreach($articles as $index=>$article)
<tr >
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($articles->currentPage()-1)*$articles->perPage())}}</td>
  <td>
    @if($article->image_id && $article->image)
    <a href="javascript:void(0)" data-img="{{$article->image->image(400,400)}}" class="img-view">
        <img src="{{$article->image->image(100,100)}}" alt="{{$article->title}}" class="img-fluid">
    </a>
    @endif
  </td>
  <td>
    @if(adminCan('blog', 'update'))
    <a href="{{route('admin.article.edit', [$lang, $article->id])}}">
      {{$article->title}}
    </a>
    @else
    {{$article->title}}
    @endif
  </td>
  <td>{{articleStatus($article->status)}}</td>
  <td>{{$article->user_id ? $article->user->name : ($article->admin ? $article->admin->name : '')}}</td>
  <td>{{$article->created_at!=$article->updated_at ? $article->updated_at : $article->created_at}}</td>
  <td class="text-right">
    {{--
    <a href="{{route('blog.post', [$article->category?$article->category->slug:'all',$article->slug])}}" class="text-primary" target="_blank">
      <i class="fa fa-eye"></i>
    </a>
    |
    --}}
    @if(adminCan('blog', 'update'))
    <a href="{{route('admin.article.edit', [$lang, $article->id])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan('blog', 'add'))
    |
    <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.article.copy', [$lang,$article->id])}}')" class="text-secondary">
      <i class="fa fa-copy"></i>
    </a>
    @endif
    @if(adminCan('blog', 'delete'))
    |
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$article->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif
