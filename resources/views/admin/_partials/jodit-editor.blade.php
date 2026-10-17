<link rel="stylesheet" href="{{asset('joditeditor/jodit.fat.min.css')}}" integrity="sha512-O+7d1TWkImVhLnWSuA4cSS6X5N2PAEYVqGHf9I+PMfNuoFCbhvgInfl2zrI7FY1Omiys4kApXXWQYKG0ditO4g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="{{asset('joditeditor/jodit.fat.min.js')}}" integrity="sha512-M8VijecL0x6mNY2u5nAbItGhq0uksvfnlKpofV82ccJYX+o0wACPZkt1I4QK7jlk/I4ZUQodZ11FFc9FDrciAQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="modal fade" id="jodit-editor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document" style="margin: 0">
        <div class="modal-content bg-white" style="width: 100vw; height:95vh;">
            <div class="modal-body">
               <textarea name="joditeditor" id="joditeditor" class="mceNoEditor" cols="30" rows="300"></textarea>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success text-dark" onclick="updateToTinyMce()">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    var updateToTinyMce = function(){
        tinyMCE.activeEditor.setContent(joditeditor.value);
        joditeditor.setEditorValue('');
        $('#jodit-editor').modal('hide');
    }
</script>
