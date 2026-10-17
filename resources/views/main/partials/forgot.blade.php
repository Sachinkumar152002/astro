<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Enter Email to reset your password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}"
                      id="reset-password-form">
                    {{ csrf_field() }}
                    <div class="radio radio-section">
                        <div class="radi-sub" style="display: none">
                            <label> <input type="radio" checked name="optradio">Student</label>
                        </div>
                    </div>
                    <input id="reset-password-email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required placeholder="Email">
                    <input type="submit" name="register" class="login loginmodal-submit"
                           value="Send Password Reset Link" id="forgot-submit">
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center"><a class="link" href="javascript:void(0)"
                                            onclick="openModal('login')">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{--<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="loginmodal-container">
            <div class="modal-header">
                <h4 class="modal-title text-center">Enter Email to reset your password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}"
                      id="reset-password-form">
                    {{ csrf_field() }}
                    <div class="radio radio-section">
                        <div class="radi-sub">
                            <label> <input type="radio" checked name="optradio">Student</label></div>
                        <div class="radi-sub"><label> <input type="radio" name="optradio">Employer</label></div>

                    </div>
                    <input id="reset-password-email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required placeholder="Email">
                    <input type="submit" name="register" class="login loginmodal-submit"
                           value="Send Password Reset Link" id="forgot-submit">
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center"><a class="link" href="javascript:void(0)"
                                            onclick="openModal('login')">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>--}}

@push('scripts')
    <script type="text/javascript">

        $("#reset-password-form").validate({
            submitHandler: function (form) {
                $('#forgot-submit').addClass('disable').attr('disabled', 'disabled');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        toastr.success('Password send to your email.');
                        toastr.success('Please check your email inbox.');
                        setTimeout(function () {
                            window.location.reload();
                        }, 5000);
                    })
                    .catch(function (error) {
                        $('#forgot-submit').removeClass('disable').removeAttr('disabled');
                        var errors = error.response.data;
                        for (i in errors) {
                            toastr.error(errors[i]);
                        }
                    });
                return false;
            }
        });


    </script>
@endpush