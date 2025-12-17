<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>

<section class="content-header">
    <h1>My Profile <small>Update</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-user"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL . 'profile' ?>"> Profile</a></li>
        <li class="active">Update Profile</li>
    </ol>
</section>

<section class="content personaldevelopment">

    <form class="form-horizontal" action="<?php echo base_url(Backend_URL . 'profile/update_action'); ?>"
          enctype="multipart/form-data" role="form" id="personalForm" method="post">
        <?php echo profileTab('profile'); ?>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">Update Profile Information</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-6 no-padding">
                        <label for="first_name" class="col-sm-4">Full Name</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">First Part</span>
                                <input type="text" class="form-control" placeholder="First Name"
                                       name="first_name" value="<?php echo $first_name; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Last Part</span>
                                <input type="text" class="form-control" placeholder="Last Name"
                                       name="last_name" value="<?php echo $last_name; ?>"/>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2">Email Address</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" readonly value="<?php echo $your_email; ?>">
                            <span class="input-group-addon text-bold">Readonly View</span>
                        </div>
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
                                       name="mobile[number]" value="<?php echo $number; ?>" id="contact"
                                       placeholder="07766554433"
                                       class="form-control"/>
                            </div>
                        </div>
                        <?php echo form_error('mobile[number]') ?>
                    </div>
                </div>


                <div class="form-group">
                    <label for="contact" class="col-sm-2">Address Line 1</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="add_line1" placeholder="Address Line 1"
                               name="add_line1" value="<?php echo $add_line1; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact" class="col-sm-2">Address Line 2</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="add_line2" placeholder="Address Line 2"
                               name="add_line2" value="<?php echo $add_line2; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2">City</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="city" placeholder="City"
                               name="city" value="<?php echo $city; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact" class="col-sm-2">Region/State</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="state" placeholder="Region/State"
                               name="state" value="<?php echo $state; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact" class="col-sm-2">Postcode</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="postcode" placeholder="Postcode"
                               name="postcode" value="<?php echo $postcode; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact" class="col-sm-2">Country</label>
                    <div class="col-md-8">
                        <select class="form-control" name="country_id">
                            <?php echo getDropDownCountries($country_id); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</section>