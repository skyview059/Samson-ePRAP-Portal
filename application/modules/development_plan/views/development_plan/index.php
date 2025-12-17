<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>Individual Learning Plan <small>Control panel</small>
        <?php echo anchor(site_url(Backend_URL . 'development_plan/create'), ' + Add New', 'class="btn btn-default"'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Individual Learning Plan</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'development_plan'); ?>" class="form-inline"
                      method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'development_plan'); ?>"
                                   class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="50">S/L</th>
                            <th>Student</th>
                            <th>Date of Achievement</th>
                            <th class="text-center">Plan</th>
                            <th>Review Date</th>
                            <th width="150">Created on</th>
                            <th width="150">Updated on</th>
                            <th width="90" class="text-center">View Plan</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($dev_plans as $plan) { ?>
                        <tr>
                            <td><?php echo ++$start; ?></td>
                            <td><a href="<?php echo site_url(Backend_URL.'student/read/'.$plan->student_id); ?>"><?php echo $plan->student_name; ?></a></td>
                            <td><?php echo globalDateFormat($plan->date_of_achievement); ?></td>                            
                            <td class="text-center"><?php echo planQty($plan->student_id); ?></td>
                            <td><?php echo globalDateFormat($plan->review_date); ?></td>
                            <td><?php echo globalDateTimeFormat($plan->created_at); ?></td>
                            <td><?php echo globalDateTimeFormat($plan->updated_at); ?></td>
                            <td class="text-center">
                                <?php
                                    echo anchor(
                                        site_url(Backend_URL . 'development_plan/details/' . $plan->student_id), 
                                        '<i class="fa fa-fw fa-external-link"></i> View', 
                                        'class="btn btn-xs btn-primary"'
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span>Total Development Plan: <?php echo $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>

        </div>
    </div>
</section>