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

toolbar1: " undo redo | bold italic underline strikethrough | cut copy paste | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent blockquote | styleselect formatselect fontselect fontsizeselect | table   ",
  toolbar2: "hr removeformat | subscript superscript | charmap emoticons |  link unlink anchor image media code  | searchreplace | forecolor backcolor | server_image | iiag_editor | fullscreen",
  // toolbar3: "",

  menubar: false,
  toolbar_items_size: 'small',
  editor_deselector : "mceNoEditor",
  init_instance_callback: function () {
    window.setTimeout(function() {
      $("#div").show();
     }, 1000);
  },
  setup: function(ed) {
      ed.addButton('server_image', {
         title: 'Insert Image',
         image: 'data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNDkwLjA3MSA0OTAuMDcxIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0OTAuMDcxIDQ5MC4wNzE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDI2LjMzNCwxMzMuMzM0aC0xMS4wNzZMMjg5LjkzOCwxOC44NDZjLTI0LjYyOS0yMi40NzQtNjIuODEyLTIyLjA1OS04Ni45MzEsMC45NDdMODQuMDA2LDEzMy4zMzRINjMuNjU0ICAgIEMyOC40OTEsMTMzLjMzNCwwLDE2MS44NTIsMCwxOTcuMDJ2MjI3LjFjMCwzNS4xOCwyOC40OTEsNjMuNjkxLDYzLjY1NCw2My42OTFoMzYyLjY4YzM1LjE3NCwwLDYzLjczNy0yOC41MTIsNjMuNzM3LTYzLjY5MSAgICB2LTIyNy4xQzQ5MC4wNzEsMTYxLjg1Miw0NjEuNTA4LDEzMy4zMzQsNDI2LjMzNCwxMzMuMzM0eiBNMjM4Ljk5Myw1Ny41MDVjNC4zOTMtNC4xODUsMTEuMzI1LTQuMjUyLDE1Ljc5LTAuMTcybDgzLjE5Miw3NiAgICBIMTU5LjUxNkwyMzguOTkzLDU3LjUwNXogTTQzNy43ODQsNDI0LjEyYzAsNi4zODMtNS40NDQsMTEuNDA0LTExLjgxNSwxMS40MDRINjMuNDI2Yy02LjM4MSwwLTExLjc0Mi01LjAyMS0xMS43NDItMTEuNDA0ICAgIFYxOTcuMDkzYzAtNi4zODEsNS4zNjEtMTEuNDY1LDExLjc0Mi0xMS40NjVoMzYyLjU0M2M2LjM3MSwwLDExLjgxNSw1LjA4NCwxMS44MTUsMTEuNDY1VjQyNC4xMnoiIGZpbGw9IiMwMDAwMDAiLz4KCQk8cGF0aCBkPSJNMTI1LjY0NCwyOTYuNjU1YzI2LjEyOCwwLDQ3LjMyMi0yMS4xNzgsNDcuMzIyLTQ3LjMxMWMwLTI2LjEyOS0yMS4xOTQtNDcuMzE4LTQ3LjMyMi00Ny4zMTggICAgYy0yNi4xMjksMC00Ny4zMTIsMjEuMTg5LTQ3LjMxMiw0Ny4zMThDNzguMzMyLDI3NS40NzcsOTkuNTE1LDI5Ni42NTUsMTI1LjY0NCwyOTYuNjU1eiIgZmlsbD0iIzAwMDAwMCIvPgoJCTxwYXRoIGQ9Ik00MDEuNjQzLDI0NS41NzFjLTYuMzUtNC41OTEtMTQuNDU5LTUuODU1LTIxLjkxMS0zLjM5OWwtMjgzLjM1LDkzLjczOWMtMTAuNzY0LDMuNTY0LTE3Ljk0NiwxMy42MjUtMTcuOTQ2LDI0Ljk2NyAgICB2MzEuODE2YzAsNi40NzcsNS4xNTMsMTEuODIsMTEuNjE3LDExLjgyaDMxMC4xMTFjNi40NzUsMCwxMS40NzItNS4zNDYsMTEuNDcyLTExLjgydi0xMjcuMzMgICAgQzQxMS42MzYsMjU3LjUyLDQwNy45OTIsMjUwLjE1Nyw0MDEuNjQzLDI0NS41NzF6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==',
         onclick: function() {
            ed.focus();
            $('#media-img-manager').modal('show');
            imageset = 'editor';
         }
      });

      ed.addButton('iiag_editor', {
         title: 'Code Editor',
         image: 'data:image/png;base64,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',
         onclick: function() {
            ed.focus();
            $('#jodit-editor').modal('show');
            tinyMCE.triggerSave();
            joditeditor =  Jodit.make('#joditeditor', {
                  width: '100vw',
                  height: '85vh',
                  buttons: ['sourceEditor'],
                  defaultMode: Jodit.constants.MODE_SOURCE,
                  // sourceEditor: 'area',
                  theme: 'dark',
                  toolbarButtonSize: 'small',
                  toolbar: false,
                  autofocus: true
            });
            joditeditor.setEditorValue(tinyMCE.activeEditor.getContent());
         }
      });
   }
});
setInterval(function(){
  tinyMCE.triggerSave();
}, 100);


</script>

@include('admin._partials.jodit-editor')
