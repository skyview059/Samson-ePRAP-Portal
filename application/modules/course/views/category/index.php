<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Category  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?= Backend_URL ?>course">Course</a></li>
        <li class="active">Category</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-8 col-xs-12">

            <div class="box box-primary">            
                <div class="box-header with-border">                                   
                    <h3 class="box-title">Course Categories</h3>
                </div>

                <div class="box-body">
                    <?= $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th width="220">Name</th>
                                    <th>Description</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center" width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($categorys as $category) { ?>
                                    <tr>
                                        <td><?= ++$start ?></td>
                                        <td><?= $category->name; ?></td>
                                        <td><?= $category->description; ?></td>
                                        <td class="text-center"><?= $category->course_qty; ?></td>
                                        <td class="text-center">
                                            <?php
                                            echo anchor(site_url(Backend_URL . 'course/category/update/' . $category->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                            echo anchor(site_url(Backend_URL . 'course/category/delete/' . $category->id), '<i class="fa fa-fw fa-trash"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">                
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Course: <?= $total_rows; ?></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <?= $pagination ?>
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
                        <?= form_open(Backend_URL . 'course/category/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" rows="5" name="description" id="description" placeholder="Description"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save New</button> 
                            <button type="reset" class="btn btn-default">Reset</button> 
                        </div>
                        <?= form_close(); ?>
                    </div>                    
                </div>    
            </div>
        </div>

    </div>
</section>