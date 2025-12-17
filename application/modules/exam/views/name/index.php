<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Mock Exam Name  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
        <li class="active">Name</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-8 col-xs-12">

            <div class="box box-primary">            
                <div class="box-header with-border">                                   
                    <h3 class="box-title">Exam Name</h3>
                </div>

                <div class="box-body">
                    <?php echo $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th>Name</th>
                                    <th width="80" class="text-center">Centre</th>
                                    <th width="100" class="text-center">Exams</th>
                                    <th width="120" class="text-center">Created on</th>
                                    <th width="120" class="text-center">Updated on</th>
                                    <th class="text-center" width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($names as $name) { 
                                    $scheduled = Tools::getSchedules($name->id);
                                    ?>
                                    <tr>
                                        <td><?php echo ++$start; ?></td>
                                        <td><?php echo $name->name; ?></td>
                                        <td class="text-center">
                                            <a href="admin/centre?id=<?php echo $name->id; ?>">
                                                <?php echo Tools::getCentres($name->id); ?>
                                                &nbsp;
                                                <i class="fa fa-external-link-square"></i>
                                            </a>                                            
                                        </td>
                                        <td class="text-center">
                                            <a href="admin/exam?id=<?php echo $name->id; ?>">
                                                <?php echo $scheduled; ?>
                                                &nbsp;
                                                <i class="fa fa-external-link-square"></i>
                                            </a>
                                            
                                        </td>
                                        <td class="text-center"><?php echo globalDateFormat($name->created_at); ?></td>
                                        <td class="text-center"><?php echo globalDateFormat($name->updated_at); ?></td>
                                        <td class="text-center">
                                            <?php
                                            echo anchor(
                                                    site_url(Backend_URL . 'exam/name/update/' . $name->id), 
                                                    '<i class="fa fa-fw fa-edit"></i>', 
                                                    'class="btn btn-xs btn-default" title="Edit"'
                                                );
                                            
                                            if($scheduled){
                                                echo '<span class="btn btn-xs btn-danger disabled"><i class="fa fa-fw fa-lock"></i></span>';
                                            } else {
                                                echo anchor(
                                                        site_url(Backend_URL . 'exam/name/delete/' . $name->id), 
                                                        '<i class="fa fa-fw fa-times"></i>', 
                                                        'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"'
                                                    );
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">                
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Exam: <?php echo $total_rows ?></span>

                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination ?>
                        </div>                
                    </div>
                </div>
            </div>
        </div>   
        
        <div class="col-md-4 col-xs-12">            
            <div class="box box-primary">                
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </h3>
                </div>

                <div class="box-body">
                    <div style="padding:0 15px;">
                        <?php echo form_open(Backend_URL . 'exam/name/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save New</button> 
                                <button type="reset" class="btn btn-default">Reset</button> 
                            </div>
                        <?php echo form_close(); ?>
                    </div>                    
                </div>    
            </div>
        </div>

    </div>
</section>