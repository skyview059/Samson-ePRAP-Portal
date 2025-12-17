<?php
defined('BASEPATH') OR exit('No direct script access allowed');

load_module_asset('users', 'css');
?>
<section class="content-header">    
    <h1>User Details <small>of</small> <?php echo "{$first_name} {$last_name}"; ?> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL . 'users' ?>"> User</a></li>    
        <li class="active">Update Profile</li>
    </ol>
</section>


<section class="content personaldevelopment studenttab">

               
    <?php echo Users_helper::makeTab($id, 'update'); ?>
    <div class="panel panel-default">
<!--  <div class="panel-heading">Update New User</div>-->
  <div class="panel-body"><form method="post" action="<?php echo $action; ?>" name="update"  class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />                        
            <div class="box-body">
                <div class="form-group">
                    <label for="first_name" class="col-sm-2">Full Name :</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">First Name</span>
                            <input type="text" value="<?php echo $first_name; ?>" class="form-control" name="first_name" id="first_name" placeholder="Enter First Part" />
                        </div>
                        <?php echo form_error('first_name') ?>                        
                    </div>

                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Last Name</span>
                            <input type="text" value="<?php echo $last_name; ?>" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Part" />
                        </div>
                        <?php echo form_error('last_name') ?>                        
                    </div> 
                </div>

                <div class="form-group">
                    <label for="role_id" class="col-sm-2">Role :<sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="role_id" class="form-control" id="role_id">
                            <?php echo Users_helper::getDropDownRoleName($role_id); ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="show_occupation" <?php echo ($role_id==7) ? 'style="display:block;"' : 'style="display:none;"';?>>
                    <label for="allowed" class="col-sm-2">Allow CV Search for :<sup>*</sup></label>
                    <div class="col-sm-8" style="padding-top: 8px;">                        
                        <?php echo getMultipleDropDownOccuptions($allowed); ?>
                        <?php echo form_error('allowed') ?> 
                    </div>                                              
                </div>

                <div class="form-group" >
                    <label for="your_email" class="col-sm-2">Email :<sup>*</sup></label>
                    <div class="col-sm-8"> 
                        <div class="input-group">
                            <input autocomplete="off" type="text" value="<?php echo $your_email; ?>" class="form-control" name="your_email" id="your_email" placeholder="Valid Email Address" />
                            <span class="input-group-addon">as User name</span>
                        </div>

                        <?php echo form_error('your_email') ?>
                    </div>   
                </div>


                <div class="form-group">
                    <label for="contact" class="col-sm-2">Mobile Number :<sup>*</sup></label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="mobile[code]" class="form-control select2">
                                    <?php echo getPhoneCode($code); ?>
                                </select>
                            </div>
                            <div class="col-md-4 no-padding">
                                <input type="tel" maxlength="11" onKeyPress="return DigitOnly(event);"
                                       name="mobile[number]" value="<?php echo $number; ?>" id="contact" placeholder="07766554433"
                                       class="form-control"/>
                            </div>                                
                        </div>
                        <?php echo form_error('mobile[number]') ?>                        
                    </div> 
                </div>

                <div class="form-group">
                    <label for="add_line1" class="col-sm-2">Address Line1 :</label>
                    <div class="col-sm-8"><input type="text" value="<?php echo $add_line1; ?>" class="form-control" name="add_line1" id="add_line1" placeholder="Address Line1" />
                    </div>   
                </div>


                <div class="form-group">
                    <label for="add_line2" class="col-sm-2">Address Line2 :</label>
                    <div class="col-sm-8"><input type="text" value="<?php echo $add_line2; ?>" class="form-control" name="add_line2" id="add_line2" placeholder="Address Line2" />
                    </div>   
                </div>


                <div class="form-group">
                    <label for="city" class="col-sm-2">City :</label>
                    <div class="col-sm-8"> <input type="text" value="<?php echo $city; ?>" class="form-control" name="city" id="city" placeholder="City" />
                    </div>  
                </div>
                <div class="form-group">
                    <label for="state" class="col-sm-2">County :</label>
                    <div class="col-sm-8"><input type="text"  value="<?php echo $state; ?>" class="form-control" name="state" id="state" placeholder="County" />
                    </div>  
                </div>
                <div class="form-group">
                    <label for="postcode" class="col-sm-2">Postcode :</label>
                    <div class="col-sm-8"><input type="text" value="<?php echo $postcode; ?>" class="form-control" name="postcode" id="postcode" placeholder="Postcode"  />
                    </div>  
                </div>
                <div class="form-group">
                    <label for="country_id" class="col-sm-2">Country :</label>
                    <div class="col-sm-8">
                        <select name="country_id" class="form-control" id="country_id">                                
                            <?php echo getDropDownCountries($country_id); ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Status :</label>
                    <div class="col-sm-5" style="padding-top: 8px;">
                        <?php
                        echo htmlRadio('status', $status, [
                            'Active' => 'Active',
                            'Inactive' => 'Inactive',
                            'Pending' => 'Pending']);
                        ?>                        
                        <?php echo form_error('status') ?>
                    </div>  
                </div>



            </div>
            <div class="box-footer text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" ></i> 
                    Save Changes                     
                </button>
            </div>
        </form>  </div>
</div>

</section>
<script type="text/javascript">

    $(document).ready(function () {
        $( "#role_id" ).change(function() {
           var role_id = $('#role_id').val();
           if(role_id==7){
               $( "#show_occupation" ).show("slow");
           }else{
              $( "#show_occupation" ).hide("slow"); 
           }
        });
    });
</script>