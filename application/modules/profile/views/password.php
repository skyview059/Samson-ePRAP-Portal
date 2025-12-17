<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>My Profile <small>Change Password</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL . '/profile' ?>"> Profile</a></li>
        <li class="active">Change Password</li>
    </ol>
</section>

<section class="content personaldevelopment">

    <?php
    echo form_open('', [
        'method' => 'post',
        'role' => 'form',
        'name' => 'updatePassword',
        'id' => 'update_password',
        'class' => 'form-horizontal'
    ]);
    ?>

    <?php echo profileTab('profile/password'); ?>
    <br>
    <div class="panel panel-default">
  <div class="panel-heading">Change Password</div>
  <div class="panel-body"><div id="ajax_respond"></div>
            <div class="form-group">
                <div class="col-md-6">
                    <div class="input-group">                               
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i> Current Password<sup>*</sup> </span>
                        <input type="password" name="old_pass" id="old_pass" 
                               maxlength="20" class="form-control pass_field">
                        <span class="input-group-addon">Length must be 8~20 characters</span>
                    </div> 
                    <p id="old_pass_err"></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">New Password<sup>*</sup></span>
                        <input type="password" name="new_pass" id="new_pass"                                
                               maxlength="20" class="form-control pass_field">
                        <span class="input-group-addon">Length must be 8~20 characters</span>
                    </div>
                    <p id="new_pass_err"></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">Confirm Password<sup>*</sup></span>
                        <input type="password" name="con_pass" id="con_pass"  
                               maxlength="20" class="form-control pass_field">
                        <span class="input-group-addon">Length must be 8~20 characters</span>                        
                    </div>
                    <p id="con_pass_err"></p>
                    <p id="pass_length_err"></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6"> 
                    <button class="btn btn-warning" id="show-pass" type="button" >
                        <i class="fa fa-eye-slash" ></i> 
                        <span>Show Pass</span>
                    </button>
                    <button class="btn btn-primary emform" onclick="password_change();" type="button" >
                        <i class="fa fa-random" ></i> 
                        Save New Password
                    </button>
                </div>
            </div>
    <?php echo form_close(); ?></div>
</div>

            
</section>

<script type="text/javascript">
    
    $('#show-pass').on('click', function(){
        var pass_field = $('.pass_field').attr('type');
        if ( pass_field === "password") {
            $('.pass_field').attr('type', "text");
            $(this).html('<i class="fa fa-eye-slash" ></i> Hide Pass');
        } else {
            $('.pass_field').attr('type', "password");         
            $(this).html('<i class="fa fa-eye" ></i> Show Pass');
        }
    });    
    
    function  password_change() {
        var formData = $('#update_password').serialize();
        var error = 0;
        var pass = $('#old_pass').val();
        if(!pass){
            $('#old_pass_err').html('<p class="ajax_error">Please enter current password</p>');
            error = 1;
        } else {
            $('#old_pass_err').empty();
        }
        var pass = $('#new_pass').val();
        if(!pass){
            $('#new_pass_err').html('<p class="ajax_error">Please enter new password</p>');
            error = 1;
        } else {
            $('#new_pass_err').empty();
        }
        var conf = $('#con_pass').val();
        if(!conf){
            $('#con_pass_err').html('<p class="ajax_error">Please enter confirm password</p>');
            error = 1;
        } else {
            $('#con_pass_err').empty();
        }
        
        if( pass ){
            if( pass.length > 8 ){            
                $('#pass_length_err').empty();
            } else {
                error = 1;
                console.log( pass.length  );
                $('#pass_length_err').html('<p class="ajax_error">Password leangth need between 8 ~ 20 chracters</p>');            
            }
        }
        
      
        if (!error) {
            $.ajax({
                url: 'admin/profile/update_password',
                type: "post",
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    $('#ajax_respond')
                            .html('<p class="ajax_processing">Please Wait...</p>')
                            .css('display', 'block');
                },
                success: function (jsonRespond) {
                    $('#ajax_respond').html(jsonRespond.Msg);
                    if (jsonRespond.Status === 'OK'){                        
                        setTimeout(function () { 
                            location.reload();
                        }, 2000);                        
                    }
                }
            });
        }
        return false;
    }
</script>
