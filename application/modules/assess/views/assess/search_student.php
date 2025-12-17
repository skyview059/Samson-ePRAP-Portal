<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Start New Assessment </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Assessment</li>
    </ol>
</section>

<section class="content">
        <div class="panel panel-default">
            <div class="panel-heading">Start New Assessment</div>
            <div class="panel-body">
                <div class="col-md-12 text-center">
                <form action="<?php echo site_url(Backend_URL . 'assess/search_student'); ?>" class="form-inline" method="GET">
                    <div class="input-group">
                        <span class="input-group-addon">Mock Exam</span>
                        <select name="exam_schedule_id" class="form-control">
                            <?php echo getTodayExamScheduleDropDownByTeacher($exam_schedule_id); ?>
                        </select>
                        <span class="input-group-addon">Search</span>
                        <select name="number_type" class="form-control" style="width:150px;">
                            <?php echo getNumberType($number_type); ?>
                        </select>
                        <span class="input-group-addon" style="padding: 0!important;"></span>       
                        <input type="text" class="form-control" autocomplete="off" placeholder="Enter Number" name="gmc" value="<?php echo $gmc; ?>">
                        <span class="input-group-btn">
                            <?php if ($gmc <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'assess/search_student'); ?>"
                                   class="btn btn-default">Reset</a>
                               <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
            </div>
        </div>

    <?php if ($exam && $gmc) { ?>

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
                                        <th width="140"><?php echo $students->number_type.' Number'; ?></th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="text-center"><?php echo getPhoto_v2($students->photo, "{$students->fname} {$students->lname}"); ?></td>
                                        <td><?php echo "{$students->title} {$students->fname} {$students->lname}"; ?></td>
                                        <td><?php echo $students->gmc_number; ?></td>
                                        <td><?php echo $students->email; ?></td>
                                        <td><?php echo $students->phone; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div></div>
                  </div>
            </div>
            <div class="col-md-9 col-md-offset-3">
                <div class="form-group row" style="font-size: 18px;">
                    <label for="right_candidate" class="col-sm-4 control-label text-right" style="padding-top: 15px;">Is this the right candidate?</label>
                    <div class="col-sm-8">
                        <button class="btn bg-olive btn-flat margin" onclick="rightCandidate(1)">Yes</button> Or
                        <a class="btn bg-maroon btn-flat margin" href="admin/assess/search_student">No</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="exam_information" style="display: none;">
                <div class="panel panel-default">
                    <div class="panel-heading">Exam Information</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if ($exam) { ?>
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
                                                                    site_url(Backend_URL . 'assess/initial_approach/' . $question->id), '<i class="fa fa-fw fa-play"></i> Start Assessment', 'class="btn btn-xs btn-primary"'
                                                                );
                                                            } else if ($question->status == 'Complete') {
                                                                echo '<button type="button" class="btn btn-xs btn-success"><i class="fa fa-fw fa-check"></i> Completed </button>';
                                                            } else {
                                                                echo anchor(
                                                                    site_url(Backend_URL . 'assess/initial_approach/' . $question->id), '<i class="fa fa-fw fa-caret-square-o-right"></i> Runing Exam', 'class="btn btn-xs btn-warning"'
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
                                <?php } else { ?>
                                    <div class="callout callout-info">
                                        <h4>Not Found!</h4>
                                        <p>No Exam Found for This Student.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                  </div>
            </div>
        </div>
    <?php } else if($gmc){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-info">
                    <h4>Not Found!</h4>
                    <p>No Exam Found for <?php echo $number_type.' '.$gmc;?>.</p>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
<script type="text/javascript">
    function rightCandidate(val){
        if(val===1){
            $("#exam_information").slideDown(500);
        }
    }
    $(document).ready(function () {
        
    });

</script>