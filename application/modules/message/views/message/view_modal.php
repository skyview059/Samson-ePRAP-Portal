<?php load_module_asset('message', 'css'); ?>
<div class="box no-border">
    <div class="box-body">
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
            <?php foreach ($replys as $reply) { ?>                
                <div class="row replies">
                    <div class="col-md-2 who">                        
                        <h3><?php echo ($reply->opened_by == 'Student') ? $reply->student : $reply->admin; ?></h3>                        
                        <em><?php echo globalDateTimeFormat($reply->open_at); ?></em>
                    </div>
                    <div class="col-md-9">
                        <p><?php echo $reply->body; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>