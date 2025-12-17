<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Centre  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'centre') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>centre">Centre</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Centre</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name <sup>*</sup></label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $address; ?>" />
                        <?php echo form_error('address') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="country_id" class="col-sm-2 control-label">Country <sup>*</sup></label>
                    <div class="col-sm-10">                    
                        <select class="form-control" name="country_id" id="country_id">
                            <?php echo getDropDownCountries($country_id); ?>
                        </select>
                        <?php echo form_error('country_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>" /> 
                        <button type="submit" class="btn btn-primary"><?php echo $button; ?></button>
                        <a href="<?php echo site_url(Backend_URL . 'centre') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
                    
                </div>
            </div>
        </div>
    </div>
</section>