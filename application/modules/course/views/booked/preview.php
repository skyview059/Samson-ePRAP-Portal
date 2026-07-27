<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
        <h3 class="modal-title no-margin">Booking Details </h3>
    </div>
    <div class="modal-body">
        <?php if (empty($payment)) { ?>
            <p class="ajax_error">No booking details found.</p>
        <?php } else { ?>

            <h4 class="no-margin"><b>Student &amp; Payment</b></h4>
            <hr class="no-margin" style="margin: 8px 0;"/>
            <table class="table table-bordered table-condensed no-margin" style="line-height: 1.5;">
                <tbody>
                <tr>
                    <th width="140">Student</th>
                    <td><p class="no-margin" style="line-height: 1.2;">
                        <?= $payment->full_name; ?><br/>
                        <em><?= $payment->email; ?> | <?= "{$payment->phone_code}-{$payment->phone}"; ?></em>  
                        </p>                      
                    </td>
                    <th width="140">Invoice</th>
                    <td><?= $payment->invoice_id; ?></td>
                </tr>
                <tr>
                    <th>Purchased On</th>
                    <td><?= $payment->purchased_on; ?></td>
                    <th>Payment Status</th>
                    <td><?= isConfirmed($payment->status); ?></td>
                </tr>
                <tr>
                    <th>Gateway</th>
                    <td><?= $payment->gateway ?: '--'; ?></td>
                    <th>Total Paid</th>
                    <td><?= GBP($payment->total_pay); ?> <small>(<?= (int)$payment->total_items; ?> item/s)</small></td>
                </tr>
                <?php if (!empty($payment->admin_comments)) { ?>
                    <tr>
                        <th>Payment Note</th>
                        <td colspan="3"><?= nl2br_fk($payment->admin_comments); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <h4><b>Booked Courses</b> <small>(<?= count($bookings); ?>)</small></h4>
            <hr class="no-margin" style="margin: 8px 0;"/>
            <div class="table-responsive">                
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th width="30">#</th>
                        <th>Course</th>
                        <th>Type</th>
                        <th>Course Date</th>
                        <th class="text-right">Price</th>                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0;
                    foreach ($bookings as $b) { ?>
                        <tr <?= ($b->id == $clicked) ? 'class="info"' : ''; ?>>
                            <td><?= ++$i; ?></td>
                            <td><p class="no-margin" style="line-height: 1;">
                                <?= $b->course; ?>
                                <?php if (!empty($b->category)) { ?>
                                    <br/><small class="text-muted"><?= $b->category; ?></small>
                                <?php } ?>
                                </p>
                            </td>
                            <td><?= ucfirst($b->type); ?></td>
                            <td><?= ($b->start_date) ? "{$b->start_date} ~ {$b->end_date}" : '--'; ?></td>
                            <td class="text-right"><?= GBP($b->booked_price); ?></td>
                            
                        </tr>
                        <?php if (!empty($b->student_remark) || !empty($b->admin_remark)) { ?>
                            <tr <?= ($b->id == $clicked) ? 'class="info"' : ''; ?>>
                                <td></td>
                                <td colspan="7">
                                    <?= showLabelTxt($b->student_remark, 'Student Note'); ?>
                                    <?php if (!empty($b->student_remark) && !empty($b->admin_remark)) echo '<br/>'; ?>
                                    <?= showLabelTxt($b->admin_remark, 'Admin Note'); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
