
<div class="container">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-box-custom" style="margin-top: 100px;"> 
            <div class="login-box">               
                <div class="login-box-body">
                    <form id="credential" action="" method="post">

                        <h3 style="margin:0;">Reset Your Password</h3>
                        <div id="respond"></div>
                        <input type="hidden" name="verify_token" value="<?php echo $this->input->get('token'); ?>" >
                        <div class="form-group has-feedback">

                            <input type="text" readonly class="form-control" id="email" name="email" value="<?php echo $this->input->get('email'); ?>">
                        </div>    

                        <div class="form-group has-feedback">
                            <input type="password" value="" name="new_password" id="new_password"  class="form-control" placeholder="New Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>

                        <div class="form-group has-feedback">
                            <input type="password" value="" name="retype_password" id="retype_password"  class="form-control" placeholder="Retype password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>


                        <button type="button" id="reset_pass"  class="btn btn-primary btn-block btn-flat">Reset & Back to Sign In Page</button>


                    </form>


                </div>
                <!-- /.login-box-body -->
            </div>
        </div>
    </div>
</div>





<script>


    var $ = jQuery;
    $('#reset_pass').on('click', function () {

        $('.validation_error').html('');

        var error = 0;
        
        var new_password = $('#new_password').val();
        if (!new_password) {
            $('#new_password').addClass('required');

            $('#new_password').closest('.form-group').append('<em class="validation_error">Please enter new password</em>');
            error = 1;
        } else {
            $('#new_password').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        var retype_password = $('#retype_password').val();
        if (!retype_password) {
            $('#retype_password').addClass('required');

            $('#retype_password').closest('.form-group').append('<em class="validation_error">Retype  password</em>');
            error = 1;
        } else {
            $('#retype_password').addClass('required');
        }


        if (error === 0) {
            var formData = jQuery('#credential').serialize();
            jQuery.ajax({
                url: 'auth_student/reset_password_action',
                type: "post",
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    jQuery('#respond').html('<p class="ajax_processing">Updating...</p>');
                },
                success: function (jsonRespond) {
                    jQuery('#respond').html(jsonRespond.Msg);
                    if (jsonRespond.Status === 'OK') {
                        setTimeout(function () {
                            jQuery('.formresponse').fadeOut('slow');
                            window.location.href = "login";
                        }, 1500);
                    }
                }
            });
            return false;
        }
    });
</script>

