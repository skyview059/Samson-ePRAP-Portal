<form action="<?php echo site_url(Backend_URL . 'course/booked'); ?>" class="form-inline row" method="get">
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Category</span>
            <select class="form-control" name="category_id">
                <?= getDropDownCategory($category_id); ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Course</span>
            <select class="form-control" name="course_id" id="course">
                <?= getDropDownCourse($course_id); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Date</span>

            <select class="form-control" name="course_date_id" id="date_slot">
                <?= getCourseDateSlotID($course_date_id, $course_id); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Status</span>
            <select class="form-control" name="status">
                <?= selectOptions($status, [
                    '0' => '--All--',
                    'Pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                    'Cancelled' => 'Cancelled'
                ]); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-search"></i>
            Search
        </button>
        <a href="<?php echo site_url(Backend_URL . 'course/booked'); ?>" class="btn btn-default">
            <i class="fa fa-times"></i>
            Reset
        </a>
    </div>
</form>

<script>
    $('#course').on('change', function () {
        let course_id = $(this).val();

        $.ajax({
            dataType: 'HTML',
            method: 'get',
            url: 'admin/course/date/slot/' + course_id,
            beforeSend: function (){
                $('#date_slot').html('<option value="0">Loading...</option>');
            },
            success: function (response) {
                $('#date_slot').html(response);
            }
        })
    });
</script>