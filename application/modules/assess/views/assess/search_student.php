<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Start New Assessment </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Assessment</li>
    </ol>
</section>

<section class="content">

    

    <!-- Step 1: Today's exam schedules -->
    <div class="panel panel-default">
        <div class="panel-heading">Today's Mock Exam Schedules</div>
        <div class="panel-body">
            <?php echo $sql_query; ?>

            <p>Matching: Assigned Assessor account + Unpublish Exam + Today's Exam Only. (Current Time:
                <?php echo date('dS, M Y h:i:s A'); ?>)</p>

            <?php if ($exam_schedules) { ?>
                <div class="list-group" style="margin-bottom: 0;">
                    <?php foreach ($exam_schedules as $schedule) {
                        $active = ($schedule->exam_schedule_id == $exam_schedule_id) ? 'active' : '';
                        $schedule_url = site_url(Backend_URL . 'assess/search_student?exam_schedule_id=' . $schedule->exam_schedule_id);
                        ?>
                        <a href="<?php echo $schedule_url; ?>" class="list-group-item <?php echo $active; ?>">
                            <i class="fa fa-fw fa-calendar"></i>
                            <strong><?php echo $schedule->centre; ?></strong> &raquo; <?php echo $schedule->name; ?> &raquo; (<?php echo $schedule->label; ?>)
                            <span class="pull-right">
                                <?php echo globalDateTimeFormat($schedule->datetime); ?>
                                &nbsp;<i class="fa fa-angle-double-right"></i>
                            </span>
                        </a>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="callout callout-info" style="margin-bottom: 0;">
                    <h4>No Exam Found!</h4>
                    <p>No exam schedule is assigned to your account for today.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if ($students && $exam) { ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Student Information</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th width="80">Photo</th>
                                        <th>Full Name</th>
                                        <th width="140"><?php echo $students->number_type . ' Number'; ?></th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo getPhoto_v2($students->photo, "{$students->fname} {$students->lname}"); ?>
                                        </td>
                                        <td><?php echo "{$students->title} {$students->fname} {$students->lname}"; ?></td>
                                        <td><?php echo $students->gmc_number; ?></td>
                                        <td><?php echo $students->email; ?></td>
                                        <td><?php echo $students->phone; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-md-offset-3">
                <div class="form-group row" style="font-size: 18px;">
                    <label for="right_candidate" class="col-sm-4 control-label text-right" style="padding-top: 15px;">Is
                        this the right candidate?</label>
                    <div class="col-sm-8">
                        <button class="btn bg-olive btn-flat margin" onclick="rightCandidate(1)">Yes</button> Or
                        <a class="btn bg-maroon btn-flat margin"
                            href="<?php echo site_url(Backend_URL . 'assess/search_student?exam_schedule_id=' . $exam_schedule_id); ?>">No</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="exam_information" style="display: none;">
                <div class="panel panel-default">
                    <div class="panel-heading">Exam Information</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <address style="text-align: center">
                                    <strong><?= $exam->exam_name; ?></strong><br>
                                    Center: <?= $exam->center_name; ?><br>
                                    <?= $exam->center_address; ?><br>
                                    Date: <?= globalDateTimeFormat($exam->datetime); ?><br>
                                </address>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } else if ($students) { ?>

        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-info">
                    <h4>Not Found!</h4>
                    <p>No Exam Found for <?php echo $students->number_type . ' ' . $students->gmc_number; ?>.</p>
                </div>
            </div>
        </div>

    <?php } else if ($exam_schedule_id) { ?>

        <!-- Step 2: Enrolled students of the selected exam schedule -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Enrolled Students
                    
                        <!-- <a class="btn btn-default btn-sm pull-right" style="margin-top: -5px;"
                        href="<?= site_url(Backend_URL . 'exam/student/' . $exam_schedule_id); ?>"> 
                            Enrol Student 
                        </a> -->
                    
                    </div>
                    <div class="panel-body">
                        <?php if ($enrolled_students) { ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th width="40">Sl</th>
                                            <th width="80" class="text-center">Photo</th>
                                            <th>Full Name</th>                                            
                                            <th>Email</th>
                                            <th>StudentID</th>
                                            <th width="120" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sl = 0;
                                        foreach ($enrolled_students as $s) {
                                            $scenario_url = site_url(Backend_URL . "assess/student_scenarios?exam_schedule_id={$exam_schedule_id}&student_id={$s->id}");
                                            ?>
                                            <tr>
                                                <td><?php echo ++$sl; ?></td>
                                                <td class="text-center"><?php echo getPhoto_v2($s->photo, $s->full_name); ?></td>
                                                <td><?php echo $s->full_name; ?></td>
                                                <td><?php echo $s->email; ?></td>
                                                <td><?php echo studentID($s->id); ?></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-xs btn-scenarios"
                                                        data-url="<?php echo $scenario_url; ?>"
                                                        data-student="<?php echo html_escape($s->full_name); ?>">
                                                        <i class="fa fa-search"></i> Open Scenario
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <span class="btn btn-primary">Total Students: <?php echo count($enrolled_students); ?></span>

                            <button type="button" class="btn btn-primary pull-right hide_on_print"
                                    onclick="linkStudent();">
                                <i class="fa fa-hospital-o"></i>
                                Book Student for Exam
                            </button>
                            
                        <?php } else { ?>
                            <div class="callout callout-info">
                                <h4>Not Found!</h4>
                                <p>No Student Enrolled for This Exam Schedule.</p>
                            </div>

                            <button type="button" class="btn btn-primary pull-right hide_on_print"
                                    onclick="linkStudent();">
                                <i class="fa fa-hospital-o"></i>
                                Book Student for Exam
                            </button>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
</section>

<!-- Scenario Modal -->
<div class="modal fade" id="scenarioModal" tabindex="-1" role="dialog" aria-labelledby="scenarioModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="scenarioModalLabel">Name of Student: </h4>
            </div>
            <div class="modal-body" id="scenarioModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function rightCandidate(val) {
        if (val === 1) {
            $("#exam_information").slideDown(500);
        }
    }
    $(document).ready(function () {

        //Open exam schedule scenarios in a modal
        $(document).on('click', '.btn-scenarios', function () {
            var url = $(this).data('url');
            var student = $(this).data('student');

            $('#scenarioModalLabel').text('Name of Student: ' + student);
            $('#scenarioModalBody').html('<p class="text-center" style="padding: 30px 0;"><i class="fa fa-spinner fa-spin fa-2x"></i></p>');
            $('#scenarioModal').modal('show');

            $.get(url)
                .done(function (html) {
                    $('#scenarioModalBody').html(html);
                })
                .fail(function () {
                    $('#scenarioModalBody').html('<div class="callout callout-danger" style="margin-bottom: 0;"><h4>Error!</h4><p>Failed to load scenarios. Please try again.</p></div>');
                });
        });

    });

</script>

<?php if ($exam_schedule_id && !$students) { ?>
    <!-- Book Student for Exam — shared modal + Alpine component (defines linkStudent() / bookingModal()) -->
    <?php $this->load->view('student/student/book_for_exam_modal', [
        'schedule_id' => $exam_schedule_id,
        'exam_id'     => $schedule_exam_id,
        'source'      => 'Assessment page',
    ]); ?>
<?php } ?>
