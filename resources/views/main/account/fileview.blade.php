<!DOCTYPE html>
<html>
<head>
    <title>View File</title>
</head>
<body style="overflow: hidden; margin: 0; padding:0;">
<iframe src="{{url('file/'.$file->file)}}#toolbar=0" style="width:100%; height: 100%; overflow: hidden; border: 0;"  id="file_view"></iframe>
</body>
<script type="text/javascript">
var file_view = document.getElementById('file_view');


window.onload = function(){
    var size = {
      width: window.innerWidth || document.body.clientWidth,
      height: window.innerHeight || document.body.clientHeight
    }
    file_view.style.height = size.height+'px';
    file_view.style.width = size.width+'px';
}
document.oncontextmenu = function() {
    return false;
};
document.onkeydown = function(e) {
    if(e.keyCode == 123) {
     return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
     return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
     return false;
    }
    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
     return false;
    }

    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
     return false;
    }
 }
</script>
</html>
