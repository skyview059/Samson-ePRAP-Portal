<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($students && $exam) { ?>

    <!-- Exam information -->
    <address style="text-align: center">        
        <strong><?= $exam->exam_name; ?></strong><br>
        Center: <?= $exam->center_name; ?><br>
        <?= $exam->center_address; ?><br>
        Date: <?= globalDateTimeFormat($exam->datetime); ?><br>
    </address>

    <!-- Scenarios -->
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th width="80">S/L</th>
                    <th>Name</th>
                    <th width="50">Action</th>
                    <th width="200">Assessor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $start = 0;
                foreach ($exam->scenarios as $question) {
                    ?>
                    <tr>
                        <td><?= ++$start; ?></td>
                        <td><?= $question->name; ?></td>
                        <td>
                            <?php
                            if ($question->status == 'not_assessor') {
                                echo '<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-check"></i> Assessment already started by the other assessor </button>';
                            } else if ($question->status == 'initial_start') {
                                echo anchor(
                                    site_url(Backend_URL . 'assess/initial_approach/' . $question->id),
                                    '<i class="fa fa-fw fa-play"></i> Start Assessment',
                                    'class="btn btn-xs btn-primary"'
                                );
                            } else if ($question->status == 'Complete') {
                                echo '<button type="button" class="btn btn-xs btn-success"><i class="fa fa-fw fa-check"></i> Completed </button>';
                            } else {
                                echo anchor(
                                    site_url(Backend_URL . 'assess/initial_approach/' . $question->id),
                                    '<i class="fa fa-fw fa-caret-square-o-right"></i> Runing Exam',
                                    'class="btn btn-xs btn-warning"'
                                );
                            }
                            ?>
                        </td>
                        <td><?php echo $question->assessor; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php } else if ($students) { ?>

    <div class="callout callout-info" style="margin-bottom: 0;">
        <p>As a Assessor you are not assigned to any scenarios for this exam.</p>
        <p>No scenarios found for <?php echo $students->id; ?>.</p>             
    </div>

<?php } else { ?>

    <div class="callout callout-danger" style="margin-bottom: 0;">
        <h4>Not Found!</h4>
        <p>Student not found.</p>
    </div>

<?php } ?>
