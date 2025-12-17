<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Admin Report  <small>Recruitment Manager</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Doctor</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Recruitment Manager Summery</div>
  <div class="panel-body"><div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th> 
                            <th width="200">Job Title</th>
                            <th width="200">Post By </th>
                            <th>Qty</th>
                            <th>Stage/Status</th>
                            <th width="250" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($report as $short) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>  
                                <td><?php echo $short->post; ?></td>                                
                                <td><?php echo $short->first_name.' '.$short->last_name; ?></td>                                
                                <td><?php echo $short->total_candidate; ?></td>          
                                <td><?php echo Tools::statusWiseCount($short->id);?></td>          
                                <td class="text-center">
                                    <?php
                                        echo anchor(
                                            site_url(Backend_URL . 'doctor/shortlist/' . $short->id), 
                                            'Show Details <i class="fa fa-long-arrow-right"></i>', 
                                            'class="btn btn-primary"'
                                        );                           
                                   
                                        echo anchor(
                                            site_url(Backend_URL . 'doctor/delete_post/' . $short->id), 
                                            '<i class="fa fa-times"></i> Delete', 
                                            'class="btn btn-danger" onClick="return confirm(\'Confirm Delete\')"'
                                        );                           
                                    ?>
                                    
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div></div>
</div>
 
</section>