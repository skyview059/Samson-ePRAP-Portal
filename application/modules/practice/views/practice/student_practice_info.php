<?php if(!$student_practice_info){ ?>
    <p class="ajax_notice">No Data Found!</p>
<?php } else { ?>
    
    <div class="row">
        <div class="col-md-12">
            <address class="text-center" style="font-size: 16px; font-weight: 600; text-align: center;">
                Name: <?php echo "{$student_practice_info->fname} {$student_practice_info->mname} {$student_practice_info->lname}"; ?><br/>
                <?php echo $student_practice_info->number_type;?> Number : <?php echo $student_practice_info->gmc_number; ?><br>
                Exam: <?php echo $student_practice_info->practice_name; ?><br>
                Date & Time: <?php echo globalDateTimeFormat($student_practice_info->datetime); ?>
                Booked At: <?php echo globalDateTimeFormat($student_practice_info->assign_at); ?>
            </address>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="remarks" class="col-sm-2 control-label">Remarks<sup>*</sup></label>
                <div class="col-sm-10">
                    <input type="hidden" name="id" value="<?php echo $student_practice_id; ?>"/>
                    <textarea class="form-control" name="remarks" id="remarks" rows="3" cols="27"></textarea>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
  

