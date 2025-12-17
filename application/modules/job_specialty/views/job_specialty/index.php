<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Job Specialties  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Job_specialty</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        
        <div class="col-md-8 col-xs-12">
<div class="panel panel-default">
  <div class="panel-heading">List of job specialties</div>
  <div class="panel-body"><?php echo $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="text-center" width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($specialties as $specialty) { ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td><?php echo $specialty->name; ?></td>
                                        <td><?php echo globalDateTimeFormat($specialty->created_at); ?></td>
                                        <td><?php echo globalDateTimeFormat($specialty->updated_at); ?></td>
                                        <td class="text-center">
                                            <?php
                                            echo anchor(site_url(Backend_URL . 'job_specialty/update/' . $specialty->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                            echo anchor(site_url(Backend_URL . 'job_specialty/delete/' . $specialty->id), '<i class="fa fa-fw fa-trash"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer">
                        <div class="row">                
                            <div class="col-md-6">
                                <span class="btn btn-primary">Total: <?= $total_rows; ?></span>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php echo $pagination; ?>
                            </div>                
                        </div>
                    </div></div>
</div>
        </div> 
        
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default">
  <div class="panel-heading">Add New</div>
  <div class="panel-body">                    <div style="padding:0 15px;">
                        <?php echo form_open(Backend_URL . 'job_specialty/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" />
                        </div>                        
                        <button type="submit" class="btn btn-primary">Save New</button> 
                        <button type="reset" class="btn btn-default">Reset</button> 
                        <?php echo form_close(); ?>

                    </div>  </div>
</div>
        </div>
    </div>
</section>