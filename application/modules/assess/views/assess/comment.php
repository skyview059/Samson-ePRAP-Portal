<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Assessor <small>Comment</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Assessor Comment</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Assessor Comment / <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php
                                $options = array(
                                    'name' => 'comments',
                                    'id' => 'comments',
                                    'class' => 'form-control',
                                    // 'readonly' => 'readonly',
                                    'rows' => '10',
                                    'cols' => '50',
                                    'value' => $comments
                                );
                                echo form_textarea($options);
                                ?>
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
