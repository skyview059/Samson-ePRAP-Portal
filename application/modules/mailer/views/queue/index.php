<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Queue  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>mailer">Mailer</a></li>
        <li class="active">Queue</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-6"><h1 class="box-title">Mail Queue</h1></div>
                <div class="col-md-6">
                    <a href="<?= site_url('mailer/process'); ?>" class="btn btn-lg btn-primary">
                        Start Email Process
                        <i class="fa fa-play"></i>
                    </a>
                </div>
            </div>
            
        </div>

        <div class="box-body"> 
            <?= $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Send To</th>
                            <th>Subject</th>                            
                            <th>Created On</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th class="text-center" width="90">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($queues as $queue) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $queue->send_to; ?></td>
                                <td><?php echo $queue->subject; ?></td>
                                <td><?php echo globalDateTimeFormat($queue->created_on); ?></td>
                                <td><?php echo $queue->status; ?></td>
                                <td><?php echo globalDateTimeFormat($queue->sent_at); ?></td>
                                <td class="text-center">
                                    <?php
                                        echo anchor(
                                            site_url(Backend_URL . 'mailer/queue/popup/' . $queue->id), 
                                            '<i class="fa fa-fw fa-bars"></i>', 
                                            'class="btn btn-xs btn-default" title="Popup"'
                                        );                                    
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            
        </div>
        <div class="box-footer">
            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Queue: <?= $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
    </div>

</section>