<h2>Invoice ID # <?= $payment->invoice_id; ?></h2>
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <td width="200">Purchased on</td>
        <td width="5">:</td>
        <td><?= globalDateTimeFormat($payment->purchased_at); ?> </td>
    </tr>
    <tr>
        <td>Student Name </td>
        <td>:</td>
        <td><?= $payment->full_name; ?></td>
    <tr>
    <tr>
        <td>Student Email </td>
        <td>:</td>
        <td><?= $payment->email; ?></td>
    <tr>
    <tr>
        <td>Number of Courses </td>
        <td>:</td>
        <td><?= $payment->total_items; ?></td>
    <tr> 
    <tr>
        <td>Total Payment </td>
        <td>:</td>
        <td><?= GBP($payment->total_pay); ?></td>
    <tr>
    <tr>
        <td>Payment Gateway </td>
        <td>:</td>
        <td><?= $payment->gateway; ?></td>
    <tr>
    <tr>
        <td>Payment Status </td>
        <td>:</td>
        <td><?= $payment->status; ?></td>
    <tr>
</table> 

<h3>Booked Courses</h3>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th class="text-left">Category</th>
            <th class="text-left">Course Name</th>
            <th class="text-center">Course Date Time</th>
            <th width="150" class="text-right">Price</th>                                                            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $key => $course) { ?>
            <tr>
                <td class="text-left"><?= $course->category; ?></td>       
                <td class="text-left"><?= $course->course; ?></td>
                <td class="text-center"><?= $course->start_date; ?></td>
                <td class="text-right"><?= GBP($course->price); ?></td>                                                                         
            </tr>
        <?php } ?>
    </tbody>
</table>