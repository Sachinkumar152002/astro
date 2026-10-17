<li class="breadcrumb-item text-primary">
    <a href="javascript:void(0)" onclick="loadFolderContent(0)">root</a>
</li>
@foreach($breads as $bid=>$bname)
<li class="breadcrumb-item text-primary">
    <a href="javascript:void(0)" onclick="loadFolderContent({{$bid}})">{{$bname}}</a>
</li>
@endforeach
