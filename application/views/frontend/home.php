<?php echo getStudentProcessBar(); ?>

    <div class="clearfix" style="margin-bottom: 50px;"></div>

<?php echo Tools::getAnnouncement( $number_type ); ?>

<div class="row">
    <div class="col-md-2">
        <img class="img-circle pull-left profile_img img-responsive" 
             alt="<?php echo $name; ?>"
             src="<?php echo getPhotoWithTimThumb($photo, 120, 120, 1); ?>" />
    </div>
    <div class="col-md-8">
        <h1 class="no-margin"><small>Welcome back,</small> <?php echo $name; ?></h1>
        <p><?php echo $number_type . ' Number: ' . $gmc; ?></p>    

        <?php echo getStageOfProgess();?>
    </div>
    
    
    <div class="col-md-2">
        <a href="messages/open" class="btn btn-primary pull-right">
            <i class="fa fa-location-arrow"></i>
            Send Message
        </a>
    </div>
</div>
</div>
<hr/>

<?php 
/*
<h1>Your Upcoming Mock Exam(s)</h1>

<?php if (!$exams) { ?>
    <p class="ajax_notice">No Mock Exam Found!</p>
<?php } ?>

<?php foreach ($exams as $e) { ?>
    <div class="exam_row">
        <p><span class="label_v2">Exam Name:</span> <?php echo $e->name; ?></p>
        <p><span class="label_v2">Centre:</span> <?php echo $e->centre; ?></p>
        <p><span class="label_v2">Date & Time:</span> <?php echo globalDateTimeFormat($e->datetime); ?> <small>(Day Left: <?php echo dayLeftOfExam($e->datetime); ?>)</small></p>

        <p><em><span class="label_v2">Address:</span> <?php echo $e->address; ?></em></p>            
    </div>

<?php } ?>
 * 
 */ ?>