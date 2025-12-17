<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> File/Document <small>Management Panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">File</li>
    </ol>
</section>
<?php load_module_asset('file','css'); ?>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'file'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'file'); ?>"
                                   class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th>Student Name</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Uploaded on</th>
                        <th width="100">Status</th>
                        <th class="text-center" width=180">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $url = Backend_URL . 'file/delete/';
                    foreach ($files as $file) { ?>
                        <tr>
                            <td><?php echo ++$start; ?></td>
                            <td>
                                <a href="<?php echo site_url("admin/student/file/{$file->stu_id}"); ?>">
                                    <?php echo $file->full_name; ?>
                                </a>
                            </td>
                            <td><?php echo $file->title; ?></td>
                            <td><?php echo download_attachment($file->file); ?></td>
                            <td><?php echo globalDateTimeFormat($file->timestamp); ?></td>

                            <td>
                                <label class="switch">
                                    <input class="status_btn"
                                           data-id="<?php echo $file->id; ?>"
                                           data-status="<?php echo $file->status; ?>"
                                        <?php echo ($file->status == 'Locked') ? ' checked ' : ''; ?>
                                           type="checkbox"/>
                                    <span class="slider"></span>
                                </label>
                            </td>

                            <td class="text-center">
                                <?php echo filePreviewBtn($file->file); ?>
                                <?php echo isEditLocked($file->id, $file->status, $url); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(".status_btn").click(function () {
        var id = parseInt($(this).data('id'));
        var status = $(this).data('status');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {id: id, status: status},
            url: '<?php echo site_url(Backend_URL . 'file/change_status'); ?>',
            success: function (response) {
                if(response.Msg === 'Unlock'){
                    var html = '<a href="admin/file/delete/'+id+'" class="btn btn-danger btn-xs" onclick="return confirm(\'Confirm Delete\');"><i class="fa fa-times"></i> Delete</a>';
                } else {
                    var html = '<span class="btn btn-danger btn-xs disabled" title="Admin Locked File"><i class="fa fa-lock"></i> Locked </span>';
                }                
                $('#sbtn-'+id).html(html);
            }
        });
    });
</script>