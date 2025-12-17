<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Examine<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>examine">Examine</a></li>
        <li class="active">Overall Judgment</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Overall Judgment</h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Overall Judgment</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label>
                                        <?php echo htmlRadio('overall_judgment', $overall_judgment, array('Fail' => 'Fail'), 'class="icheck-radio"'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <?php echo htmlRadio('overall_judgment', $overall_judgment, array('Borderline' => 'Borderline'), 'class="icheck-radio"'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <?php echo htmlRadio('overall_judgment', $overall_judgment, array('Pass' => 'Pass'), 'class="icheck-radio"'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <?php echo htmlRadio('overall_judgment', $overall_judgment, array('Very Good' => 'Very Good'), 'class="icheck-radio"'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <?php echo htmlRadio('overall_judgment', $overall_judgment, array('Excellent' => 'Excellent'), 'class="icheck-radio"'); ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                                <?php echo form_error('overall_judgment') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save &amp; Continue <i class="fa fa-long-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
