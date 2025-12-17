<form class="student-form" style="background-color: #ebebee;
    padding: 20px;" action="<?php echo site_url(Backend_URL . 'student'); ?>" class="form-inline" method="get">
<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Student of Teacher</span>
            <select class="form-control" name="tid">
            <?php echo getDropDownUserList($tid); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Exam</span>
            <select class="form-control" name="eid" id="exam_id">
            <?php echo getExamNameDropDownForFrontend($exam_id); ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Centre</span>
            <select class="form-control" name="cid" id="exam_centre_id">
                <?php echo getExamCentreDroDownByExam($exam_id, $centre_id); ?>                
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">                    
                <i class="fa fa-calendar"></i> Exam Date
            </span>
            <input type="text" autocomplete="off" 
                   placeholder="Select Date"
                   class="form-control js_datepicker" 
                   name="dt" value="<?php echo $exam_date; ?>">

        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Type</span>
            <select class="form-control" name="type">
                <?php echo selectOptions($type,[
                    '' => '--All--',
                    'GMC' => 'GMC',
                    'GDC' => 'GDC',
                    'NMC' => 'NMC',
                ]); ?>
            </select>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Country of Origin</span>
            <select name="ocid" class="form-control select2" id="ocid">
                <?php echo getDropDownCountries($ocid, 'Any Country'); ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Current Location</span>
            <select name="pcid" class="form-control select2" id="pcid">
                <?php echo getDropDownCountries($pcid, 'Any Country'); ?>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Status</span>
            <select class="form-control" name="status">
            <?php echo selectOptions($status,[
                '' => '--All--',
                'Active' => 'Active',
                'Inactive' => 'Inactive',
                'Pending' => 'Pending',
                'Archive' => 'Archive',
            ]); ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Keyword" name="q" value="<?php echo $q; ?>">
            <span class="input-group-btn">                                        
                <button class="btn btn-success" type="submit">Search</button>
                <a title="Reset" href="<?php echo site_url(Backend_URL . 'student'); ?>" class="btn btn-default">
                    <i class="fa fa-random"></i> Reset
                </a>
            </span>
        </div>
    </div>    
</div>
    
</form>
<script>
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
</script>