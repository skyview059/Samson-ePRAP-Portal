<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Result <small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>result">Result</a></li>
        <li class="active"> Generate Result</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">Generate Result</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'get')); ?>

                    <div class="form-group">
                        <label for="exam_id" class="col-sm-3">Choose the Exam<sup>*</sup></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="exam_id" id="exam_id">
                                <?php echo getExamNameDropDown($exam_id); ?>
                            </select>
                            <?php echo form_error('exam_id') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exam_centre_id" class="col-sm-3">Choose the Centre <sup>*</sup></label>
                        <div class="col-sm-9">
                            <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                                <?php echo getExamCentreDroDownByExam($exam_id, $exam_centre_id); ?>
                            </select>
                            <?php echo form_error('exam_centre_id') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exam_schedule_id" class="col-sm-3">Choose the Exam Date & Time <sup>*</sup></label>
                        <div class="col-sm-9">
                            <select name="exam_schedule_id" class="form-control select2" id="exam_schedule_id">
                                <?php echo getExamNameDropDownByCentre($exam_centre_id, $exam_schedule_id, true ); ?>
                            </select>
                            <?php echo form_error('exam_schedule_id'); ?>
                            <div id="exam_schedule_id_err"></div>
                        </div>
                    </div>
                    <div class="col-md-9 col-md-offset-3" style="padding-left:5px;">
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                        <a href="<?php echo site_url(Backend_URL . 'result') ?>" class="btn btn-danger">Cancel</a>
                        <span class="btn btn-success" id="summery">Overview Before Publish</span>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            
            <?php if($exam_schedule_id){?>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                    <?php if($scenario_list){?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="40" class="text-center">SL/No</th>
                                <th width="100" class="text-center">Scenario No</th>
                                <th>Scenario Name</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Pass Mark Generated</th>
                                <th class="text-center hide_on_print" width="150">Produce Result</th>
                            </tr>
                            <?php 
                            foreach ($scenario_list as $key => $scenario) {
                                $target = getStudentExamResultByScenario($exam_schedule_id, $scenario->scenario_id);
                                $total_student = ($target) ? $target->total_student : 0;
                            ?>
                            <tr>
                                <td class="text-center"><?= ++$key;?></td>
                                <td class="text-center"><?= $scenario->reference_number;?></td>
                                <td><?= $scenario->name;?></td>
                                <td class="text-center"><?= $total_student;?></td>
                                <td class="text-center"><?= ($target && $target->pass_mark!=null) ? '<i class="fa fa-check"></i>' : '';?></td>
                                <td class="text-center">
                                    <?php
                                    if(getStudentExamResultByScenario($exam_schedule_id, $scenario->scenario_id)){
                                        echo anchor(
                                                site_url(Backend_URL . 'assess/result/view_scores?exam_id='.$exam_id.'&exam_centre_id='.$exam_centre_id.'&exam_schedule_id='.$exam_schedule_id.'&scenario_id='.$scenario->scenario_id), '<i class="fa fa-fw fa-list"></i> View Scores ', 'class="btn btn-xs btn-success"'
                                        );
                                    }else{
                                      echo '<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-check"></i> The exam is not done yet</button>';  
                                    }

                                    ?>
                                </td>
                            </tr>
                            <?php }//foreach?>
                        </table>
                    </div>
                    <?php }else{?>
                            <div class="callout callout-info">
                                <h4>Not Found!</h4>
                                <p>No Scenario Found for This Exam.</p>
                            </div>
                    <?php }?>
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
            url: 'admin/assess/result/center_list_by_exam',
            type: 'POST',
            dataType: 'json',
            data: {exam_id: exam_id},
            beforeSend: function () {
                $('#exam_centre_id').html('<option value="0">Loading...</option>');
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#exam_centre_id').html(jsonRespond.Msg);
                }
            }
        });
        return false;
    });

    $(document).on('change', '#exam_centre_id', function () {
        var centre_id = $(this).val();
        $.ajax({
            url: 'admin/exam/exam_list_by_centre',
            type: 'POST',
            dataType: 'json',
            data: {centre_id: centre_id},
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
    
    $(document).on('click', '#summery', function () {
        var id = parseInt( $('#exam_schedule_id').val() ) || 0;
        if( id === 0 ){
            $('#exam_schedule_id').addClass('required');
            $('#exam_schedule_id_err').html('<p class="ajax_error">Please Select Schedule Data & Time</p>');
        } else {
            window.open(
                '<?php echo site_url( Backend_URL . 'assess/result?id='); ?>' + id,
                '_blank'
            );
            $('#exam_schedule_id').removeClass('required');
            $('#exam_schedule_id_err').html('');
        }
    });
</script>