<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Student Document <small>for <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">Student</a></li>
        <li class="active">File</li>
    </ol>
</section>

<style type="text/css">
    div.bg { background-color: #fbfbfb; min-height: 350px; }
    div.bg div.box-footer { background-color: #fbfbfb; }
</style>
<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'file'); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <div class="col-md-8">
                <div class="box-header with-border">
                    <h3 class="box-title">Student Documents</h3>            
                </div>
                <div class="box-body">

                    <?php
                    if (!$files) {
                        echo '<p class="ajax_notice">No File Found!</p>';
                    } else {
                        ?>

                        <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>                                
                                <th width='120'>File</th>                                
                                <th width='160'>Date & Time</th>
                                <th width='180' class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file) { 
                                $url = Backend_URL . 'student/file_delete';
                                ?>
                                <tr>
                                    <td><?php echo $file->title; ?></td>
                                    <td><?php echo download_attachment($file->file); ?></td>
                                    <td><?php echo globalDateTimeFormat($file->timestamp); ?></td>
                                    <td class="text-center">                                        
                                        <?php echo filePreviewBtn( $file->file ); ?>
                                        <?php echo isEditLocked($file->id,$file->status, $url ); ?>                                        
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
                
                <form name="save_doc" id="save_doc" onsubmit="return saveDoc(event);"  method="POST" class="form-horizontal">
                    <input name="student_id" value="<?php echo $id; ?>" type="hidden" />
                    <div class="box-body">
                        <div id="respond"></div>

                        <div class="form-group" id="title_dropdown">
                            <label class="col-md-2 control-label" for="title">Name<sup>*</sup>
                            </label>
                            <div class="col-md-10">
                                <select class="form-control" name="title_dropdown" id="title_dropdown">
                                    <?php echo nameOfDocuments(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <div id="title_area" class="hidden">
                                    <input type="text" class="form-control" name="title_val" id="title_val"
                                           placeholder="Please Specify"/>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" name="title" id="title"/>

                        <div class="form-group">  
                            <label class="col-md-2 control-label" for="file">
                                File<sup>*</sup>
                            </label>
                            <div class="col-md-8" style="overflow: hidden">
                                <input id="file" name="file" type="file">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer with-border text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-cloud-upload"></i>
                            Upload
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

    $('#title_dropdown').on('change', function () {
        var data = $('#title_dropdown option:selected').val();
        if (data === 'Other') {
            $('#title_area').removeClass('hidden');
            $('#title').val('');
        } else {
            $('#title_area').addClass('hidden');
            $('#title').val(data);
        }
    });

    $("#title_val").keyup(function () {
        var data = $('#title_val').val();
        $('#title').val(data);
    });

    $(document).ready(function () {
        var data = $('#title_dropdown option:selected').val();
        $('#title').val(data);
    });

    function saveDoc(e) {
        e.preventDefault();
        var data = new FormData(document.getElementById("save_doc"));
        $.ajax({
            url: 'admin/student/file_upload',
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
                    document.getElementById("save_doc").reset();
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
