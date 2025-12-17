<?php echo getStudentProcessBar(); ?>
<div class="page-title">
    <h3>Available Mock Exams</h3>
</div>

<?php 
/*
if (!$enrolled_exams) { ?>
    <p class="ajax_notice">No Mock Exam Found!</p>
<?php } ?>
<?php foreach ($enrolled_exams as $e) { ?>
    <div class="exam_row enrolled">
        <p><span class="label_v2">Exam Name:</span> <?php echo $e->name; ?></p>
        <p><span class="label_v2">Centre:</span> <?php echo $e->centre; ?></p>
        <p><span class="label_v2">Date & Time:</span> <?php echo globalDateTimeFormat($e->datetime); ?></p>
        <p><span class="label_v2">Day Left:</span>             
            <?php echo dayLeftOfExam($e->datetime); ?>
        </p>
        <p><em><span class="label_v2">Address:</span> <?php echo $e->address; ?></em></p>            
    </div>
<?php } ?>
*/ ?>
    
<!--<h3>Available Mock Exams</h3>-->
<!--
    <pre>
    <?php //echo $sql;  ?>
    <br/><br/>
    Student ID: <?php //echo $this->student_id; ?>

    <?php // dd( $available_mock_exam ); ?>
    </pre>
-->
<br>

<?php if (!$available_mock_exam) { ?>
    <p class="ajax_notice">No Mock Exam Found!</p>
<?php } ?>
<?php foreach ($available_mock_exam as $e) {
    
    $isEnrolled = Tools::isAlreadyEnrolled($e->id, $this->student_id );
    $booked     = Tools::enrolledStudentByMockExam($e->id );
    ?>
    <div class="exam_row">
        <p><span class="label_v2">Exam Name:</span> <?php echo $e->name; ?>  
            <?php echo $isEnrolled; ?>
            <?php if($isEnrolled == 'Pending'){ ?>
            <span class="btn btn-warning pull-right">
                <i class="fa fa-spinner fa-spin"></i>
                Awaiting Confirmation
            </span>
            <?php } elseif( $isEnrolled == 'Enrolled' ) { ?>
                <span class="btn btn-success pull-right">
                    <i class="fa fa-check-square-o"></i>
                    Booking Confirmed
                </span>
            <?php } elseif( $isEnrolled == 'Cancelled' ) { ?>
                <span class="btn btn-danger pull-right">
                    <i class="fa fa-times"></i>
                    Booking Request Cancelled
                </span>
            <?php } else { ?>
                <?php if($booked >= $e->student_limit){ ?>
                <span class="btn btn-danger disabled pull-right">
                    <i class="fa fa-times"></i>
                    Full Seat Booked (<?= $e->student_limit; ?> Seats)
                </span>
                <?php } else { ?>
                <span onclick="enrollment(<?= $e->id; ?>);" class="btn btn-primary pull-right">
                    <i class="fa fa-check-square-o"></i>
                    Request to Book This Mock Exam
                    (<?= $e->student_limit - $booked; ?> Seat(s) Left)
                </span>
                <?php  } ?>
            <?php  } ?>
        </p>
        
        <p><span class="label_v2">Label:</span> <span class="label_online"><?php echo $e->label; ?></span></p>
        <p><span class="label_v2">GMC Exam Dates:</span> <?php echo multiDateFormat($e->gmc_exam_dates); ?></p>
        <p><span class="label_v2">Total Seat:</span> <?php echo ($e->student_limit); ?></p>
        
        <p><span class="label_v2">Centre:</span> <?php echo $e->centre; ?></p>
        <p><span class="label_v2">Date & Time:</span> 
            <?php echo globalDateTimeFormat($e->datetime); ?>            
        </p>
        <p><span class="label_v2">Day Left:</span>             
            <?php echo dayLeftOfExam($e->datetime); ?>
        </p>

        <p><em><span class="label_v2">Address:</span> <?php echo $e->address; ?></em></p>            
    </div>
<?php } ?>

    
<div class="modal fade" id="enrollment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="form">
                <input type="hidden" id="es_id" name="id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Complete Your Enrollment
                    </h4>
                </div>

                <div class="modal-body">
                    <div id="js_respond"></div>
                    <div id="summery" class="text-center">                        
                        <p>Confirm you enrollment.</p>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span> 
                        Close
                    </button>
                    <button type="button" class="btn btn-primary " onclick="enrollment_confim();">
                        <i class="fa fa-save"></i> 
                        Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>