<div class="row" style="padding-bottom: 15px">
    <div class="col-md-6">
        <h3 style="font-weight: 700; color: #6C00A1">Study Plan<small> / View</small></h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default"><i class="fa fa-backward"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">View Study Plan</div>
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
                    <tr>
                        <th>Created At</th>
                        <td><?php echo timePassed($study_plan->created_at); ?></td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td><?php echo timePassed($study_plan->updated_at); ?></td>
                    </tr>
                </table>
            </div>
            <div class="panel-footer text-right">
                <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default"><i class="fa fa-backward"></i>
                    Back</a>

                <a href="<?php echo site_url('study-plan/share/' . $study_plan->id); ?>" class="btn btn-success"><i
                            class="fa fa-envelope"></i> Share</a>
                <a href="<?php echo site_url('study-plan/update/' . $study_plan->id); ?>" class="btn btn-warning"><i
                            class="fa fa-edit"></i> Edit</a>
                <a href="<?php echo site_url('study-plan/delete/' . $study_plan->id); ?>"
                   class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">
                    <i class="fa fa-trash"></i> Delete
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-5">
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
</div>
