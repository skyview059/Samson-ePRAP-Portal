<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Job Application <small>Control panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>job">Job</a></li>
        <li class="active">Application</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="col-md-4 col-md-offset-8 text-right">
                <form action="<?php echo site_url(Backend_URL . 'job/application'); ?>" class="form-inline"
                      method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'job/application'); ?>"
                                   class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th>Job</th>
                        <th>Student</th>
                        <th>Cover Letter</th>
                        <th width="220">Status</th>
                        <th width="150">Created At</th>
                        <th width="150">Updated At</th>
                        <th width="90">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($applications as $application) { ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $application->job_title ?></td>
                            <td><?php echo $application->fname.' '.$application->mname.' '.$application->lname; ?></td>
                            <td><?php echo getShortContent($application->cover_letter, 100) ?></td>
                            <td>
                                <select name="status_<?= $application->id?>" id="status_<?= $application->id?>"
                                        class="form-control" onchange="applicationStatusUpdate('<?= $application->id ?>')">
                                    <?php echo Tools::getJobApplicationStatus($application->status); ?>
                                </select>
                            </td>
                            <td><?php echo globalDateTimeFormat($application->created_at) ?></td>
                            <td><?php echo globalDateTimeFormat($application->updated_at) ?></td>
                            <td>
                                <?php
                                echo anchor(site_url(Backend_URL . 'job/application/delete_action/' . $application->id), '<i class="fa fa-fw fa-trash"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Application: <?php echo $total_rows ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function applicationStatusUpdate(id){
        var status = $('#status_'+id).val();
        $.ajax({
            url: 'admin/job/application/set_status',
            type: 'POST',
            dataType: "json",
            data: { id: id, status: status  },
            beforeSend: function(){
                toastr.warning("Please Updating...");
            },
            success: function ( jsonRespond ) {
                if (jsonRespond.Status === 'OK') {
                    toastr.success("Status successfully updated");
                }else if(jsonRespond.Status === 'FAIL'){
                    toastr.error("Status Could not be updated");
                }else{
                    toastr.error("Something went wrong please try again");
                }
            }
        });
    }
</script>