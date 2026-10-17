<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Login</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="message"></div>
                <form class="form-horizontal" role="form" id="login-form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="radio radio-section">
                        <div class="radi-sub" style="display: none">
                            <label> <input type="radio" checked name="login-option"
                                           onclick="$('#login-form').attr('action', '{{ route('login') }}');">Student</label>
                        </div>
                    </div>

                    <input id="login_email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                           required autofocus placeholder="Email">
                    <input id="login_password" type="password" class="form-control" name="password" required
                           placeholder="Password">
                    <div class="clearfix"></div>
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>

                    <input type="submit" name="login" class="login loginmodal-submit" value="Login" id="login-submit">
                </form>
                <div class="login-help">
                    <a href="javascript:void(0)" onclick="openModal('forgot')">Forgot Password</a>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">If you don't have an account <a class="link" href="javascript:void(0)"
                                                                         onclick="openModal('registration')">Create your
                        account</a></div>
            </div>
        </div>
    </div>
</div>

{{--<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">

        <div class="loginmodal-container">
            <div class="modal-header">
                <h4 class="modal-title text-center">Login</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" id="login-form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="radio radio-section">
                        <div class="radi-sub">
                            <label> <input type="radio" checked name="login-option"
                                           onclick="$('#login-form').attr('action', '{{ route('login') }}');">Student</label>
                        </div>
                        <div class="radi-sub"><label> <input type="radio" name="login-option"
                                                             onclick="$('#login-form').attr('action', '{{ route('employer.login.submit') }}');">Employer</label>
                        </div>

                    </div>

                    <input id="login_email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                           required autofocus placeholder="Email">
                    <input id="login_password" type="password" class="form-control" name="password" required
                           placeholder="Password">
                    <div class="clearfix"></div>
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>

                    <input type="submit" name="login" class="login loginmodal-submit" value="Login" id="login-submit">
                </form>
                <div class="login-help">
                    <a href="javascript:void(0)" onclick="openModal('forgot')">Forgot Password</a>
                </div>


            </div>

            <div class="modal-footer">
                <div class="text-center">If you don't have an account <a class="link" href="javascript:void(0)"
                                                                         onclick="openModal('registration')">Create your
                        account</a></div>
            </div>
        </div>
    </div>
</div>--}}

@push('scripts')
    <script type="text/javascript">
        $("#login-form").validate({
            submitHandler: function (form) {
                $('#login-submit').addClass('disabled').attr('disabled', 'disabled').val('Validating..');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        $('#login-submit').val('Success');
                        window.location.reload();
                    })
                    .catch(function (error) {
                        $('#login-submit').removeClass('disabled').removeAttr('disabled').val('Login');
                        // var errors = error.response.data;
                        // for (i in errors) {
                        //     if(errors[i] != null){
                        //         toastr.error(errors[i]);
                        //     }
                        // }
                        toastr.error('The given data is invalid');
                    });
                return false;
            }
        });
    </script>
@endpush