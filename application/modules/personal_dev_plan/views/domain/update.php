<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Domain<small><?php echo $button ?></small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>personal_dev_plan">Personal Development Plan</a></li>
        <li><a href="<?php echo Backend_URL ?>personal_dev_plan/domain">Domain</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">Update Domain<br><?php echo $this->session->flashdata('message'); ?></div>
        <div class="panel-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="domain" class="col-sm-2 control-label">Domain :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="domain" id="domain" placeholder="Domain" value="<?php echo $domain; ?>" />
                        <?php echo form_error('domain') ?>
                    </div>
                </div>    
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                        <a href="<?php echo site_url(Backend_URL . 'personal_dev_plan/domain') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
</div>
</section>