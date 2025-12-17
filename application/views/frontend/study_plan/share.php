<style>
    .input-group {
        margin-bottom: 15px;
    }

    .email_fields {
        margin-top: 15px;
    }
</style>

<div class="row" style="padding-bottom: 15px">
    <div class="col-md-6">
        <h3 style="font-weight: 700; color: #6C00A1">Study Plan<small> / Share</small></h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default"><i class="fa fa-backward"></i>  Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Share Your Study Plan</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10">
                        <p>Share your study plan with your friends or family members using email.</p>
                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-info" id="add_new_email"><i class="fa fa-plus"></i> Add New Email
                        </button>
                    </div>
                </div>

                <!-- Share with multiple people using email -->
                <form action="<?php echo site_url('study-plan/share_action'); ?>" method="post">
                    <input type="hidden" name="study_plan_id" value="<?php echo $study_plan->id; ?>">
                    <div class="email_fields">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <input type="email" name="email[]" class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success"><i class="fa fa-envelope-o"></i> Send Mail / Share</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Share Log</div>
            <div class="panel-body">
                <?php if ($share_logs): ?>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Shared At</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($share_logs as $key => $share_log): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $share_log->email; ?></td>
                                <td><?php echo timePassed($share_log->created_at); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No share log found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Study Plan</div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th width="200">Exam</th>
                        <td><?php echo getExamName($study_plan->exam_id); ?></td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td><?php echo $study_plan->subject_name; ?></td>
                    </tr>
                    <?php if ($study_plan->topic_name): ?>
                        <tr>
                            <th>Topic</th>
                            <td><?php echo $study_plan->topic_name; ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($study_plan->topic_item_ids): ?>
                        <tr>
                            <th>Scenario</th>
                            <td>
                                <?php
                                $sl = 1;
                                foreach ($topic_items as $item) { ?>
                                    <div>
                                        <span><?php echo $sl++; ?>.</span>
                                        <span data-id="<?= $item->id; ?>"
                                              data-exam_id="<?= $item->exam_id; ?>"
                                              data-action="<?= site_url('scenario-practice/exam/' . $item->exam_id . '/item/' . $item->id); ?>">
                                                    <?= $item->presentation; ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Start Time</th>
                        <td><?php echo getDateTimeColour($study_plan->start_date_time); ?></td>
                    </tr>
                    <tr>
                        <th>End Time</th>
                        <td><?php echo getDateTimeColour($study_plan->end_date_time); ?></td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td><?php echo $study_plan->duration; ?></td>
                    </tr>
                </table>
            </div>
            <div class="panel-footer text-right">
                <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default"><i class="fa fa-backward"></i> Back</a>
                <a href="<?php echo site_url('study-plan/update/' . $study_plan->id); ?>"
                   class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                <a href="<?php echo site_url('study-plan/delete/' . $study_plan->id); ?>"
                   class="btn btn-danger" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash"></i>  Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const max_fields = 10;
        const wrapper    = $(".email_fields");
        const add_button = $("#add_new_email");

        let x = 1;
        $(add_button).click(function (e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append('<div class="input-group">' +
                    '<div class="input-group-addon"><i class="fa fa-envelope"></i></div>' +
                    '<input type="email" name="email[]" class="form-control" required>' +
                    '<div class="input-group-addon">' +
                    '<button type="button" class="btn btn-xs btn-danger remove_field"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>');
            } else {
                alert('You Reached the limits, you can add only 10 emails at a time.');
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').parent('div').remove();
            x--;
        });
    });
</script>