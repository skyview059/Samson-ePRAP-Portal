<form action="<?php echo site_url(Backend_URL . 'course/booked/practice'); ?>" class="form-inline row" method="get">

    <div class="col-md-3 col-md-offset-3">
        <div class="input-group">
            <span class="input-group-addon">Practice</span>
            <select class="form-control" name="practice_id" id="practice_id">
                <?= getExamNameDropDown($practice_id, 'Any'); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Gateway</span>
            <select class="form-control" name="gateway">
                <?= selectOptions($gateway, [
                    '' => 'Any',
                    'Free' => 'Free',
                    'PayPal' => 'PayPal',
                    'Stripe' => 'Stripe',
                    'Bank' => 'Bank',
                    'Cash' => 'Cash'
                ]); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon">Status</span>
            <select class="form-control" name="status">
                <?= selectOptions($status, [
                    '' => 'Any',
                    'Pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                    'Cancelled' => 'Cancelled'
                ]); ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
        <a href="<?php echo site_url(Backend_URL . 'course/booked/practice'); ?>" class="btn btn-default">
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