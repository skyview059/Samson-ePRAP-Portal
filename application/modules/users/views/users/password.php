<?php
defined('BASEPATH') OR exit('No direct script access allowed');

load_module_asset('users', 'css');
?>
<section class="content-header">
    <h1>User Details <small>of</small> <?php echo $first_name . ' ' . $last_name; ?> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin </a></li>
        <li><a href="<?php echo Backend_URL . '/users' ?>"> Users</a></li>    
        <li class="active">Reset Password</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo Users_helper::makeTab($id, 'password'); ?>
    <div class="panel panel-default">
<!--  <div class="panel-heading">Reset User Password</div>-->
  <div class="panel-body"><form name="updatePassword" id="update_password" role="form" method="POST">
            <input name="user_id" value="<?php echo $id; ?>" type="hidden" />
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-2">
                        <div id="ajax_respond"></div>

                        <div class="form-group">
                            <div class="input-group">                               
                                <span class="input-group-addon"><i class="fa fa-user"></i> Username</span>                        
                                <input value="<?php echo $email; ?>" type="text" readonly="readonly"  class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">New Password<sup>*</sup></span>
                                <input type="password" minlength="8" maxlength="20" required="" name="new_pass" id="new_pass" class="form-control pass_field"/>
                                <span class="input-group-addon">Need  8~20 Characters</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">Confirm Password<sup>*</sup></span>
                                <input type="password" minlength="8" maxlength="20" required="" name="con_pass" id="con_pass" class="form-control pass_field"/>                         
                                <span class="input-group-addon">Need 8~20 Characters</span>
                            </div>
                        </div>
                        <p id="pass_length_err"></p>
                        <blockquote>
                            <p class="text-red">
                                <em>
                                The password must be between 8 ~ 20 characters. 
                                And must have letters, numbers, and special characters.
                                </em>
                            </p>
                        </blockquote>

                    
                    <div class="box-footer with-border">
                        <button class="btn btn-warning" id="show-pass" type="button" >
                            <i class="fa fa-eye-slash" ></i> 
                            <span>Show Pass</span>
                        </button>
                        
                        <button class="btn btn-primary emform" onclick="password_change();" type="button" >
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
<?php  load_module_asset('users', 'js'); ?>
