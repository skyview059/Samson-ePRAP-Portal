<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('student', 'css'); ?>
<?php load_module_asset('message', 'css'); ?>
<section class="content-header">
    <h1>Messages <small>of <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">student</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'message'); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Student Details View <button  type="button" class="btn btn-primary pull-right" data-toggle="modal"
                                                                data-target="#modal-compose-message">
                <i class="fa fa-envelope"></i> 
                Send Message
                <i class="fa fa-copy"></i>
            </button>
               </h3>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped no-margin">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>                                
                            <th>Subject</th>                                
                            <th class="text-right" width='120'>Sent At</th>
                            <th class="text-center" width='90'>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($mails as $m) {
                            $link = site_url(Backend_URL . 'message/view_modal/' . $m->id);
                            ?>
                            <tr class="<?php echo $m->status; ?>">
                                <td><?= $sl++; ?></td>                                    
                                <td><a href="<?= $link; ?>" class="read-more open_modal">
    <?php echo getShortContent($m->subject, 30); ?>
                                    </a>
                                </td>                                    
                                <td class="text-right"><?php echo dateTimeDifference($m->open_at); ?></td>
                                <td class="text-center">
                                    <a href="<?= $link; ?>" class="btn btn-primary btn-sm open_modal">
                                        <i class="fa fa-search"></i>
                                        Open                                       
                                    </a>
                                </td>
                            </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">            
<?php echo $pagination; ?>
            </div>
        </div>
    </div>

</section>
<?php $this->load->view('message/message/new_message_modal', ['id' => $id, 'subject' => 'subject']); ?>
<div class="modal fade" id="read_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">                            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Manage Scenarios</h4>
            </div>

            <div class="modal-body" id="message_content"></div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.open_modal').on('click', function () {
        var href = $(this).attr('href');
        $('#read_popup').modal({
            show: 'false',
            backdrop: 'static'
        });

        $.ajax({
            url: href,
            type: "POST",
            dataType: "html",
            beforeSend: function () {
                $('#message_content').html('<p class="ajax_processing">Loading...</p>');
            },
            success: function (msg) {
                $('#message_content').html(msg);
            }
        });
        return false;
    });
</script>

