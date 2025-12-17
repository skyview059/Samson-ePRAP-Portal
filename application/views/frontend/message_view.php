<?php load_module_asset('message', 'css'); ?>

<div class="panel panel-default">
  <div class="panel-heading">Message View</div>
  <div class="panel-body">
      <table class="table table-striped">
            <tr>
                <td width="100">Open by</td>
                <td width="5">:</td>
                <td><?php echo "{$sender} ({$opened_by})"; ?></td>
            </tr>
            <tr><td>For </td><td>:</td><td><?php echo $receiver; ?></td></tr>
            <tr><td>Subject</td><td>:</td><td><?php echo $subject; ?></td></tr>                        
            <tr><td>Message</td><td>:</td><td><?php echo $body; ?></td></tr>                        
        </table>

        <div>             
        <?php foreach($replys as $reply) { ?>                
            <div class="row replies">
                <div class="col-md-2 who">                        
                    <h3><?php echo ($reply->opened_by == 'Student')  ? $reply->student : $reply->admin; ?></h3>                        
                    <em><?php echo globalDateTimeFormat($reply->open_at); ?></em>
                </div>
                <div class="col-md-9">
                    <p><?php echo $reply->body; ?></p>
                </div>
            </div>
        <?php } ?>
        </div>
          <div class="box-footer">
        <a href="<?php echo site_url( 'messages') ?>" class="btn btn-default">
            <i class="fa fa-long-arrow-left"></i> 
            Back to Message
        </a>
        <span class="btn btn-primary open_reply_box">
            <i class="fa fa-reply"></i> 
            Reply Message
        </span>
    </div>
  </div>
</div>


<?php $this->load->view('frontend/message_reply'); ?>