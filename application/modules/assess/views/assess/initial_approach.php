<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Examine<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>examine">Examine</a></li>
        <li class="active">Initial Approach</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Initial Approach / <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-9 col-md-offset-3">
                    <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                        <div class="form-group">
                            <label for="patient_name" class="col-sm-3 control-label">Identifies Patient :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('patient_name', $patient_name, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('patient_name') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="greet_the_patient" class="col-sm-3 control-label">Greet the patient :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('greet_the_patient', $greet_the_patient, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('greet_the_patient') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="introduces_himself" class="col-sm-3 control-label">Introduces himself :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('introduces_himself', $introduces_himself, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('introduces_himself') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state_the_role" class="col-sm-3 control-label">State the role :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('state_the_role', $state_the_role, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('state_the_role') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name_preference" class="col-sm-3 control-label">Checks patient’s name preference :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('name_preference', $name_preference, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('name_preference') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="starts_station_well" class="col-sm-3 control-label">Starts station well :</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('starts_station_well', $starts_station_well, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                <div class="clearfix"></div>
                                <?php echo form_error('starts_station_well') ?>
                            </div>
                        </div>


                        <div class="form-group" style="margin-top: 25px;">
                            <div class="col-md-offset-3">
                                <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save &amp; Continue <i class="fa fa-long-arrow-right"></i></button>                            
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>