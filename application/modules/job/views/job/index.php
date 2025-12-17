<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Job <small>Control
            panel</small> <?php echo anchor(site_url(Backend_URL . 'job/create'), ' + Add New', 'class="btn btn-default"'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Job</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'job'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Search</button>
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'job'); ?>" class="btn btn-default">Reset</a>
                            <?php } ?>                            
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th>Post Title</th>
                        <th>Job Type</th>
                        <th>Location</th>
                        <th>Deadline</th>
                        <th class="text-center">Vacancy</th>
                        <th class="text-right">Hourly Rate</th>
                        <th class="text-center">Hit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Featured</th>
                        <th width="150">Post Date</th>
                        <th width="150">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($jobs as $job) { ?>
                        <tr>
                            <td><?php echo sprintf('%02d', ++$start); ?></td>
                            <td><?php echo $job->post_title; ?></td>
                            <td><?php echo $job->job_type; ?></td>
                            <td><?php echo $job->location; ?></td>
                            <td><?php echo deadline($job->deadline); ?></td>
                            <td class="text-center"><?php echo sprintf('%02d', $job->vacancy); ?></td>
                            <td class="text-right"><?php echo (!empty($job->hourly_rate)) ? globalCurrencyFormat($job->hourly_rate) : null; ?></td>
                            <td class="text-center"><span class="badge"><?php echo $job->hit; ?></span></td>
                            <td class="text-center"><?php echo $job->status; ?></td>
                            <td class="text-center"><?php echo $job->featured; ?></td>
                            <td><?php echo globalDateTimeFormat($job->created_at); ?></td>
                            <td>
                                <?php
                                echo anchor(site_url(Backend_URL . 'job/read/' . $job->id), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-primary disabled"');
                                echo anchor(site_url(Backend_URL . 'job/update/' . $job->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Job: <?php echo $total_rows ?></span>

                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
    </div>
</section>