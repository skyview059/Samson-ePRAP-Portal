<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Practice Package  <small>Control panel</small>
        <a href="<?php echo site_url(Backend_URL . 'course/practice_package/create'); ?>"
           class="btn btn-primary"><i class="fa fa-plus"></i> Add Practice Package</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Practice</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">
            <form action="<?php echo site_url(Backend_URL . 'course/practice_package'); ?>" class="form-inline" method="get">
                
                <div class="col-md-2 text-right col-md-offset-5">
                    <div class="input-group">
                        <select class="form-control" name="exam_id" id="exam_id">
                            <?php echo getExamNameDropDown($exam_id, 'Any Practice'); ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">                        
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    <a href="<?php echo site_url(Backend_URL . 'course/practice_package'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="box-body">            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Practice</th>
                            <th>Title</th>
<!--                            <th class="text-center">Booked</th>                            -->
                            <th class="text-right">Price</th>
                            <th class="text-center">Duration</th>
                            <th class="text-center">Scenario Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($packages as $package) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $package->practice_name; ?></td>
                                <td><?php echo $package->title; ?></td>
<!--                                <td class="text-center">--><?php //echo intOnly($package->booked); ?><!--</td>-->
                                <td class="text-right"><?php echo GBP($package->price); ?></td>
                                <td class="text-center"><?php echo $package->duration; ?></td>
                                <td class="text-center"><?php echo getPackageScenarioTypeName($package->scenario_type); ?></td>
                                <td class="text-center"><?php echo isActive($package->status); ?></td>
                                <td class="text-center">
                                    <?php
                                    echo anchor(site_url(Backend_URL . 'course/practice_package/read/' . $package->id), '<i class="fa fa-fw fa-bars"></i> Preview', 'class="btn btn-xs btn-success" title="Details"');
                                    echo anchor(site_url(Backend_URL . 'course/practice_package/update/' . $package->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-default" title="Edit"');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="box-footer">
            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Course: <?= $total_rows; ?></span>                    
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
    </div>
</section>