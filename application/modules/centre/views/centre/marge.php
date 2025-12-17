<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users','css');?>
<section class="content-header">
    <h1><?php echo getExamCentreName( $mock_id ); ?>  Centre<small>Marging</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>centre">Centre</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo centreTabs( $mock_id, 'marge'); ?>
    <br>
    <div class="panel panel-default">
  <div class="panel-heading">Update Centre</div>
  <div class="panel-body">
      <div class="row">
                <div class="col-md-8">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                
                <input type="hidden" name="old_centre_id" value="<?php echo $old_id; ?>" />
                <input type="hidden" name="mock_id" value="<?php echo $mock_id; ?>" />
                
                <div class="form-group">
                    <label class="col-sm-3">Delete Exam Centre <sup>*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" value="<?= getExamCentreName( $old_id );?>" class="form-control" readonly="readonly" />
                        <?php echo form_error('exam_centre_id') ?>
                    </div>
                </div> 
                
                <div class="form-group">
                    <label class="col-sm-3">To Exam Centre <sup>*</sup></label>
                    <div class="col-sm-8">
                        <select name="new_centre_id" class="form-control">
                            <option value="">-- Select Exam Centre --</option>
                            <?php echo ExamCenterDroDown(0, 0); ?>
                        </select>
                        <?php echo form_error('exam_centre_id') ?>
                    </div>
                </div> 
                
                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">                        
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-random"></i>
                            Marge Now
                        </button>
                        <a href="<?php echo site_url(Backend_URL . 'centre?id=' . $mock_id ) ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
                     </div>
            </div>
  </div>
</div>

</section>