<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Name  <small><?php echo $button ?></small> <a href="<?php echo site_url( Backend_URL .'exam/name') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
	<li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
	<li><a href="<?php echo Backend_URL ?>exam/name">Name</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Name</h3>
        </div>
        
        <div class="box-body">
        <?php echo form_open( $action, array('class'=>'form-horizontal', 'method'=>'post')); ?>
	    <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>
	    <div class="form-group">
                    <label for="created_at" class="col-sm-2 control-label">Created At :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="created_at" id="created_at" placeholder="Created At" value="<?php echo $created_at; ?>" />
                        <?php echo form_error('created_at') ?>
                    </div>
                </div>
	    <div class="form-group">
                    <label for="updated_at" class="col-sm-2 control-label">Updated At :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="updated_at" id="updated_at" placeholder="Updated At" value="<?php echo $updated_at; ?>" />
                        <?php echo form_error('updated_at') ?>
                    </div>
                </div>
	<div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
	    <input type="hidden" name="id" value="<?php echo $id; ?>" />
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url( Backend_URL .'exam/name') ?>" class="btn btn-default">Cancel</a>
	</div>
	<?php echo form_close(); ?>
	</div>
</div>
</section>