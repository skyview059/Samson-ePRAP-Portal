<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Book Course as Admin</h3>
            </div>
            <div class="box-body">
                <?php foreach ($course_plans as $plan) { ?>
                    <fieldset>
                        <legend><?= $plan['category']; ?></legend>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">Select</th>
                                    <th width="320">Name</th>
                                    <th width="120" class="text-right">Price</th>
                                    <th>Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plan['courses'] as $c) { ?>
                                    <tr>
                                        <td>
                                            <input name="id[<?= $c['id']; ?>]" value="<?= $c['id']; ?>" data-price="<?= $c['price']; ?>" class="want2buy mark" type="checkbox"/>

                                        </td>
                                        <td><?= $c['name']; ?></td>
                                        <td class="text-right"><?= GBP($c['price']); ?></td>                          
                                        <td style="padding: 0;"><?= showBookingDates($c, 0); ?></td>                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </fieldset>
                <?php } ?>
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
                            <td width="145">Number of Course(s)</td>
                            <td width="5">:</td>
                            <td><span id="crs_quantity">0</span></td>
                        </tr>
                        <tr>
                            <td>Total Amount (£)</td>
                            <td>:</td>
                            <td><span id="total_amount" style="color:red; font-weight: bold;">0</span></td>
                        </tr>
                        <tr>
                            <td>Payment Status</td>
                            <td>:</td>
                            <td><div style="padding-top:8px"> 
                                <?= htmlRadio('payment_status', 'Pending', [
                                    'Pending' => 'Pending',
                                    'Confirmed' => 'Confirmed',                                
                                ]);?>
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
                                <textarea class="form-control" rows="5" name="admin_comments" placeholder="Comments">Booked by admin. Payment method cash.</textarea> </td>
                        </tr>

                        <tr style="background:none;">
                            <td></td>
                            <td></td>
                            <td>                                    
                                <button class="btn btn-primary" id="adminBookingSave">
                                    Continue
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="respond"></div>
            </div>
        </div>

    </div>
</div>       

