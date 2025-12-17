<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section class="content-header">
    <h1> Modules  <small>Control panel</small> <?php echo anchor(site_url(Backend_URL . 'module/create'), ' + Add New', 'class="btn btn-default"'); ?> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Modules</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Module List</div>
  <div class="panel-body">
      <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="40">#ID</th>                                                        
                            <th>Name</th>                                                        
                            <th>Remark</th>                                                        
                            <th width="100">Type</th>
                            <th width="120">Date</th>
                            <th class="text-center" width="100">Status</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($modules as $module) { ?>
                            <tr>
                                <td><?php echo $module->id; ?></td>                                                                
                                <td><?php echo $module->name; ?></td>                                                                
                                <td><?php echo $module->description; ?></td>                                                                
                                <td><?php echo $module->type; ?></td>
                                <td><?php echo globalDateFormat($module->added_date); ?></td>
                                <td class="text-center"><?php echo $module->status; ?></td>
                                <td class="text-center" >
                                    <?php                            
                                    echo anchor(site_url(Backend_URL . 'module/update/' . $module->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default"');
                                    echo anchor(site_url(Backend_URL . 'module/delete/' . $module->id), '<i class="fa fa-fw fa-times"></i>', 'class="btn btn-xs btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Record : <?php echo $total_rows ?></span>
                </div>
                
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>                
            </div>
  </div>
</div>
</section>