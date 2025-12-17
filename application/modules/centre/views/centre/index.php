<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> 
        <?php echo getExamName($id); ?>  Exam Centre <small>Control panel</small> 
        <?php echo anchor(site_url(Backend_URL . "centre/create?id={$id}"), ' + Add New', 'class="btn btn-default"'); ?> 
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Centre</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
    <div class="panel-heading">List of Centre</div>
    <div class="panel-body">
        <?php if($centres){?>
        
            <div class="table-responsive">                
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th width="200">Centre Name</th>                            
                            <th>Address</th>
                            <th class="text-center" width="100">Student</th>
                            <th width="200">Country</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($centres as $centre) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $centre->name; ?></td>                                
                                <td><?php echo $centre->address; ?></td>
                                <td class="text-center"><?= $centre->students; ?></td>
                                <td><?php echo $centre->country; ?></td>
                                <td class="text-center">
                                    <?php
                                    echo anchor(site_url(Backend_URL . "centre/update/{$centre->id}?id={$id}"), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                    echo anchor(site_url(Backend_URL . "centre/delete/{$centre->id}?id={$id}" ), '<i class="fa fa-fw fa-times"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                    echo anchor(site_url(Backend_URL . "centre/marge/{$centre->id}?id={$id}" ), '<i class="fa fa-fw fa-random"></i>', 'class="btn btn-xs btn-danger" title="Marging"');
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
                    <span class="btn btn-primary">Total Centre: <?php echo $total_rows ?></span>

                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>                
            </div>
        </div>
        <?php } else { ?>
            <div class="box-body">
                <p class="ajax_notice"> No Exam found at this Centre.</p>
            </div>
        <?php } ?>
    </div>
  </div>
         

        

</section>