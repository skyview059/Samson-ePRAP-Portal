<?php load_module_asset('course','css'); ?>
<div class="booking-tab">
    <?= courseNavTab('booking/course');?> 
    
    <div class="tab-content">   
        <form id="bookingForm" name="bookingForm" class="form-horizontal">
            <div class="row">
                <div class="col-md-8">
                    <?php foreach ($course_plans as $plan){ ?>
                        <h1><?= $plan['category']; ?></h1>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">Select</th>
                                    <th width="320">Name</th>
                                    <th width="120" class="text-right">Price</th>
                                    <th>Dates & Available Seat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plan['courses'] as $c) { ?>
                                    <tr>
                                        <td>
                                            <input name="id[<?= $c['id']; ?>]" class="want2buy form-control" type="checkbox"
                                                   value="<?= $c['id']; ?>" 
                                                   data-price="<?= $c['price']; ?>"
                                                   <?php echo $c['isSelected'] ? 'checked="checked"' : ''; ?> />
                                        </td>
                                        <td><?= $c['name']; ?></td>
                                        <td class="text-right"><?= GBP($c['price']); ?></td>                          
                                        <td style="padding: 0;">                                        
                                        <?= showBookingDates($c, $course_payment_id); ?></td>                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <h1>Your Cart Details</h1>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td width="145">Course Qty</td>
                                <td width="5">:</td>
                                <td><span id="crs_quantity"><?= !empty($payment_info['total_items']) ? $payment_info['total_items'] : 0; ?></span></td>
                            </tr>
                            <tr>
                                <td>Total Amount (£)</td>
                                <td>:</td>
                                <td><span id="total_amount" style="color:red; font-weight: bold;"><?= !empty($payment_info['total_pay']) ? $payment_info['total_pay'] : 0; ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="3">Comments :
                                    <textarea class="form-control" rows="3" name="admin_comments" placeholder="Comments"><?= !empty($payment_info['admin_comments']) ? $payment_info['admin_comments'] : null; ?></textarea> </td>
                            </tr>

                            <tr style="background:none;">                                
                                <td colspan="3">
                                    <input type="hidden" name="course_payment_id" id="course_payment_id" value="<?= $course_payment_id;?>"/>
                                    <button id="bookingSave" class="btn btn-primary">
                                        Continue to Payment
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="respond"></div>
                </div>
            </div> 
        </form>
    </div>
</div>
<?php load_module_asset('course','js','script.common.js.php'); ?>