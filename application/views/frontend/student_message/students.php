<div class="panel panel-default">
    <div class="panel-heading">List of Students</div>
    <div class="panel-body">
        <form class="student-form form-inline" action="<?php echo site_url( 'student-messages/students'); ?>" method="get">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3 col-md-offset-3">
                    <input type="text" class="form-control" style="width: 100%" placeholder="Student ID or Name" name="q" value="<?php echo $q; ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="exam_id" style="width: 100%">
                        <?php echo ExamCourseDroDown($exam_id, 'Any Exam'); ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success btn-block" type="submit"><i class="fa fa-search"></i> Search</button>
                </div>
                <div class="col-md-1 no-padding">
                    <a title="Reset" href="<?= site_url('student-messages/students'); ?>" class="btn btn-default btn-block">
                        <i class="fa fa-random"></i> Reset
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="<?= site_url('student-messages'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-arrow-left"></i> Back to Messages
                    </a>
                </div>
            </div>
        </form>

        <?php if (!$students) {
            echo '<p class="ajax_notice">No Student Found</p>';
        } ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="80">Photo</th>
                    <th>Name</th>
                    <th>Exam Name</th>
                    <th>Exam Centre</th>
                    <th>Exam Date</th>
                    <th class="text-center" width="160">Action</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($students as $student) {
                    $link = site_url('student-messages/open?id=' . $student->id);
                    ?>
                    <tr>
                        <td class="text-center">
                            <?= getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?>
                        </td>
                        <td><a href="<?= $link; ?>"><?= $student->fname; ?> <?= $student->lname; ?></a>
                        </td>
                        <td><?= $student->exam_name; ?></td>
                        <td><?= $student->exam_centre_name; ?></td>
                        <td><?= globalDateFormat($student->exam_date); ?></td>
                        <td class="text-center">
                            <a href="<?= $link; ?>" class="btn btn-success"><i class="fa fa-envelope-open"></i> Send Message</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer text-center">
        <?php echo $pagination; ?>
    </div>
</div>