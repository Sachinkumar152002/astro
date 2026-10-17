@if(count($files)>0)
<table class="table table-sm">
    <tr>
    <th>Sr.</th>
    <th>Title</th>
    <th></th>
    <th>Format</th>
    <th></th>
    </tr>

    @foreach($files as $i=>$file)
    <tr>
    <td> {{$i+1}}</td>
    <td> {{$file->title}}</td>
    <td> <a href="{{route('course.file.view',[$file->id, time()])}}"  target="_blank">
    <i class="fa fa-2 fa-2x {{$file->file_type=='pdf'?'fa-file-pdf-o':'fa-file-movie-o'}}"></i></a></td>
    <td> {{strtoupper($file->file_type)}}</td>
    <td> <a href="{{route('course.file.view',[$file->id, time()])}}"  target="_blank">
    Click Here</a></td>
    </tr>
    @endforeach

</table>
@else
<p>No File found</p>
@endif