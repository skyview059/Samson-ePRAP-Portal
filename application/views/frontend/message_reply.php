
<div class="panel panel-default" style="display: none;" id="reply_box">
  <div class="panel-heading">Reply to Message</div>
  <div class="panel-body"><form name="reply" id="reply" method="post" action="<?php echo site_url('messages/message_reply_action'); ?>" >
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>"/>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
        <div class="box-body">

            <div class="form-group">
                <textarea id="Message" name="message" class="form-control"></textarea>                        
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right">
                    <input type="hidden" class="btn btn-default">
                    <span class="btn btn-default">
                        <i class="fa fa-times"></i> 
                        Cancel
                    </span>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-envelope-o"></i>
                        Send Reply
                    </button>
                </div>                                                    
            </div>
        </div>
    </form></div>
</div>

<?php loadCKEditor5ClassicBasic(['#message']); ?>

<script>
    $('.open_reply_box').on('click', function(){
        $('#reply_box').slideDown('slow');
    });
</script>