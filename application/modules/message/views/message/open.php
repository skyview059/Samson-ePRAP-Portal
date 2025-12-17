<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
<link href="assets/lib/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css"/>
<section class="content-header">    
    <h1> <i class="fa fa-edit"></i> Open New Message  <small>Start</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Mailbox</li>
    </ol>
</section>

<section class="content">
<div class="panel panel-default">
  <div class="panel-heading">Compose New Message</div>
  <div class="panel-body">
      <form name="compose" id="compose" method="post" enctype="multipart/form-data"
              action="<?php echo base_url('admin/message/open_action'); ?>" >

            <div class="box-body">
                <div class="form-group">
                    <select id="To" name="student_id" class="form-control select2">                                
                        <?php echo getDropDownStudentList( $id ); ?>
                    </select>                             
                </div>

                <div class="form-group">
                    <input id="Subject" class="form-control" name="subject" placeholder="Subject:" value="">
                </div>
                <div class="form-group">
                    <textarea id="Message" rows="15" name="message" class="form-control"></textarea>                        
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">  
                        
                        <input type="hidden" class="btn btn-default">
                        <a href="admin/message" class="btn btn-default">
                            <i class="fa fa-times"></i> 
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-envelope-o"></i>
                            Send
                        </button>
                    </div>                                                    
                </div>
            </div>
        </form>
  </div>
</div>

</section>

<!-- Page Script -->
<script src="assets/lib/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({placeholder: "Select an Email Template", allowClear: true});        
        $('#Message').wysihtml5();
    });    
</script>
