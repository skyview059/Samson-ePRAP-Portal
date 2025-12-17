<?php require_once ('style.css.php');?>
<div class="booking-tab">
    <?= courseNavTab('booking/payment');?>    
    
    <div class="tab-content">        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="150">InvoiceID</th>
                        <th>Purchased on</th>
                        <th class="text-center">Total Item </th>
                        <th class="text-right">Paid Amount</th>
                        <th class="text-center">Gateway</th>                        
                        <th class="text-center">Status</th>
                        <th class="text-center">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p) { ?>
                    
                        <tr>
                            <td><?= $p->invoice_id; ?></td>
                            <td><?= globalDateTimeFormat($p->purchased_at); ?></td>
                            <td class="text-center"><?= $p->total_items; ?></td>
                            <td class="text-right"><?= GBP($p->total_pay); ?></td>                                                             
                            <td class="text-center"><?= $p->gateway; ?></td>                               
                            <td class="text-center"><?= $p->status; ?></td>                                        
                            <td class="text-center">                                
                                <?php echo viewOrPayInvoice($p->id,$p->status,$p->timeout); ?>
                            </td>                                        
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>        
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="paymentDetails"></div>
</div>
<?php require_once ('script.js.php'); ?>