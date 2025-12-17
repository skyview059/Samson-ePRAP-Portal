<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Student  <small>Name <b><?php echo $student_name; ?></b></small></h1>    
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">student</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'reset'); ?>

    <div class="panel panel-default">
  <div class="panel-body">
      <form name="reset" id="reset" role="form" method="POST">
            <input name="id" value="<?php echo $id; ?>" type="hidden" />
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div id="ajax_respond"></div>

                        
                        <div class="form-group">
                            <div class="input-group">                               
                                <span class="input-group-addon"><i class="fa fa-user"></i> Name</span>                        
                                <input value="<?php echo "{$fname} {$mname} {$lname}"; ?>" type="text" readonly="readonly"  class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">                               
                                <span class="input-group-addon"><i class="fa fa-envelope-o"></i> Email</span>                        
                                <input name="email" value="<?php echo $email; ?>" type="text" readonly="readonly"  class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">New Password<sup>*</sup></span>
                                <input type="password" minlength="6" maxlength="12" required="" name="new_pass" id="new_pass" class="form-control"/>
                                
                                <span class="input-group-addon showhide-pass" style="cursor: pointer;">
                                    <i class="fa fa-eye-slash"></i>
                                    Show Password
                                </span>
                            </div>
                        </div>
                        <blockquote>
                            <p class="help-block">
                                <em>
                                The password must be between 6 ~ 12 characters. 
                                And must have letters, numbers, and special characters.
                                </em>
                            </p>
                        </blockquote>

                        <div class="box-footer with-border">
                            <button class="btn btn-primary" onclick="password_change();" type="button" >
                                <i class="fa fa-random"></i> 
                                Update
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
  </div>
</div>
</section>
<script>

    $('body').on('click', '.showhide-pass', function(){
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        var input = $("#new_pass");        
        if (input.attr('type') === 'password') {
          input.attr('type', 'text');          
        } else {
          input.attr('type', 'password');          
        }
    });


    function  password_change() {
        var formData = $('#reset').serialize();
        var error = 0;
        
        var new_pass = $('[name=new_pass]').val();
        if (!new_pass) {
            $('[name=new_pass]').addClass('required');
            error = 1;
        } else {
            $('[name=new_pass]').removeClass('required').addClass('required_pass');
        }

        


        if (!error) {
            $.ajax({
                url: '<?php echo Backend_URL; ?>student/reset_action',
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
                    if (jsonRespond.Status === 'OK') {                        
                        setTimeout(function () {
                            $('#ajax_respond').slideUp('slow');
                            document.getElementById("update_password").reset();
                            $('[name=new_pass]').removeClass('required_pass');
                        }, 2000);
                    }
                }
            });
        }
        return false;
    }
</script>