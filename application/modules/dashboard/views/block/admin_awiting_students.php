<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                 <i class="fa fa-spinner fa-spin"></i> 
                Mock Booking Request 
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered no-margin table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">S/L</th>
                                <th class="text-center" width="90">Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Number</th>
                                <th>Mock Exam Name</th>
                                <th>Centre Name</th>
                                <th>Exam Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $std){ ?>
                                <tr id="row_<?= $std->enroll_id; ?>">
                                    <td class="text-center"><?=  ++$sl; ?></td>
                                    <td class="text-center no-padding"><?=  getPhoto_v3( $std->photo, $std->gender, $std->full_name, 65, 65 ); ?></td>
                                    <td><?=  $std->title .' '. $std->full_name; ?></td>
                                    <td><a href="<?= base_url('admin/student/read/'.$std->stu_id); ?>"><?= $std->email; ?></a></td>
                                    <td><?= '+'. $std->phone; ?></td>
                                    <td><?= "{$std->number_type} {$std->gmc_number}"; ?></td>
                                    <td><?= $std->exam_name .'<br/>'. $std->label; ?></td>
                                    <td><?= $std->centre_name; ?> </td>
                                    <td><?= globalDateTimeFormat($std->datetime) . ' ['. dayLeftOfExam($std->datetime) . "]"; ?>
                                        <p class='small-padding'>
                                            <span onclick="return student_enroll_action(<?= $std->enroll_id; ?>, 'Enrolled');" class='btn btn-success btn-xs'>
                                                  <i class='fa fa-check-square-o'></i> 
                                                Approve</span>                                            
                                            <span onclick="return student_enroll_action(<?= $std->enroll_id; ?>, 'Delete');" class='btn btn-warning btn-xs'>
                                                  <i class='fa fa-times'></i> 
                                                Cancel
                                            </span>
                                            <span onclick="return student_enroll_action(<?= $std->enroll_id; ?>, 'Blocked');" class='btn btn-danger btn-xs'>
                                                  <i class='fa fa-ban'></i> 
                                                Block
                                            </span>
                                        </p>
                                    </td>
                                </tr>                                                                
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <div class="box-footer">
                 <?php echo $pagination;?>
             </div>
            </div>
          </div>                 
    </div>
</div>
<script type="text/javascript">
    $('ul.pagination li a').on('click', function(){
       var href = $(this).attr('href');
        $.ajax({
            url: href,
            type: "GET",
            dataType: "html",            
            beforeSend: function () {
                $('#pending_enrolled_students').css('opacity','0.5');
            },
            success: function (msg) {
                $('#pending_enrolled_students').html(msg).css('opacity','1');
            }
        });    
       return false;
    });
</script>    