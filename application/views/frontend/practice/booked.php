<?php load_module_asset( 'course', 'css') ;?>
<div class="booking-tab">
    <?= practiceNavTab('practices/booked');?>    
    
    <div class="tab-content"> 
                
        
        <?php if($schedules) { ?>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>                        
                        <th>Type of Booking</th>
                        <th>Slot</th>
                        <th class="text-center">Centre</th>
                        <th>Time </th>
                        <th class="text-center">Day Left</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $s) { ?>
                        <tr class="<?= strtolower($s->status); ?>">
                            <td><?php echo $s->category; ?></td>
                            <td><?php echo $s->label; ?></td>
                            <td class="text-center"><?php echo $s->centre; ?></td>
                            <td><?php echo $s->schedule; ?> </td>    
                            <td class="text-center"><?php echo $s->days_left; ?> Days</td>
                            <td class="text-center">
                                <?php // $s->status .' '. $s->days_hrs;  ?>
                                <?php if($s->status == 'Enrolled' && $s->days_hrs > 12 ){ ?>
                                <span id="<?= $s->id; ?>" class="btn btn-xs cancel-practice btn-danger">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </span>
                                
                                <?php } else if($s->status == 'Enrolled' && $s->days_hrs < 12 ){ ?>
                                <span class="btn btn-xs disabled btn-danger">
                                    <i class="fa fa-lock"></i>
                                    Passed
                                </span>                                
                                <?php } else { ?>
                                <span class="btn btn-xs btn-danger disabled">
                                    <i class="fa fa-times"></i>
                                    Canceled
                                </span>                                
                                <?php } ?>
                            </td>
                        </tr>                        
                    <?php }?>
                </tbody>
            </table>
        </div>                     
        <?php } else { ?>
        <p class="alert alert-info"><i class="fa fa-bullhorn"></i> No Practice Seat Booking Found</p>
        <?php } ?>
    </div>
</div>


<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="practiceCancelForm" id="practiceCancelForm" class="form-horizontal">
            <input type="hidden" name="practice_booked_id" id="practice_booked_id" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Cancel Booked</h3>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Cancel Remarks</label>     
                        <div class="col-md-8"> 
                            <textarea class="form-control" id="remark" name="remark" rows="3">I want to cancel my booking due to personal problem or something else.</textarea>
                        </div>
                    </div>                    
                    <div id="respond"></div>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">                        
                        <b>Close</b>
                    </button>
                    <button type="button" class="btn btn-primary" id="send-cancel">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php load_module_asset('practice', 'js'); ?>