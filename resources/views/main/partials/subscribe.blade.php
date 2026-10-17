<div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="subscribemodal-container">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-body">
        <div class="subscribe-section text-center">
          <div class="image-section"><img src="{{asset('images/newsletter-icon.png')}}"></div>
          <h2><span>Subscribe</span> to Newsletter</h2>
          <p>We respect your privacy.Your Information is safe and will never be shared.</p>
        </div>
        <div class="form-section text-center">
          <form action="{{route('subscribe')}}" method="post" id="subscribe-form">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
              <input  type="email" class="form-control" placeholder="Enter your email address" name="email" required="">
            </div>
            <div class="btns">
              <button class="btn submit-btn w100" type="submit" align="right"  id="subscriber-btn-pop"> Submit </button>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="text/javascript">
$("#subscribe-form").validate({
submitHandler : function(form){
$('#subscriber-btn-pop').addClass('disabled').attr('disabled', 'disabled').val('Validating..');
var form = $(form);
axios.post(form.attr('action'), form.serialize())
.then(function (response) {
toastr.success('Thankyou for subscribing');
$('#subscriber-btn-pop').val('Success');
$('#subscribe-modal').modal('hide');
})
.catch(function (error) {
var errors = error.response.data;
for(i in errors){
toastr.error(errors[i]);
}
$('#subscriber-btn-pop').removeClass('disabled').removeAttr('disabled').val('subscribe');
});
return false;
}
});
</script>
@endpush