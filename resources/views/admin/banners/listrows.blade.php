@if($banners->count())
@foreach($banners as $index=>$banner)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($banners->currentPage()-1)*$banners->perPage())}}</td>
  <td>
    <a href="javascript:void(0)" data-img="{{$banner->image ? $banner->image->image(800,800) : no_image()}}" class="banner-img-view">
        <img src="{{$banner->image ? $banner->image->image(200,200) : no_image()}}" alt="{{$banner->title}}" class="img-fluid">
    </a>
  </td>
  <td>
    @if(adminCan('banner', 'update'))
    <a href="{{route('admin.banner.edit', [$banner->id])}}">
      {{$banner->title}}
    </a>
    @else
      {{$banner->title}}
    @endif
  </td>
  <td>{{$banner->type}}</td>
  <td>{{$banner->updated_by ? $banner->updated_at : $banner->created_at}}</td>

  <td class="text-right">
    @if(adminCan('banner', 'update'))
      <a href="{{route('admin.banner.edit', [$banner->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('banner', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.banner.copy', [$banner->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('banner', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$banner->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
