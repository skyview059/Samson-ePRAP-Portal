<style>
    .select2{ width: 100% !important;}
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #d2d6de !important;
        border-radius: 0px !important;
        padding: 4px !important;
        height: auto;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #787878;
        line-height: 28px;
    }
</style>

<div class="panel panel-default">
  <div class="panel-heading">Compose New Message to Student</div>
  <div class="panel-body">
      <form name="compose" id="compose" method="post" enctype="multipart/form-data"
          action="<?php echo site_url('student-messages/message_new_action'); ?>">

        <div class="box-body">
            <div class="form-group" style="position:relative; overflow: hidden;">
                <select id="to" name="to_student_id" class="form-control select2">
                    <?php echo getDropDownStudentList($to_student_id); ?>
                </select>
            </div>

            <div class="form-group">
                <input id="subject" class="form-control" name="subject" placeholder="Subject:" value="<?php echo $subject; ?>">
                <?php echo form_error('subject') ?>
            </div>
            <div class="form-group">
                <textarea id="message" name="message" class="form-control"><?php echo $message; ?></textarea>
                <?php echo form_error('message') ?>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right">

                    <input type="hidden" class="btn btn-default">
                    <a href="<?php echo site_url('student-messages'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-envelope-o"></i>
                        Send
                    </button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>

<?php loadCKEditor5ClassicBasic(['#message']); ?>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>