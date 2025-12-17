<div class="box box-primary" style="display: none;" id="reply_box">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-reply"></i>
            Reply to Message
        </h3>
    </div>
    <!-- /.box-header -->
    <form name="reply" id="reply" method="post" action="<?php echo base_url('admin/message/reply_action'); ?>">
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>"/>
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>"/>
        <div class="box-body">

            <div class="form-group">
                <textarea id="Message" name="message" class="form-control"></textarea>
            </div>

            <!-- /.box-body -->
            <div class="box-footer text-center">
                <span class="btn btn-default">
                    <i class="fa fa-times"></i> 
                    Cancel
                </span>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-envelope-o"></i>
                    Send Reply
                </button>
            </div>
        </div>
    </form>
</div>
<?php loadCKEditor5ClassicBasic(['#message']); ?>

<script>
    $('.open_reply_box').on('click', function () {
        $('#reply_box').slideDown('slow');
    });
</script>