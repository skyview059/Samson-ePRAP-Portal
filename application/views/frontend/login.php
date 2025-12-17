<style>
    .d-none{
        display: none;
    }
</style>

<div class="loginpagee">
    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-box-custom">

                    <div class="login-box">

                        <div class="login-box-body js_login">

                            <form id="credential" class="form-signin text-center" action="" method="post">

                                <h1 class="h3 mb-3 font-weight-normal">Please sign in </h1>
                                <?php

                                $msg = $this->input->get('msg');
                                if ($msg == 'success') {
                                    echo '<p class="ajax_success">Please check your email and verify</p>';
                                }
                                ?>

                                <div id="respond"></div>
                                <label for="inputEmail" class="sr-only">Email address</label>
                                <input type="email" id="inputEmail" name="username" class="form-control"
                                       placeholder="Email address" required autofocus>
                                <label for="inputPassword" class="sr-only">Password</label>
                                <input type="password" id="inputPassword" name="password" class="form-control"
                                       placeholder="Password" required>

                                <p class="text-left">
                                    <label style="font-size: 11pt;">
                                        <input type="checkbox" onclick="show_pass();">
                                        Show Password
                                    </label>
                                </p>
                                
                                <button type="submit" id="signin" class="btn btn-lg btn-primary btn-block">Sign in</button>
                                <a class="js_forgot">Forgot Password?</a><br>

                                <div style="margin-top: 50px;">
                                    If you are not registered, click <a href="<?php echo site_url('sign-up'); ?>">Register for Free</a>
                                </div>
                            </form>
                        </div>

                        <div class="login-box-body form-signin js_forget_password" style="display: none; ">
                            <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
                            <div id="maingReport"></div>
                            <div class="formresponse"></div>

                            <form action="auth/forgot_password" method="post" id="forgotForm">
                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" placeholder="Enter Your Email"
                                           name="forgot_mail" id="forgot_mail">

                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="btn btn-default js_back_login">Back to Sign in</span>
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="button" class="btn btn-primary btn-block btn-flat"
                                                id="forgot_pass">Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="login-box-body otp_verfication" style="display: none; margin-top: 100px; ">

                            <form id="otp_credential" class="text-center" name="otp_credential" method="post">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-3">
                                        <h1 class="h3 mb-3 font-weight-normal">
                                            Enter verification code
                                        </h1>
                                        <div id="otpresponse"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-3">
                                        <div class="form-group has-feedback">
                                            <input type="text" maxlength="4" class="form-control"
                                                   placeholder="Enter verification code" name="otp_code" id="otp_code">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary" id="otp_submit">Verify</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-default resendBTN" id="otp_resend_in_mail">Resend</button>
                                        <button type="button" class="btn btn-dark disabled resendBTN d-none">Resend</button>
                                    </div>
                                    <div class="col-md-8 col-md-offset-3">
                                        <button id="otp_resend" style="font-size: 11pt; cursor: pointer; margin-top: 15px;" class="btn btn-success resendBTN">
                                            Didn't receive SMS? Get code via Phone
                                        </button>

                                        <p style="color: limegreen; font-size: 11pt; cursor: pointer; margin-top: 15px" class="reset_time resendBTN d-none"> </p>

                                        <span id="email_otp_msg"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/theme/js/login.js"></script>

<script>
    function reset_timer(){
        $('.resendBTN').toggleClass('d-none');

        let countDownDate = new Date().getTime();

        let newCountDownTime = countDownDate + ( 59 * 1000 ); // adding minutes in milliseconds
        let updatedCountDownDate = new Date(newCountDownTime);

        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = updatedCountDownDate - now;

            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $('.reset_time').text(`Resend OTP in ${seconds}s`);

            if (distance < 0) {
                clearInterval(x);
                $('.resendBTN').toggleClass('d-none');

                $('#otpresponse').empty();
                $('#email_otp_msg').empty();
            }
        }, 1000);
    }
</script>