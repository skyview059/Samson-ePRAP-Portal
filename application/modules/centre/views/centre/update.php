<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users','css');?>
<section class="content-header">
    <h1><?php echo getExamCentreName($id); ?>  Centre<small><?php echo $button ?></small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>centre">Centre</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo centreTabs( $id, 'update'); ?>
    <br>
    <div class="panel panel-default">
  <div class="panel-heading">Update Centre</div>
  <div class="panel-body">
      <div class="row">
                <div class="col-md-8">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name <sup>*</sup></label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exam_id" class="col-sm-2 control-label">Exam Centre <sup>*</sup></label>
                    <div class="col-sm-10">                    
                        <select name="exam_id" class="form-control" id="exam_id">                            
                            <?php echo Tools::getExam($exam_id); ?>
                        </select>
                        <?php echo form_error('exam_id') ?>
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