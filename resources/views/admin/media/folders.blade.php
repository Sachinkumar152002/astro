@foreach($folders as $folder)
<div class="file-item" ondblclick="loadFolderContent({{$folder->id}})">
    <div class="file-item-select-bg bg-primary"></div>
    <div class="file-item-icon fa fa-folder text-primary"></div>
    <a href="javascript:void(0)" class="file-item-name">
        {{$folder->name}}
    </a>
    <div class="file-item-actions btn-group">
        <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item text-secondary" href="javascript:void(0)" onclick="createNewFolder({{$folder->id}})"><i class="fa fa-edit"></i> Rename</a>
            <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="moveemedia({{$folder->id}}, 'folder')"><i class="fas fa-level-up-alt"></i> Move</a>
            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deletemedia({{$folder->id}}, 'folder')"><i class="fa fa-trash"></i> Delete</a>
        </div>
    </div>
</div>
@endforeach

@foreach($medias as $media)
<div class="file-item" ondblclick="selectImageAndInsert({{$media->id}}, '{{$media->image(200)}}',  '{{$media->original_path}}')">
    <div class="file-item-select-bg bg-primary"></div>
    <div class="file-item-img" style="background-image: url({{$media->image(100)}});"></div>
        <a href="javascript:void(0)" class="file-item-name">
            {{$media->title}}
        </a>
    <div class="file-item-actions btn-group">
        <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item text-success" href="javascript:void(0)" onclick="copyToClipboard('{{$media->original_path}}')"><i class="fa fa-copy"></i> Copy</a>
            <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="moveemedia({{$media->id}}, 'image')"><i class="fas fa-level-up-alt"></i> Move</a>
            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deletemedia({{$media->id}}, 'image')"><i class="fa fa-trash"></i> Delete</a>
        </div>
    </div>
</div>
@endforeach


<div class="file-item" onclick="createNewFolder(0)">
    <div class="file-item-select-bg bg-success"></div>
    <div class="file-item-icon fa fa-folder text-success"></div>
    <a href="javascript:void(0)" class="file-item-name">
        New Folder
    </a>
</div>
