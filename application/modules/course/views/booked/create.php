<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Course Booking <small> as Admin </small> <a href="<?php echo site_url(Backend_URL . 'course/booked') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li><a href="<?php echo Backend_URL ?>course/booked">Booked</a></li>
        <li class="active">Add New</li>
    </ol>
</section>


<?php load_module_asset('course', 'css'); ?>
<section class="content">
    <form id="bookingForm" name="bookingForm" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="student_id" class="col-sm-2 control-label">Select Student :</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="student_id" id="student_id">
                            <?php echo getDropDownStudentList($student_id); ?>
                        </select>
                        <?php echo form_error('student_id') ?>
                    </div>
                </div>
            </div>
        </div>
        <br/>

        <div id="bookCourse"></div>      
    </form>

</section>
<?php load_module_asset('course', 'js'); ?>
<?php load_module_asset('course', 'js', 'script.common.js.php'); ?>
