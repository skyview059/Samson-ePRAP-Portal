<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<style type="text/css">
    .table thead tr th,
    .table tbody tr td{
        vertical-align: middle;
    }
</style>
<section class="content-header">
    <h1>Mock Practice <small>Student List</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'practice') ?>">Mock Practice</a></li>
        <li class="active">Students</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo practiceTabs($id, 'student'); ?>
    <div class="box no-border">
        
        <div class="box-header">                                                
            <div class="row">                
                <div class="col-md-6 col-md-offset-3 text-center">                        
                    <h2 class="no-margin">Practice Name: <?php echo $practice_name; ?></h2>
                    <h4>
                        Centre: <?php echo ($centre_name); ?>, <?php echo ($centre_address); ?><br/>
                        Date & Time: <?php echo globalDateTimeFormat($datetime); ?>
                    </h4>                
                </div>
            </div>
        </div>
      
        <div class="box-body">                            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="70">S/L</th>
                            <th width="80">Photo</th>
                            <th>Full Name</th>                            
                            <th width="140">Number</th>
                            <th>Email</th>
                            <th width="140">Phone</th>
                            <th width="150">Booked on</th>                            
                            <th>Attendance</th>
                            <th class="text-center hide_on_print" width="170">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($students as $student) { 
//                        $options = "<input name='students[]' value='{$student->student_id }' class='mark' type='checkbox'/>";
                        ?>
                        <tr>
                            <td><label><?= sprintf('%02d', ++$start); ?></label></td>                                                    
                            <td><?php echo getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?></td>
                            <td><?php echo "{$student->fname} {$student->mname} {$student->lname}"; ?></td>                            
                            <td><?php echo "{$student->number_type}-{$student->gmc_number}"; ?></td>
                            <td><?php echo $student->email; ?></td>
                            <td>
                            &nbsp;&nbsp;<i class="fa fa-mobile-phone"></i> <?php echo "+{$student->phone_code}{$student->phone}"; ?><br/>
                                    <i class="fa fa-whatsapp" ></i> <?php echo "+{$student->whatsapp_code}{$student->whatsapp}"; ?>                            
                            </td>
                            <td><?php echo globalDateTimeFormat($student->created_at); ?></td>                            
                            <td>
                                <span class="practice_attendance btn btn-block <?= ($student->attendance==='Yes') ? 'btn-success' : 'btn-warning' ?> btn-xs" id="practice_attendance_<?= $student->practice_booked_id;?>" data-id="<?= $student->practice_booked_id;?>" data-attendance="<?= ($student->attendance==='Yes') ? 'No' : 'Yes';?>" onclick="return confirm('Confrim Attendance Update')"> 
                                    <?= ($student->attendance=='Yes') ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-fw fa-close"></i>';?>
                                    <?= $student->attendance;?> 
                                </span>
                            </td>
                            <td class="text-center hide_on_print">
                                <?php
                                echo anchor(
                                    site_url(Backend_URL . 'student/read/' . $student->student_id ), 
                                    '<i class="fa fa-fw fa-external-link"></i> Details', 
                                    'class="btn btn-xs btn-primary" target="_blank"'
                                );
                                if($student->status=='Enrolled'){
                                ?>
                                <span id="<?= $student->practice_booked_id; ?>" class="btn cancel-practice btn-xs btn-danger">
                                  <i class="fa fa-times"></i>
                                  Cancel
                                </span>
                                <?php } else { ?>
                                    <span class="btn  btn-xs btn-default disabled">
                                      <i class="fa fa-ban"></i>
                                      Canceled
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>            
        </div>
        
        <p class="show_on_print">Printed at <?php echo date('d/m/Y h:i a'); ?></p>
        
    </div>
</section>
<script type="text/javascript">
    $(document).on('click', '.practice_attendance', function (e) {   
        e.preventDefault();
        var id = $(this).attr("data-id")
        var attendance = $(this).attr("data-attendance")
        $.ajax({
            url: "admin/practices/attendance",
            type: 'post',
            dataType: 'json',
            data: {id:id, attendance:attendance},
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                if (respond.Status === 'OK') {
                     location.reload();
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });
</script>