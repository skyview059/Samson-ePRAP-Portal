<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('message', 'css'); ?>
<section class="content-header">
    <h1>Message  <small>Read</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'mailbox') ?>">Message</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <div class="panel panel-default">
  <div class="panel-heading">Message View</div>
  <div class="panel-body"><div class="box-body">
            
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
        </div>
        <div class="box-footer">
            <a href="<?php echo site_url(Backend_URL . 'message') ?>" class="btn btn-default">
                <i class="fa fa-long-arrow-left"></i> 
                Back to Message
            </a>
            <span class="btn btn-primary open_reply_box">
                <i class="fa fa-reply"></i> 
                Reply Message
            </span>
        </div></div>
</div>

    
    
    <?php $this->load->view('reply'); ?>
</section>