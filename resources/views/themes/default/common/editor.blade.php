<script type="text/javascript" src="{{asset('js/editor/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript">
let joditeditor = '';
// Tiny
  tinymce.init({
  selector: "textarea:not(.mceNoEditor)",
  height: 200,
  plugins: [
    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
  ],

toolbar1: " undo redo | bold italic underline strikethrough | cut copy paste | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent blockquote | styleselect formatselect ",
  toolbar2: "hr removeformat | subscript superscript | charmap emoticons |  link unlink ",
  // toolbar3: "",

  menubar: false,
  toolbar_items_size: 'small',
  editor_deselector : "mceNoEditor",
  init_instance_callback: function () {
    window.setTimeout(function() {
      $("#div").show();
     }, 1000);
  },
});
setInterval(function(){
  tinyMCE.triggerSave();
}, 100);


</script>

@include('admin._partials.jodit-editor')
