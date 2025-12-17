<div class="panel panel-default">
  <div class="panel-heading">Change Password</div>
  <div class="panel-body">
      <div id="message">
    <?php echo $this->session->flashdata('message'); ?>
</div>

<form method="POST" class="form-horizontal" role="form" id="change_pwd" action="">

    <div class="col-md-12">
        <blockquote>
            <p class="text-red">
                <em>
                 Password must have a 
                 minimum of 6 and maximum of 12 characters.
                 It must contain letters, numbers and special characters.  
                </em>
            </p>
        </blockquote>
        <div class="form-group">
            <div id="ajax_respond"></div>
        </div>
        
        

        <div class="form-group">
            <label class="col-sm-3" for="old_pass">Current Password</label>
            <div class="col-md-5">
                <input autocomplete="off" maxlength="12" type="password" name="old_pass" id="old_pass" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="new_pass">New Password</label>
            <div class="col-md-5">
                <div class="input-group">                    
                    <input autocomplete="off" maxlength="12" type="password" name="new_pass" id="new_pass" class="form-control">
                    <span class="input-group-addon">Min 6 & Max 12 Characters</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="con_pass">Confirm Password</label>
            <div class="col-md-5">
                <div class="input-group">                    
                    <input autocomplete="off" maxlength="12" type="password" name="con_pass" id="con_pass" class="form-control">
                    <span class="input-group-addon">Min 6 & Max 12 Characters</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-3">
                <button type="submit" class="btn btn-primary" id="personalSubmit">Update</button>
            </div>
        </div>
    </div>
</form>
  </div>
</div>




<script type="text/javascript">
    $(document).on('submit', '#change_pwd', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var error = 0;

        var old_pass = $('#old_pass').val();
        if (!old_pass) {
            $('#old_pass').addClass('required');
            error = 1;
        } else {
            $('#old_pass').removeClass('required');
        }

        var new_pass = $('#new_pass').val();
        if (!new_pass) {
            $('#new_pass').addClass('required');
            error = 1;
        } else {
            $('#new_pass').removeClass('required');
        }

        var con_pass = $('#con_pass').val();
        if (!con_pass) {
            $('#con_pass').addClass('required');
            error = 1;
        } else {
            $('#con_pass').removeClass('required');
        }

        if (!error) {
            jQuery.ajax({
                url: 'student_portal/change_password_action',
                type: "POST",
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    jQuery('#ajax_respond')
                            .html('<p class="ajax_processing">Updating...</p>')
                            .css('display', 'block');
                },
                success: function (respond) {
                    jQuery('#ajax_respond').html(respond.Msg);
                    
                    
                    
                    if (respond.Status === 'OK') {
                        document.getElementById("change_pwd").reset();
                        setTimeout(function () {
                            jQuery('#ajax_respond').slideUp('slow');
                        }, 2000);
                    }
                }
            });
        }
    });
</script>