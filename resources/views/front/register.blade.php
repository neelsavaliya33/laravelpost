@extends('layouts.front')
@section('content')
    <div class="container-fluid register-background">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-10 col-lg-6 offset-0 offset-sm-1 offset-lg-3">
                    <form class="login-form" id="register-form" method="POST" action="{{ route('user.register') }}">
                        @csrf
                        <div class="title">
                            Register
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="name" placeholder="Username">
                            <label id="name-er" class="error"></label>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="email" placeholder="User email">
                            <label id="email-er" class="error"></label>
                        </div>
                        <div class="form-group mt-3">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="********">
                            <label id="password-er" class="error"></label>

                        </div>
                        <div class="form-group mt-3">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="********">
                            <label id="password_confirmation-er" class="error"></label>

                        </div>
                        <button type="submit" class="btn btn-theme mt-3" id="register-submit">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span>Submit</span>
                        </button>
                        <div class="signIn text-center mt-3">Have an Account ? <strong><a href="{{ route('user.login') }}">Sign
                                    In</a></strong> Now.
                        </div>
                        <div class="signIn text-center text-success mt-3 d-none" id="success-msg"> Yor account is created
                            <strong><a href="{{ route('user.login') }}" class="text-success">Sign In</a></strong> now </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $("#register-form").validate({
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password_confirmation: {
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: {
                        required: "Please enter username",
                    },
                    email: {
                        required: "Please enter email",
                        email: 'Please enter a valid email'
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Your password must be 5 characters long"
                    },
                    password_confirmation: {
                        equalTo: "Your password and confirm password not same"
                    },
                },
                submitHandler: function(form) {
                    $('#register-submit').attr('disabled', true).addClass('loding');
                    $.ajax({
                        type: "post",
                        url: form.action,
                        data: $(form).serializeArray(),
                        dataType: "json",
                        success: function(response) {
                            $('#success-msg').removeClass('d-none');
                            $(form)[0].reset();
                            $('#register-submit').attr('disabled', false).removeClass(
                                'loding');
                        },
                        error: function(error) {
                            var res = error.responseJSON;
                            var errors = res.errors;
                            for (er of Object.keys(errors)) {
                                $(`#${er}-er`).empty().append(errors[er][0]).removeAttr(
                                    'style');
                            }
                            $('#register-submit').attr('disabled', false).removeClass(
                                'loding');
                        }

                    });
                }
            });
        });

    </script>
@endsection
