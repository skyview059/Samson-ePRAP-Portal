<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Exams <small>of <b><?php echo $student_name; ?></b></small></h1>    
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>student">Student</a></li>
        <li class="active">Exam</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'exam'); ?>
    <div class="panel panel-default">
  <div class="panel-body"><div class="row">
                <div class="col-sm-6">
                    <form action="<?php echo $action; ?>" method="post" id="user_form" class="form-horizontal"
                          enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="exam_centre_id" class="col-sm-3 control-label">Centre <sup>*</sup></label>
                            <div class="col-sm-9">
                                <select name="exam_centre_id" class="form-control" id="exam_centre_id">
                                    <option value="">-- Select Exam Centre --</option>
                                    <?php echo ExamCenterDroDown($exam_centre_id); ?>
                                </select>
                                <?php echo form_error('exam_centre_id') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exam_schedule_id" class="col-sm-3 control-label">Exam Name & Date <sup>*</sup></label>
                            <div class="col-sm-9">
                                <select name="exam_schedule_id" class="form-control js_select2" id="exam_id">
                                    <?php echo getExamNameDropDownByCentre($exam_centre_id, $exam_schedule_id); ?>
                                </select>
                                <?php echo form_error('exam_schedule_id'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <input name="id" value="<?php echo $id; ?>" type="hidden"/>
                                <button type="submit" class="btn btn-primary">Book to Mock Exam</button>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div></div>
</div>

    <?php if($exams): ?>
<div class="panel panel-default">
  <div class="panel-heading">Student Exam List</div>
  <div class="panel-body">
      <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th width="50">SL.</th>
                            <th width="350">Exam Name & Label </th>
                            <th width="120">Booking Status</th>
                            <th width="150">Booked on</th>
                            <th width="150">Mock Date & Time</th>
                            <th>Centre</th>                                                        
                            <th>Address</th>
                        </tr>

                        <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td><?php echo ++$s; ?></td>
                            <td>
                                <a href="<?= site_url( Backend_URL . "exam/student/{$exam->es_id}" ); ?>" target="_blank">
                                    <?php echo $exam->name .' ('. $exam->label ?>)<br/>
                                    <em><?php echo multiDateFormat($exam->gmc_exam_dates); ?></em>
                                </a>
                            </td>
                            <td><?php echo $exam->status; ?>
                            
                            <?php if($exam->status == 'Pending'){ ?>
                                <p class='small-padding'>
                                    <span onclick="return student_enroll_action(<?= $exam->enroll_id; ?>, 'Enrolled', true );" class='btn btn-success btn-xs'>
                                          <i class='fa fa-check-square-o'></i> 
                                        Approve</span>
                                    <span onclick="return student_enroll_action(<?= $exam->enroll_id; ?>, 'Cancelled', true );" class='btn btn-danger btn-xs'>
                                          <i class='fa fa-ban'></i> 
                                        Cancel
                                    </span>
                                </p>
                            <?php } ?>
                            </td>                                              
                            <td><?php echo globalDateTimeFormat($exam->enrolled_at); ?></td>
                            <td><?php echo globalDateTimeFormat($exam->datetime); ?></td>                            
                            <td><?php echo $exam->centre_name ?></td>
                            <td><?php echo $exam->centre_address ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
  </div>
</div>

    <?php endif; ?>

</section>
<?php load_module_asset('dashboard', 'js');?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js_select2').select2();
    });    
    $(document).on('change','#exam_centre_id', function(){
        var centre_id = $(this).val();
        $.ajax({
            url: 'admin/exam/exam_list_by_centre',
            type: 'POST',
            dataType: 'json',
            data: { centre_id: centre_id },
            beforeSend: function () {
                $('#exam_id').html('<option value="0">Loading...</option>');
            },
            success: function (jsonRespond) {
                if(jsonRespond.Status === 'OK'){
                    $('#exam_id').html(jsonRespond.Msg);
                }
            }
        });
        return false;
    });
</script>