<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <td width="220">Course Category</td>
        <td width="5">:</td>
        <td><?= $c_category; ?></td>
    </tr>
    <tr>
        <td>Course Name</td>
        <td>:</td>
        <td><?= $c_name; ?></td>
    </tr>
    <tr>
        <td>Course Booked Price</td>
        <td>:</td>
        <td><?= GBP($c_booked_price); ?></td>
    </tr>
    <tr>
        <td>Course Duration</td>
        <td>:</td>
        <td><?= $c_duration; ?></td>
    </tr>
    <tr>
        <td>Course Schedule &amp; Time</td>
        <td>:</td>
        <td><?php echo $start_date .' ~ '. $end_date; ?></td>
    </tr>
    <tr>
        <td>Day(s) Left to Start Course</td>
        <td>:</td>
        <td><?= $c_days_left; ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Invoice ID</td>
        <td>:</td>
        <td><?= $invoice_id; ?></td>
    </tr>
    <tr>
        <td>Refund Percentage</td>
        <td>:</td>
        <td><?= refundPercentage($c_days_left); ?> %</td>
    </tr>
    <tr>
        <td>Refund Amount</td>
        <td>:</td>        
        <td><?= GBP($refund_amount); ?></td>
    </tr>
    <tr>
        <td>Cancel Remarks</td>
        <td>:</td>
        <td><?= $student_remark; ?></td>
    </tr>
</table>