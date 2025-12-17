<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Stage of Progression <small>for <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">Student</a></li>
        <li class="active">Progression</li>
    </ol>
</section>

<style type="text/css">
    div.bg { background-color: #fbfbfb; min-height: 350px; }
    div.bg div.box-footer { background-color: #fbfbfb; }
</style>
<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'progress'); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <div class="col-md-8">
                <div class="box-header with-border">
                    <h3 class="box-title">Stage of Progression of Student</h3>            
                </div>
                <div class="box-body">

                    <?php
                    if (!$progress) {
                        echo '<p class="ajax_notice">No Progression Found!</p>';
                    } else {
                        ?>


                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Stage</th>
                                    <th width="120">File</th>
                                    <th width='150'>Date & Time</th>
                                    <th width='150' class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($progress as $p) { ?>
                                    <tr id="row_<?= $p->id; ?>">
                                        <td><?php echo $p->title; ?></td>
                                        <td><?php echo download_attachment($p->file); ?></td>
                                        <td><?php echo globalDateTimeFormat($p->timestamp); ?></td>
                                        <td class="text-center">                                            
                                            <?php echo filePreviewBtn( $p->file ); ?>
                                            <span onclick="return deteteProgress(<?= $p->id; ?>);" class="btn btn-danger btn-xs">
                                                <i class="fa fa-times"></i>
                                                Delete
                                            </span>                                            
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>                    
                        </table>
                    <?php } ?>
                </div>                
            </div>
            <div class="col-md-4">
                <div class="bg">
                    <div class="studemtadminright" style="background-color: #f3f4f6; border-radius: 4px; margin-top: 30px;padding: 15px;">

                    <h3 class="box-title">Upload/Add Option of Progression</h3>            
                <form name="save_progress" id="save_progress" onsubmit="return saveProgress(event);"  method="POST" class="form-horizontal">
                    <input name="student_id" value="<?php echo $id; ?>" type="hidden" />
                    <div class="box-body">
                        <div id="respond"></div>    

                        <div class="form-group">  
                            <label class="col-md-4 control-label" for="cat">
                                Category
                            </label>
                            <div class="col-md-8">
                                <input type="text" value="<?= $type; ?>" class="form-control" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="progression_id">
                                <sup>*</sup>Select Progress
                            </label>
                            <div class="col-md-8">
                                <select id="progression_id" name="progression_id" class="form-control">
                                    <?php echo $getDropDownProgress;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">  
                            <label class="col-md-4 control-label" for="file">
                                (Optional) File
                            </label>
                            <div class="col-md-8">
                                <input id="file" name="file" class="input-file" type="file">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer with-border text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </form>
                </div>
            </div>
                </div>
        </div>
        </div>
      </div>
</section>
<script type="text/javascript">
    function deteteProgress( id ){
        var yes = confirm('Confirm Delete?');
        if(!yes){ return false; }
        
        $.ajax({
            url: 'admin/student/progress_delete',
            type: "POST",
            dataType: "json",
            data: { id: id },            
            beforeSend: function () {
                $('#row_' + id ).css('background-color','red');
            },
            success: function (respond) {
                if( respond.Status === 'OK'){
                    $('#row_' + id ).fadeOut('slow');
                } else {
                    $('#row_' + id ).css('background-color','inherit');
                    alert(  respond.Msg );
                }
            }
        });
    }
    
    function saveProgress(e) {
        e.preventDefault();
        var data = new FormData(document.getElementById("save_progress"));
        $.ajax({
            url: 'admin/student/save_progress',
            type: "POST",
            dataType: "json",
            data: data,
            enctype: 'multipart/form-data',
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                $('#respond').html(respond.Msg);
                if (respond.Status === 'OK') {
                    document.getElementById("save_progress").reset();
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
            processData: false, // tell jQuery not to process the data
            contentType: false   // tell jQuery not to set contentType
        });
        return false;
    }
</script>
