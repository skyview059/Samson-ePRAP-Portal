<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            
            <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
            <h3 class="modal-title">Invoice ID # <?= $payment->invoice_id; ?></h3>
            
        </div>
        <div class="modal-body" id="invoice">            
            <div class="details">                    
                <div class="row">
                    <div class="col-md-3">Purchased on </div>
                    <div class="col-md-9">: &nbsp;<?= globalDateTimeFormat($payment->purchased_at) ?> </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3">Student Name </div>
                    <div class="col-md-9">: &nbsp;<?= $payment->full_name ?> </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3">Student Email </div>
                    <div class="col-md-9">: &nbsp;<?= $payment->email?> </div>
                </div>

                <div class="row">
                    <div class="col-md-3">Number of Courses </div>
                    <div class="col-md-9">: &nbsp;<?= $payment->total_items ?></div>
                </div>

                <div class="row">
                    <div class="col-md-3">Total Payment </div>
                    <div class="col-md-9">: &nbsp;<?= GBP($payment->total_pay); ?></div>
                </div>

                <div class="row">
                    <div class="col-md-3">Payment Gateway </div>
                    <div class="col-md-9">: &nbsp;<?= $payment->gateway; ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3">Payment Status </div>
                    <div class="col-md-9">: &nbsp;<?= $payment->status?></div>
                </div>

            </div>

            
                          
            <?php // pp($courses); ?>
            <h3>Booked Courses</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left">Category</th>
                            <th class="text-left">Course Name</th>
                            <th class="text-center">Course Date Time</th>
                            <th width="150" class="text-right">Price</th>                                                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $key => $course) {?>
                            <tr>
                                <td class="text-left"><?= $course->category; ?></td>       
                                <td class="text-left"><?= $course->course; ?></td>
                                <td class="text-center"><?= $course->start_date; ?></td>
                                <td class="text-right"><?= GBP($course->price); ?></td>                                                                         
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>



            <p><b>Comments:</b><br/>
            <?= $payment->admin_comments; ?></p>

        </div>        
    </div>
</div>