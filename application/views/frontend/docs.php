<?php echo getStudentProcessBar(); ?>

<h3>Documents</h3>
<div class="row">
    <div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">Title goes here</div>
            <div class="panel-body"><?php
                if (!$files) {
                    echo '<p class="ajax_error">No Document Found! Please upload required documents.</p>';
                } else {
                    ?>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th width='120'>File</th>
                                <th width='190'>Uploaded on</th>
                                <th width='200' class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file) {
                                ?>
                                <tr>
                                    <td><?php echo $file->title; ?></td>
                                    <td><?php echo download_attachment($file->file); ?></td>
                                    <td><?php echo globalDateTimeFormat($file->timestamp); ?></td>                                
                                    <td class="text-center">
                                        <?php echo filePreviewBtn($file->file); ?>
                                        <?php echo isEditLocked($file->id, $file->status); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php } ?></div>

        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">Upload Documents</div>
            <div class="panel-body">
                <form name="save_doc" id="save_doc" onsubmit="return saveDoc(event);" method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div id="respond"></div>

                        <div class="form-group" id="title_dropdown">
                            <label class="col-sm-3 control-label" for="title">Name<sup>*</sup>
                            </label>
                            <div class="col-sm-9">
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
                            <label class="col-sm-3 control-label" for="file">
                                File<sup>*</sup>
                            </label>
                            <div class="col-sm-9" style="overflow: hidden;font-size: 16px;">
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
<div class="row">
    <div class="col-sm-8">
        <p class="ajax_notice">
            Once Admin <i class="fa fa-lock"></i> locked file.
            You can not delete them anymore.
        </p> 
    </div>
</div>


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
            url: 'student_portal/upload_doc',
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

    function deteteFile(id) {

        $.ajax({
            url: 'student_portal/delete_doc',
            type: "post",
            dataType: "json",
            data: {id: id},
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
