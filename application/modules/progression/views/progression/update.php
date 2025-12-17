<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Progression<small><?php echo $button ?></small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>progression">Progression</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Update Progression<?php echo $this->session->flashdata('message'); ?></div>
  <div class="panel-body"><form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="category"  class="col-sm-2 control-label">Category :</label>
                    <div class="col-sm-10"  style="padding-top:8px;"><?php echo htmlRadio('category', $category, array('GMC' => 'GMC', 'GDC' => 'GDC', 'NMC' => 'NMC')); ?></div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Title :</label>
                    <div class="col-sm-5">                    
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>" />
                        <?php echo form_error('title') ?>
                    </div>
                </div>
                
                
                
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        
                        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                        <a href="<?php echo site_url(Backend_URL . 'progression') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form></div>
</div>
</section>