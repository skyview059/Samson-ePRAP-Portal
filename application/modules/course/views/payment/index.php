<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Payment  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li class="active">Payment</li>
    </ol>
</section>
<style>
    div#invoice div.row {
        border-bottom: 1px solid #f5f5f5;
        padding: 5px;
        margin: 0;
    }
</style>
<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">                                   
            <div class="text-right">
                <form action="<?php echo site_url(Backend_URL . 'course/payment'); ?>" class="form-inline" method="get">
                    <div class="input-group flatpickr" style="width: 20%;">
                        <div class="input-group-addon" data-toggle style="padding: 0;">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                            </button>
                        </div>

                        <input type="date" name="purchased" aria-label="purchased" data-input placeholder="Purchased Date" value="<?php echo $purchased; ?>"
                               class="form-control">

                        <div class="input-group-addon" data-clear style="padding: 0;">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <select name="gateway" aria-label="gateway" class="form-control">
                        <option disabled selected>--Select Gateway--</option>
                        <option value="PayPal" <?php echo $gateway === 'PayPal' ? 'selected' : '' ?> > PayPal </option>
                        <option value="WorldPay" <?php echo $gateway === 'WorldPay' ? 'selected' : '' ?> > WorldPay </option>
                        <option value="Free" <?php echo $gateway === 'Free' ? 'selected' : '' ?> > Free </option>
                        <option value="Stripe" <?php echo $gateway === 'Stripe' ? 'selected' : '' ?> > Stripe </option>
                        <option value="Cash" <?php echo $gateway === 'Cash' ? 'selected' : '' ?> > Cash </option>
                        <option value="Bank" <?php echo $gateway === 'Bank' ? 'selected' : '' ?> > Bank </option>
                    </select>

                    <select name="status" aria-label="status" class="form-control">
                        <option disabled selected>--Select Status--</option>
                        <option value="Paid" <?php echo $status === 'Paid' || $status === ''? 'selected' : '' ?> > Paid </option>
                        <option value="Due" <?php echo $status === 'Due' ? 'selected' : '' ?> > Due </option>
                    </select>

                    <input type="text" class="form-control" name="q" value="<?php echo $q; ?>" placeholder="InvoiceID/name/email/Promo Code" aria-label="q">

                    <span>
                        <a href="<?php echo site_url(Backend_URL . 'course/payment'); ?>" class="btn btn-default">Reset</a>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>InvoiceID</th>
                            <th>Student</th>
                            <th>Purchased on</th>
                            <th class="text-center">Items</th>
                            <th class="text-right">Amount</th>
                            <th>Gateway</th>
                            <th>Status</th>
                            <th>Promo Code</th>
                            <th>Admin Comments</th>
                            <th class="text-center">Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($payments as $payment) { ?>
                            <tr>
                                <td><?= ++$start; ?></td>
                                <td><?= $payment->invoice_id; ?></td>
                                <td>
                                    <p class="no-margin"><?= $payment->full_name; ?><br/>
                                        <em><?= $payment->email; ?> | <?= "{$payment->phone_code}-{$payment->phone}"; ?></em>
                                    </p>
                                </td>
                                <td><?= globalDateTimeFormat($payment->purchased_at); ?></td>
                                <td class="text-center"> <span class="badge badge-primary"> <?= $payment->total_items; ?> </span> </td>
                                <td class="text-right"><?= GBP($payment->total_pay); ?></td>                                
                                <td><?= $payment->gateway; ?></td>
                                <td><?= $payment->status; ?></td>

                                <td>
                                    <?php
                                        if ($payment->promo_code){
                                         echo $payment->promo_code . ' - '.'<em>' . $payment->course_name . '</em>';
                                        }
                                    ?>
                                </td>

                                <td><?= $payment->admin_comments; ?></td>
                                <td class="text-center"><?= viewOrPayInvoice($payment->id,$payment->status,$payment->timeout); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>          
        </div>
        <div class="box-footer">
            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Course: <?= $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
    </div>

</section>
<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="paymentDetails"></div>
</div>

<?php load_module_asset('course', 'js'); ?>
<?php load_module_asset('course', 'js', 'script.common.js.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $('.flatpickr').flatpickr({
        altInput: true,
        dateFormat: "Y-m-d",
        enableTime: false,
        altFormat: "F j, Y",
        wrap: true,
        mode: "range"
    });
</script>
