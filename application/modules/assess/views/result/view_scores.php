<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Result <small>Scenario</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Result</li>
    </ol>
</section>

<section class="content">
    <?php if($exam_scenario){?>
    <div class="row">

        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <address class="text-center" style="font-size: 16px; font-weight: 600;">
                        Scenario : <?php echo $exam_scenario->scenario_name; ?><br>
                        Exam: <?php echo $exam_scenario->exam_name; ?><br>
                        Exam Date & Time: <?php echo globalDateTimeFormat($exam_scenario->datetime); ?>
                    </address>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th width="40" class="text-center">SL</th>
                                    <th>Student Name</th>
                                    <th class="text-center">Data Gathering Technical and Assessment Skills </th>
                                    <th class="text-center">Clinical Management Skills </th>
                                    <th class="text-center">Interpersonal Skills </th>
                                    <th class="text-center">Your Score</th>
                                    <th class="text-center hidden">Examiner’s Judgment</th>
                                    <th class="text-center">Pass Mark</th>
                                    <th class="text-center">Results</th>
                                    <th class="text-center">Assessor</th>
                                </tr>
                                <?php
                                foreach ($results as $key => $result) {
                                    $total_score = $result->technical_skills + $result->clinical_skills + $result->interpersonal_skills;
                                    $pass_mark = $result->pass_mark;
                                    $grade = ($result->pass_mark!=null) ? ($total_score >= $pass_mark) ? 'Pass' : 'Fail' : '';
                                    ?>
                                    <tr id="result_details_id_<?= $result->id; ?>">
                                        <td class="text-center"><?php echo ++$key; ?></td>
                                        <td><?php echo "{$result->fname} {$result->mname} {$result->lname}"; ?></td>
                                        <td class="text-center"><?php echo intval($result->technical_skills); ?></td>
                                        <td class="text-center"><?php echo intval($result->clinical_skills); ?></td>
                                        <td class="text-center"><?php echo intval($result->interpersonal_skills); ?></td>
                                        <td class="text-center"><?php echo $total_score; ?></td>
                                        <td class="text-center hidden"><?php echo ($result->overall_judgment != null) ? $result->overall_judgment : 'N/A'; ?></td>
                                        <td class="text-center pass_mark"><?php echo $pass_mark;?></td>
                                        <td class="text-center grade"><?php echo $grade; ?></td>
                                        <td class="text-center" style="line-height:1;"><?php echo "{$result->ass_name} <br/>{$result->ass_phone} <br/> {$result->ass_email}"; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>                            
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php 
                            echo anchor(
                                    site_url(Backend_URL . 'assess/result/generate?exam_id='.$exam_id.'&exam_centre_id='.$exam_centre_id.'&exam_schedule_id='.$exam_schedule_id), 
                                    '<i class="fa fa-angle-left"></i> Back to List', 
                                    'class="btn btn-info"'
                                );
                            ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generate_pass_score">
                                <?php echo ($exam_scenario->pass_mark != null) 
                                        ? '<i class="fa fa-edit"></i> Update Pass Score' 
                                        : '<i class="fa fa-navicon"></i> Generate Pass Score'; 
                                ?>
                            </button>
                            <?php 
                            echo anchor(
                                    site_url(Backend_URL . 'assess/result/view_scores?exam_id='.$exam_id.'&exam_centre_id='.$exam_centre_id.'&exam_schedule_id='.$exam_schedule_id.'&scenario_id='.$next_scenario_id), 'Next <i class="fa fa-angle-right"></i>', 'class="btn btn-success"'
                                );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="generate_pass_score">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Generate Pass Score</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group row">
                        <label for="avg_borderline_score" class="col-sm-4 control-label">Average Borderline Score</label>
                        <div class="col-sm-8">
                            <input type="number" min="1"  class="form-control" id="avg_borderline_score" readonly="" value="<?php echo $exam_scenario->avg_borderline_score;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="coefficient_mark" class="col-sm-4 control-label">Add Coefficient <sup>*</sup></label>
                        <div class="col-sm-8">
                            <input type="number" min=".01" max="<?php echo 12-$exam_scenario->avg_borderline_score;?>" maxlength="2" class="form-control" id="coefficient_mark" placeholder="Enter Add Coefficient Mark" value="<?php echo $exam_scenario->coefficient_mark;?>">
                            <span id="err"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pass_mark" class="col-sm-4 control-label">Generate Pass Score</label>
                        <div class="col-sm-8">
                            <input type="number" min="1"  class="form-control" id="pass_mark" readonly="" value="<?php echo $exam_scenario->pass_mark;?>">
                        </div>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="exam_schedule_id" id="exam_schedule_id" value="<?php echo $exam_schedule_id; ?>"/>
                    <input type="hidden" name="scenario_id" id="scenario_id"  value="<?php echo $scenario_id; ?>"/>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="generate">Generate</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php }?>
</section>
<script type="text/javascript">
    
//    $("#coefficient_mark").bind("keyup change", function(e) {
//        var coefficient_mark = $('#coefficient_mark').val();
//        var max = parseInt($(this).attr('max'));
//
//        if (coefficient_mark > max){
//            $(this).val(max);
//        }
//
//        var avg_borderline_score = $('#avg_borderline_score').val();
//        var pss_mark = Number(avg_borderline_score)+Number(coefficient_mark);
//        $('#pass_mark').val(pss_mark);
//    })
    
    $("#coefficient_mark").bind("keyup change", function(e) {
        $('#err').html('');
        var avg_bdrline_score = $('#avg_borderline_score').val();
        var coefficient_mark = $('#coefficient_mark').val();
        var max = parseInt($(this).attr('max'));

        if (coefficient_mark > max){
//            $(this).val(max);
            coefficient_mark = max;
            $('#coefficient_mark').val(max);
            $('#err').html('<p class="ajax_error">Maximum Mark '+coefficient_mark+'</p>');
        }


        var pss_mark = Number( avg_bdrline_score )+Number(coefficient_mark);
        $('#pass_mark').val(pss_mark);
    });

    $(document).on('click', '#generate', function () {
        
        var exam_schedule_id = $('#exam_schedule_id').val();
        var scenario_id = $('#scenario_id').val();
        var avg_borderline_score = $('#avg_borderline_score').val();
        var coefficient_mark = $('#coefficient_mark').val();
        var pass_mark = $('#pass_mark').val();
        
        
        if(coefficient_mark==='' || coefficient_mark===0){
            toastr.error("Please Add Coefficient!");
            return false;
        }
        
        if(pass_mark==='' || pass_mark===0){
            toastr.error("Please enter pass mark!");
            return false;
        }
        
        $.ajax({
            url: 'admin/assess/result/generate_pass_mark',
            type: 'POST',
            dataType: 'json',
            data: {exam_schedule_id: exam_schedule_id, scenario_id: scenario_id, avg_borderline_score: avg_borderline_score, coefficient_mark: coefficient_mark, pass_mark: pass_mark},
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#generate_pass_score').modal('hide');
                    toastr.success("Generate Pass Score Successfully!");
                    location.reload();
                }else if(jsonRespond.Status === 'FAIL'){
                    toastr.error(jsonRespond.Msg);
                }else{
                    toastr.error("Pass Score Could not Generate");
                }
            }
        });
        return false;
    });
</script>