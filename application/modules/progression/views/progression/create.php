<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Progression  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'progression') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>progression">Progression</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Progression</h3>
        </div>

        <div class="box-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="category"  class="col-sm-2 control-label">Category :</label>
                <div class="col-sm-10"  style="padding-top:8px;"><?php echo htmlRadio('category', $category, array('GMC' => 'GMC', 'GDC' => 'GDC', 'NMC' => 'NMC')); ?></div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">Title :</label>
                <div class="col-sm-10">                    
                    <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>" />
                    <?php echo form_error('title') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="order_id" class="col-sm-2 control-label">Order Id :</label>
                <div class="col-sm-10">                    
                    <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Order Id" value="<?php echo $order_id; ?>" />
                    <?php echo form_error('order_id') ?>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                <a href="<?php echo site_url(Backend_URL . 'progression') ?>" class="btn btn-default">Cancel</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>