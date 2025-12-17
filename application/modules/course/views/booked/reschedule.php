<form name="reschedule" class="form-horizontal" id="reschedule">
    <input type="hidden" name="booked_id" value="<?= $id; ?>" />
    <input type="hidden" name="old_course_date_id" value="<?= $course_date_id; ?>" />
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
            <h3 class="modal-title no-margin">Reschedule Booked Course</h3>
        </div>
        <div class="modal-body">
            <div id="reschedule_respond"></div>            
            <div class="form-group row">
                <label class="col-md-2 control-label">Reschedule Date :</label>
                <div class="col-md-9" style="padding-top: 8px;">
                    <?= getBookingDates($course_id, $seat_limit, $course_date_id ); ?>
                </div>
            </div>            
            <div class="form-group row">
                <label class="col-md-2 control-label">Admin Remark :</label>
                <div class="col-md-9">
                    <textarea class="form-control" name="admin_remark" rows="5"><?= $remark; ?></textarea>
                </div>
            </div>
            
            
            <div class="form-group row">
                <label class="col-md-2 control-label">Send Email :</label>
                <div class="col-md-9" style="padding-top: 8px;">
                    <label>
                        <input type="radio" name="send_mail" value="Yes" checked="checked"/>
                        Yes &nbsp; &nbsp;
                    </label>
                    <label>
                        <input type="radio" name="send_mail" value="No"/>
                        No &nbsp; &nbsp;
                    </label>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Reschedule</button>
        </div>
    </div>
</form>

<script>
    $('#reschedule').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "admin/course/booked/reschedule_save",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function () {
                $('#reschedule_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                
                if (respond.Status === 'OK') {
                    location.href = '<?= base_url('course/booked'); ?>';
                } else {
                    $('#reschedule_respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });
</script>    