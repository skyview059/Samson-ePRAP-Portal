<?php load_module_asset('message', 'css'); ?>


<div class="row">
    <div class="col-md-3">
        <?php $this->load->view('frontend/student_message/left_sidebar'); ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading"><?php echo $subject; ?></div>
            <div class="panel-body">
                <div>
                    <div class="row replies">
                        <div class="col-md-3 who">
                            <h3><?php echo ($login_id == $from_student_id) ? 'You' : $from_student; ?></h3>
                            <small><em><?php echo globalDateTimeFormat($open_at); ?></em></small>
                        </div>
                        <div class="col-md-9">
                            <p><?php echo $body; ?></p>
                        </div>
                    </div>

                    <?php foreach ($replys as $reply) { ?>
                        <div class="row replies">
                            <div class="col-md-3 who">
                                <h3><?= ($login_id == $reply->from_student_id) ? 'You' : $reply->from_student; ?></h3>
                                <small><em><?php echo globalDateTimeFormat($reply->open_at); ?></em></small>
                            </div>
                            <div class="col-md-9">
                                <p><?php echo $reply->body; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="box-footer">
                    <a href="<?php echo site_url('student-messages') ?>" class="btn btn-default">
                        <i class="fa fa-long-arrow-left"></i>
                        Back to Message
                    </a>
                    <span class="btn btn-primary open_reply_box">
                        <i class="fa fa-reply"></i>
                        Reply Message
                    </span>
                </div>
                <?php $this->load->view('frontend/student_message/message_reply'); ?>
            </div>
        </div>
    </div>
</div>


