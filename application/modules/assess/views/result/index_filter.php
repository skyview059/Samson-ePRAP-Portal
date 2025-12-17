
<form action="<?php echo site_url(Backend_URL . 'assess/result'); ?>" class="form-inline" method="get">
    <div class="row">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-edit"></i>
                    Select Exam
                </span>
                <select name="exam_id" class="form-control" id="exam_id">                        
                    <?php echo ExamCourseDroDown($exam_id); ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-map-marker"></i>
                    Select Centre
                </span>
                <select name="centre_id" class="form-control" id="centre_id">
                    <option value="0">-- Select Exam Centre --</option>
                    <?php echo ExamCenterDroDown($exam_centre_id, $exam_id); ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-clock-o"></i> 
                    Select Date & Time
                </span>
                <select name="schedule_id" class="form-control" id="schedule_id">
                    <option value="0">-- Select Exam Centre --</option>                    
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group">                
                <button class="btn btn-primary" type="submit">
                    Filter
                </button>                
                <a href="<?php echo site_url(Backend_URL . 'assess/result'); ?>" class="btn btn-default">
                    Reset
                </a>                
            </div>
        </div>
    </div>
</form>