<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Sms  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Sms</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">                                   
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'sms'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'sms'); ?>" class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Phone</th>
                            <th>P.N. Length</th>
                            <th>Body</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Qty</th>                            
                            <th class="text-center">Status</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                            <th class="text-center" width="50">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($smss as $sms) { ?>
                            <tr>
                                <td><?php echo ++$start ?></td>
                                <td><?php echo $sms->phone ?></td>
                                <td><?php echo phoneLength($sms->phone); ?></td>
                                <td><?php echo $sms->body ?></td>
                                <td class="text-center"><?php echo $sms->type ?></td>
                                <td class="text-center"><?php echo $sms->qty ?></td>
                                <td class="text-center"><?php echo $sms->status ?></td>
                                <td>
                                    <button class="btn btn-default btn-xs open_popup" 
                                          data-note='<?= viewLogDetails($sms->respond); ?>'>
                                        <i class="fa fa-file-text-o"></i> 
                                        Show
                                    </button>
                                </td>                                
                                <td><?php echo globalDateTimeFormat($sms->timestamp); ?></td>
                                <td class="text-center">
                                    <?php                                    
                                        echo anchor(
                                            site_url(Backend_URL . 'sms/delete/' . $sms->id), 
                                            '<i class="fa fa-fw fa-times"></i>', 
                                            'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"'
                                        );
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            
        </div>
        <div class="box-footer text-center">
            <?php echo $pagination; ?>
        </div>
    </div>

</section>
<div class="modal fade" id="note_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">BulkSMS Respond Details</h4>
            </div>
            <div class="modal-body" id="show_note"></div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.open_popup').on('click',function(){
        var note = $(this).attr('data-note');
        $('#show_note').html( note );        
        $('#note_popup').modal({show: 'false'});      
    });
</script>    