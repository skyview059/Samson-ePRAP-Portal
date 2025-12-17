<div class="row" style="padding-bottom: 15px">
    <div class="col-md-6">
        <h3 style="font-weight: 700; color: #6C00A1; display: inline-block">Study Plan</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo site_url('study-plan/create'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Create Study Plan</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Study Plan List</div>
            <div class="panel-body">
                <?php if ($study_plans): ?>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th width="50">S/L</th>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Start Time</th>
                            <th>Duration</th>
                            <th width="270">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sl = 1;
                        foreach ($study_plans as $t) : ?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo getExamName($t->exam_id); ?></td>
                                <td><?php echo $t->subject_name; ?></td>
                                <td><?php echo getDateTimeColour($t->start_date_time); ?></span></td>
                                <td><?php echo $t->duration; ?></td>
                                <td>
                                    <a href="<?php echo site_url('study-plan/share/' . $t->id); ?>"
                                       class="btn btn-xs btn-success"><i class="fa fa-envelope"></i> Share</a>
                                    <a href="<?php echo site_url('study-plan/view/' . $t->id); ?>"
                                       class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>
                                    <a href="<?php echo site_url('study-plan/update/' . $t->id); ?>"
                                       class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="<?php echo site_url('study-plan/delete/' . $t->id); ?>"
                                       class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="ajax_notice">No Study Plan Found!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($total_rows > 0): ?>
    <div class="row">
        <div class="col-md-6">
            <span class="label label-primary">Total: <?php echo $total_rows ?></span>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination; ?>
        </div>
    </div>
<?php endif; ?>