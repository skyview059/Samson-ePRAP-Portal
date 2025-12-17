<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
    table.table thead th,
    table.table td{
        vertical-align: middle;
    }
</style>
<section class="content-header ">
    <h1> Overall Student Result 
        <a href="<?php echo site_url(Backend_URL . 'result') ?>"
           class="btn btn-default">Back</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>result">Overall Student Result </a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Overall Student Result</div>
  <div class="panel-body">
      <div class="row hide_on_print">
                <div class="col-md-12">
                    <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>

                    <div class="form-group">
                        <label for="exam_id" class="col-sm-3">Choose the Exam<sup>*</sup></label>
                        <div class="col-sm-6">
                            <select class="form-control" name="exam_id" id="exam_id">
                                <?php echo getExamNameDropDown($exam_id); ?>
                            </select>
                            <?php echo form_error('exam_id') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exam_schedule_id" class="col-sm-3">Choose the Exam Date & Time <sup>*</sup></label>
                        <div class="col-sm-6">
                            <select name="exam_schedule_id" class="form-control" id="exam_schedule_id">
                                <?php echo getExamScheduleDropDownByExam($exam_id, $exam_schedule_id, true ); ?>
                            </select>
                            <?php echo form_error('exam_schedule_id'); ?>
                        </div>
                    </div>
                    <div class="col-md-10 col-md-offset-3" style="padding-left:5px;">
                        <button type="submit" name="submit" class="btn btn-primary" value="1"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo site_url(Backend_URL . 'assess/result/overall_student_results') ?>" class="btn btn-default">Cancel</a>
                        <?php if ($exam_id && $exam_schedule_id) { ?>
                        <span class="btn btn-primary hide_on_print" onclick="print(document);"><i class="fa fa-print"></i> Print</span> 
                        <?php }?>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <?php if ($exam_id && $exam_schedule_id) { ?>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <?php if ($results) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Name</th>
                                            <th colspan="<?php echo count($scenario_list)?>" class="text-center">Scenario</th>
                                            <th rowspan="2" class="text-center col-md-1">Total Score</th>
                                            <th rowspan="2" class="text-center col-md-1">Pass or Fail </th>
                                        </tr>
                                        <tr>
                                            <?php foreach ($scenario_list as $scenario) { ?>
                                                <th class="text-center"><?= $scenario->reference_number;?></th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $result) { ?>
                                        <tr>
                                            <td><?= $result['student_name'];?></td>
                                            <?php 
                                            $total_score = 0;
                                            $total_pass_mark = 0;
                                            foreach ($scenario_list as $scenario) { 
                                                $marks = !empty($result['scenario_list'][$scenario->scenario_id]['mark']) ? $result['scenario_list'][$scenario->scenario_id]['mark'] : 0;
                                                $pass_mark = !empty($result['scenario_list'][$scenario->scenario_id]['pass_mark']) ? $result['scenario_list'][$scenario->scenario_id]['pass_mark'] : 0;
                                                $total_score +=$marks;
                                                $total_pass_mark +=$pass_mark;
                                                echo '<td class="text-center">'.$marks.'</td>';
                                            }
                                            $grade = ($total_score >= $total_pass_mark) ? 'Pass' : 'Fail';
                                            ?>
                                            <td class="text-center"><?= $total_score;?></td>
                                            <td class="text-center"><?= $grade;?></td>
                                        </tr>
                                        <?php }//foreach?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="callout callout-info">
                                <h4>Not Found!</h4>
                                <p>No Result Found for This Exam.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }//if?>
  </div>
</div>

</section>
<script type="text/javascript">
    $(document).on('change', '#exam_id', function () {
        var exam_id = $(this).val();
        $.ajax({
            url: 'admin/assess/result/exam_schedule_by_exam',
            type: 'POST',
            dataType: 'json',
            data: {exam_id: exam_id},
            beforeSend: function () {
                $('#exam_schedule_id').html('<option value="0">Loading...</option>');
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#exam_schedule_id').html(jsonRespond.Msg);
                }
            }
        });
        return false;
    });

</script>