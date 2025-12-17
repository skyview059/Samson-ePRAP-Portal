<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Domain  <small>Management panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>personal_dev_plan">Personal Development Plan</a></li>
        <li class="active">Domain</li>
    </ol>
</section>

<style type="text/css"> table#ordering tr { cursor: move; } </style>

<section class="content">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-default">
  <div class="panel-heading">List of Domains</div>
  <div class="panel-body"> <?php echo $this->session->flashdata('message'); ?>
                    <div id="respond"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed" id="ordering">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th>Domain</th>
                                    <th class="text-center">Used</th>
                                    <th class="text-center" width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($domains as $domain) { 
                                    $used = findUses($domain->id, $find); ?>
                                    <tr id="item-<?php echo $domain->id; ?>">
                                        <td><i class="fa fa-arrows-v"></i> <?php echo ++$start; ?></td>
                                        <td><?php echo $domain->domain; ?></td>                                        
                                        <td class="text-center"><?php echo $used; ?></td>                                        
                                        <td class="text-center">
                                            <?php
                                            echo anchor(
                                                    site_url(Backend_URL . 'personal_dev_plan/domain/update/' . $domain->id),
                                                '<i class="fa fa-fw fa-edit"></i>', 
                                                'class="btn btn-xs btn-default" title="Edit"'
                                            );
                                            if( !$used ){
                                                echo anchor(
                                                    site_url(Backend_URL . 'personal_dev_plan/domain/delete/' . $domain->id), 
                                                    '<i class="fa fa-fw fa-trash"></i>', 
                                                    'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"'
                                                );
                                            } else {
                                                echo ' <span class="btn btn-xs disabled btn-danger"><i class="fa fa-fw fa-trash"></i></span>';
                                            }
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
                            <span class="btn btn-primary">Total Domain: <?php echo $total_rows ?></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination ?>
                        </div>
                    </div>        
                </div></div>
</div>
         
                   

        </div> 


        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default">
  <div class="panel-heading">Add New Domain</div>
  <div class="panel-body"> <div style="padding:0 15px;">
                        <?php echo form_open(Backend_URL . 'personal_dev_plan/domain/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
                            <input type="hidden" name="order_id" value="<?= $order_id; ?>" />
                            <div class="form-group">
                                <label for="domain">Domain</label>
                                <input type="text" class="form-control" name="domain" id="domain" placeholder="Domain" />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save New</button> 
                                <button type="reset" class="btn btn-default">Clear</button> 
                            </div>
                        <?php echo form_close(); ?>

                    </div>  </div>
</div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ordering tbody").sortable({
            axis: "y",
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    url: 'admin/personal_dev_plan/domain/save_order',
                    beforeSend: function () {
                        $('#respond').html( '<p class="ajax_processing">Please Wait....</p>' ).css('display','block');
                    },
                    success: function (respond) {
                        $('#respond').html( respond.Msg );
                        setTimeout(function(){  $('#respond').slideUp('slow'); }, 2000);
                    }
                });
            }
        });
    });
</script>