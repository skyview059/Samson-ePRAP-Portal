<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>Practice Scenarios</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Scenario</li>
    </ol>
</section>
<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">List of Practice Scenarios</div>
        <div class="panel-body">
            <?php if ($exams) { ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th width="40" class="text-center">S/L</th>
                            <th>Name</th>
                            <th width="150" class="text-center">Subjects</th>
                            <th width="150" class="text-center">Topics</th>
                            <th width="150" class="text-center">Scenarios</th>
                            <th width="150" class="text-center">Status</th>
                            <th width="250" class="text-center">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $start = 0;
                        foreach ($exams as $exam) { ?>
                            <tr>
                                <td><?php echo sprintf('%03d', ++$start); ?></td>
                                <td><?php echo $exam->name; ?></td>
                                <td class="text-center">
                                    <span class="label label-default">
                                        <?php echo ($exam->subjects); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="label label-default">
                                        <?php echo ($exam->topics); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="label label-default">
                                        <?php echo ($exam->scenarios); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php echo examStatus($exam->status, $exam->id); ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                        echo anchor(site_url(Backend_URL . 'scenario/practice/marking_criteria/' . $exam->id), '<i class="fa fa-edit"></i> Marking Criteria', 'class="btn btn-xs btn-default"');
                                        echo anchor(site_url(Backend_URL . 'scenario/practice/view/' . $exam->id), '<i class="fa fa-gear"></i> Manage', 'class="btn btn-xs btn-primary"');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="btn btn-primary">Total Scenarios: <?php echo count($exams); ?></span>
                        </div>
                    </div>
                </div>

            <?php } else { ?>
                <div class="box-body">
                    <p class="ajax_notice"> No Scenarios found.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<script>
    function examStatusUpdate(post_id, status) {
        $.ajax({
            url: 'admin/exam/set_status',
            type: 'POST',
            dataType: "json",
            data: {status: status, post_id: post_id},
            beforeSend: function () {
                $('#exam_status_' + post_id).html('Updating...');
            },
            success: function (jsonRespond) {
                $('#exam_status_' + post_id)
                    .html(jsonRespond.Status)
                    .removeClass('btn-default btn-danger btn-success')
                    .addClass(jsonRespond.Class);
            }
        });
    }
</script>