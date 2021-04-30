@extends('layouts.front')
@section('content')
    <div class="container-fluid register-background">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-10 col-lg-6 offset-0 offset-sm-1 offset-lg-3">
                    <form class="login-form" id="login-form" method="POST" action="{{ route('user.login') }}">
                        @csrf
                        <div class="title">
                            Login
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
                        <button type="submit" class="btn btn-theme mt-3" id="login-submit">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span>Submit</span>
                        </button>
                        <div class="signIn text-center mt-3">Don't Have an Account ? <strong><a href="{{ route('user.register') }}">Sign Up</a></strong> Now.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $("#login-form").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    email: {
                        required: "Please enter email",
                        email: 'Please enter a valid email'
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Your password must be 5 characters long"
                    },
                },
                submitHandler: function(form) {
                    $('#login-submit').attr('disabled',true).addClass('loding');
                    $.ajax({
                        type: "post",
                        url: form.action,
                        data: $(form).serializeArray(),
                        dataType: "json",
                        success: function(response) {
                            $(form)[0].reset();
                            $('#login-submit').attr('disabled',false).removeClass('loding');
                            location.reload();
                        },
                        error: function(error) {
                            var res = error.responseJSON;
                            var errors = res.errors;
                            for(er of Object.keys(errors)){
                                $(`#${er}-er`).empty().append(errors[er][0]).removeAttr('style');
                            }
                            $('#login-submit').attr('disabled',false).removeClass('loding');
                        }

                    });
                }
            });
        });

    </script>
@endsection
