var button = $('input[type="submit"]');
var validateAndSubmit = function (form) {
  if((typeof someVariable !== 'undefined') && tinyMCE){
    tinyMCE.triggerSave();
  }
    var _f = $(form);
    let formData = new FormData(form);
    button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
    _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
    $.ajax({
            method: "POST",
            url: _f.attr('action'),
            // data: _f.serialize()
            data: formData,
            processData: false,
            cache : false,
            contentType: false,
        })
        .done(function (msg) {
            toastr.success(msg.message);
            button.removeClass('disabled').removeAttr('disabled').val('Submit');
            if (msg.redirectto!='' && $('#edit_back').length && $('#edit_back').val()=='0' || $('#edit_back').length == 0) {
                toastr.success("Please wait redirecting...");
            }
            if(msg.redirectto!='' && msg.redirectto!='undefined' && msg.redirectto!=null) {
                setTimeout(function () {
                    window.location.href = msg.redirectto;
                }, 1000);
            }
        })
        .fail(function (errors){
          button.removeClass('disabled').removeAttr('disabled').val('Submit');
            if(errors.responseJSON.error){
              toastr.error(errors.responseJSON.error);
              return false;
            }
            var err = $.parseJSON(errors.responseText);
            for (i in err.errors) {
                $('#' + i).addClass('btn-outline-danger');
                toastr.error(err.errors[i][0]);
            }
        });

    return false;
}

$(function(){
    $('select').not('.nosearch').selectpicker({
        liveSearch: true
    });
});

$('input').on('keypress', function(e){
    if(e.keyCode==13) {
        return false;
    }
});
if($('.tagsinput').length>0){
    $('.tagsinput').tagsinput({
        confirmKeys: [13, 44],
        trimValue: true
    });
}
