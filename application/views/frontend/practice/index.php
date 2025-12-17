<?php load_module_asset( 'course', 'css') ;?>
<div class="booking-tab">
    <?= practiceNavTab('practices');?>   
    <div class="tab-content">     
        <?php if($schedules) { ?>
        <div class="table-responsive">
            
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>                        
                        <th>Category</th>
                        <th>Label/Title</th>
                        <th class="text-center">Centre</th>
                        <th>Schedule & Seat </th>
                        <th>Duration </th>
                        <th class="text-center">Day Left</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $s) { ?>
                        <tr>                            
                            <td><?= $s->category; ?></td>
                            <td><?= $s->label; ?></td>
                            <td class="text-center"><?= $s->centre; ?></td>
                            <td><?= $s->schedule; ?> (<?= $s->seat; ?>) </td>    
                            <td><?= $s->duration; ?> hours</td>    
                            <td class="text-center"><?= $s->days_left; ?> Days</td>
                            <td class="text-center">
                                <span id="<?= $s->id; ?>" class="btn btn-xs book-practice btn-success">
                                    <i class="fa fa-edit"></i>
                                    Book Seat
                                </span>
                            </td>
                        </tr>                        
                    <?php }?>
                </tbody>
            </table>
        </div>  
        
        <?php } else { ?>
        <p class="alert alert-info"><i class="fa fa-bullhorn"></i> No Practice Scheduled for Booking</p>
        <?php } ?>
    </div>
</div>


<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="practiceCancelForm" id="practiceBookingForm" class="form-horizontal">
            <input type="hidden" name="practice_schedule_id" id="practice_schedule_id" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Book Your Seat for Practice</h3>
                </div>
                <div class="modal-body">

                    <p>I confirm that I have done the course with <?= getSettingItem('SiteTitle'); ?></p>
                         
                    <div id="respond"></div>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">                        
                        <b>Close</b>
                    </button>
                    <button type="button" class="btn btn-primary" id="send-booking">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php load_module_asset('practice', 'js'); ?>

