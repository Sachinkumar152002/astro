<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center modal-title">Register Your Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" id="registration-form" method="POST"
                      action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="radio radio-section">
                        <div class="radi-sub" style="display: none">
                            <label> <input type="radio" checked name="register-option"
                                           onclick="$('#registration-form').attr('action', '{{ route('register') }}');">Student</label>
                        </div>
                    </div>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                           autofocus placeholder="Name">
                    <input id="register_email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                           required placeholder="Email">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required
                           minlength="10" maxlength="10">
                    <input id="register_password" type="password" class="form-control" name="password" required
                           placeholder="Password">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                           required placeholder="Confirm Password">
                    <div class="clearfix"></div>
                    <input class="mb-10" name="tnc" value="Y" required="" type="checkbox">
                    <label>I accept all <a href="{{route('terms')}}" target="_blank"> terms &amp; conditions.</a>
                    </label>
                    <input type="submit" name="register" class="login loginmodal-submit" value="Register"
                           id="register-submit">
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">Already have an account? <a class="link" href="javascript:void(0)"
                                                                     onclick="openModal('login')">Login</a></div>
            </div>
        </div>
    </div>
</div>
{{--<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="loginmodal-container">
            <div class="modal-header">
                <h4 class="text-center modal-title">Register Your Account</h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" id="registration-form" method="POST" action="{{ route('register') }}" >
                    {{ csrf_field() }}
    <div class="radio radio-section">
                    <div class="radi-sub">
                    <label> <input type="radio" checked name="register-option" onclick="$('#registration-form').attr('action', '{{ route('register') }}');">Student</label> </div>
                    <div class="radi-sub"><label> <input type="radio" name="register-option" onclick="$('#registration-form').attr('action', '{{ route('employer.register') }}');">Employer</label></div>

                    </div>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
                    <input id="register_email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Email">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required minlength="10" maxlength="10">
                    <input id="register_password" type="password" class="form-control" name="password" required placeholder="Password">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                     <div class="clearfix"></div>
                    <input class="mb-10" name="tnc" value="Y" required="" type="checkbox">
                    <label>I accept all <a href="{{route('terms')}}" target="_blank"> terms &amp; conditions.</a> </label>
                    <input type="submit" name="register" class="login loginmodal-submit" value="Register" id="register-submit">
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center">Already have an account? <a class="link" href="javascript:void(0)" onclick="openModal('login')">Login</a></div>
            </div>
        </div>
    </div>
</div>--}}

@push('scripts')
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
        $('#registration-form').validate({
            rules: {
                password_confirmation: {
                    equalTo: "#register_password"
                }
            },
            submitHandler: function (form) {
                var form = $(form);
                $('#register-submit').addClass('disabled').attr('disabled', 'disabled').val('Validating..');
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        toastr.success('Thankyou for registeration');
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    })
                    .catch(function (error) {
                        $('#register-submit').removeClass('disabled').removeAttr('disabled').val('Register');
                        var errors = error.response.data;
                        for (i in errors) {
                            var emlmt = form.find('input[name="' + i + '"]');
                            emlmt.addClass('error');
                            var errorId = emlmt.attr('id');
                            if ($('#' + errorId + '-error').length) {
                                $('#' + errorId + '-error').html(errors[i]).show();
                            } else {
                                emlmt.after('<label id="' + errorId + '-error" class="error" for="' + errorId + '">' + errors[i] + '</label>');
                            }
                            toastr.error(errors[i]);
                        }
                    });
                return false;
            }
        });

    </script>
@endpush