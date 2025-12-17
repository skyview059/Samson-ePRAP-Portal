<div class="row dashboard">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span style="background-color: #fff !important;" class="info-box-icon bg-red">
                <i class="fa fa-hourglass-1"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Mock Booking Request</span>
                <span class="info-box-number"><?php echo $pending_student_qty; ?></span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span style="background-color: #fff !important;" class="info-box-icon bg-navy">
                <i class="fa fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Student</span>
                <span class="info-box-number"><?php echo $student; ?></span>
            </div>

        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span style="background-color: #fff !important;" class="info-box-icon bg-olive">
                <i class="fa fa-vcard"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Teacher</span>
                <span class="info-box-number"><?php echo $teacher; ?></span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span style="background-color: #fff !important;" class="info-box-icon bg-blue">
                <i class="fa fa-flag"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Exam Scheduled</span>
                <span class="info-box-number"><?php echo $exam_sch; ?></span>
            </div>
        </div>
    </div>
</div> 

<div id="pending_enrolled_students">
    <?php echo $enrolled_students; ?>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Today Exam Canceled Student List<div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div></div>
            <div class="panel-body"> <div class="table-responsive">
                    <table class="table table-striped table-bordered no-margin">
                        <thead>
                            <tr>
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
                            <?php echo getTodayExamCanceledStudents(); ?>
                        </tbody>
                    </table>
                </div></div>
        </div>        
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Recent Registered Student <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div></div>
            <div class="panel-body"><div class="table-responsive">
                    <table class="table table-striped table-bordered no-margin">
                        <thead>
                            <tr>
                                <th>Photo</th>                                
                                <th>Name & Email</th>                                
                                <th>Phone</th>
                                <th>ID Number</th>
                                <th>Register Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo getRecentStudents(10); ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <a href="<?php echo base_url('admin/student'); ?>" class="btn btn-sm btn-default btn-flat pull-right">
                        View All Student
                    </a>
                </div></div>
        </div>        
    </div>

    <div class="col-md-6">
        <?= $upcoming_mock_exam; ?>
    </div>
</div>