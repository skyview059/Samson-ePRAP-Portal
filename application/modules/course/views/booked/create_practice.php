<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Practice Booking <small> as Admin </small> <a href="<?php echo site_url(Backend_URL . 'course/booked') ?>"
                                                       class="btn btn-default">Back</a></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li><a href="<?php echo Backend_URL ?>course/booked">Booked</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">
    <form class="form-horizontal" method="post"
          action="<?php echo site_url(Backend_URL . 'course/booked/create_practice_action'); ?>">
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Book Course as Admin</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            <label for="practice_id" class="col-sm-2 control-label">Practice <sup>*</sup></label>
                            <div class="col-sm-10">
                                <select class="form-control" name="practice_id" id="practice_id" required>
                                    <option value="">-- Select Practice --</option>
                                    <?php
                                    foreach ($practices as $practice) {
                                        $selected = ($practice_id == $practice->id) ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . $practice->id . '">' . $practice->name . '</option>';
                                    }
                                    ?>
                                </select>
                                <?php echo form_error('practice_id'); ?>
                            </div>
                        </div>

                        <?php if ($practice_id): ?>

                            <div class="form-group">
                                <label for="student_id" class="col-sm-2 control-label">Student <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" name="student_id" id="student_id" required>
                                        <?php echo getDropDownStudentList($student_id); ?>
                                    </select>
                                    <?php echo form_error('student_id') ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="package_id" class="col-sm-2 control-label">Package <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="package_id" id="package_id" required>
                                        <option value="">-- Select Package --</option>
                                        <?php
                                        foreach ($packages as $package) {
                                            $package_title = $package->title . ' - ' . GBP($package->price) . ' (' . $package->duration . ')';
                                            echo '<option data-price="' . $package->price . '" value="' . $package->id . '">' . $package_title . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input name="package_price" id="package_price" type="hidden" value="0">
                                    <?php echo form_error('package_id'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="expiry_date" class="col-sm-2 control-label">Expiry Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control js_datepicker_limit_past" name="expiry_date" id="expiry_date"
                                               placeholder="Expiry Date" autocomplete="off">
                                    </div>
                                    <p class="help-block text-red">You can set an expiry date or leave it blank. If you leave it blank, the expiry date will be calculated based on the selected package.</p>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Your Cart Details</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Total Amount (£)</td>
                                <td>:</td>
                                <td><span id="total_amount" style="color:red; font-weight: bold;">0</span></td>
                            </tr>
                            <tr>
                                <td>Payment Gateway</td>
                                <td>:</td>
                                <td>
                                    <div style="padding-top:8px">
                                        <?= htmlRadio('payment_gateway', 'Free', [
                                            'Free'   => 'Free',
                                            'PayPal' => 'PayPal',
                                            'Stripe' => 'Stripe',
                                            'Bank'   => 'Bank',
                                            'Cash'   => 'Cash',
                                        ]); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Payment Status</td>
                                <td>:</td>
                                <td>
                                    <div style="padding-top:8px">
                                        <?= htmlRadio('payment_status', 'Confirmed', [
                                            'Pending'   => 'Pending',
                                            'Confirmed' => 'Confirmed',
                                        ]); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Comments</td>
                                <td>:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <textarea class="form-control" rows="5" name="admin_comments"
                                              placeholder="Comments">Booked by admin. Payment method cash.</textarea>
                                </td>
                            </tr>

                            <tr style="background:none;">
                                <td></td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary" id="adminBookingSave">
                                        Confirm Booking
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

</section>

<script type="text/javascript">
    $('#practice_id').change(function () {
        const practice_id    = $(this).val();
        window.location.href = '<?= Backend_URL ?>course/booked/create_practice?practice_id=' + practice_id;
    });

    $('#package_id').change(function () {
        const payment_gateway = $('input[name="payment_gateway"]:checked').val();
        let price             = $(this).find('option:selected').data('price');
        if (payment_gateway === 'Free') {
            price = 0;
        }
        $('#total_amount').text(price);
        $('#package_price').val(price);
    });

    $('input[name="payment_gateway"]').change(function () {
        const payment_gateway = $(this).val();
        let price             = $('#package_id').find('option:selected').data('price');
        if (payment_gateway === 'Free') {
            price = 0;
        }
        $('#total_amount').text(price);
        $('#package_price').val(price);
    });
</script>