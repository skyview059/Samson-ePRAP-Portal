<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Progression  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Progression</li>
    </ol>
</section>
<style type="text/css">
    tbody tr {
        cursor: move;
    }
</style>    
<section class="content">

    <div class="row">

        <div class="col-md-8 col-xs-12">

            <div class="box box-primary">            
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-5 col-md-offset-7 text-right">
                            <form action="<?php echo site_url(Backend_URL . 'progression'); ?>" class="form-inline" method="get">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                    <span class="input-group-btn">
                                        <?php if ($q <> '') { ?>
                                            <a href="<?php echo site_url(Backend_URL . 'progression'); ?>" class="btn btn-default">Reset</a>
                                        <?php } ?>
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <?php echo $this->session->flashdata('message'); ?>
                    <div id="respond"></div>
                    <div class="table-responsive">
                        <table id="ordering" class="table table-bordered table-striped table-condensed no-margin">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th width="80">Category</th>
                                    <th>Title</th>
                                    <th width="70" class="text-center">Used</th>
                                    <th class="text-center" width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                    $old = '';
                                    foreach ($progressions as $progression) {
                                    $new = $progression->category;
                                    $qty = findUsed($progression->id);
                                    ?>
                                    <tr id="item-<?php echo $progression->id; ?>">
                                        <td><?php echo ++$start; ?></td>
                                        <td><?php echo hideRepeatedName($new,$old); ?></td>
                                        <td><?php echo $progression->title; ?></td>
                                        <td class="text-center"><?php echo ($qty) ? $qty : '--'; ?></td>
                                        <td class="text-center">
                                            <?php
                                            echo anchor(site_url(Backend_URL . 'progression/update/' . $progression->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                            if($qty){
                                                echo ' <span class="btn btn-xs btn-danger disabled"><i class="fa fa-lock"></i></span>';
                                            } else {
                                                echo anchor(site_url(Backend_URL . 'progression/delete/' . $progression->id), '<i class="fa fa-fw fa-trash"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php 
                                $old = $progression->category;
                                
                                    } ?>
                            </tbody>
                        </table>
                    </div>                  
                </div>

                <div class="box-footer">
                    <div class="row">                
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Progression: <?php echo $total_rows ?></span>

                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination ?>
                        </div>                
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4 col-xs-12"> 
            <div class="panel panel-default">
  <div class="panel-heading">Add New</div>
  <div class="panel-body">
  <?php echo form_open(Backend_URL . 'progression/create_action', array('class' => 'form-horizontal', 'method' => 'post')); ?>
  
                    <div style="padding:0 15px;">

                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                        <div class="form-group" style="padding-top:8px;">
                            <label for="category">Category</label><br/>
                            <?php echo htmlRadio('category', 'GMC', array('GMC' => 'GMC', 'GDC' => 'GDC', 'NMC' => 'NMC')); ?>                                          
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" />
                        </div>                                                
                    </div>                    
 
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save New</button> 
                    <button type="reset" class="btn btn-default">Reset</button>                        
                </div>
                <?php echo form_close(); ?>
  </div>
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
                    url: 'admin/progression/save_order',
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