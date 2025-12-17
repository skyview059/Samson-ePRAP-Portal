<?php require_once('style.css.php'); ?>
<div class="booking-tab">
    <?= courseNavTab('booking'); ?>

    <div class="tab-content">

        <?php if ($courses) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Name</th>
                        <th class="text-center">Course Duration</th>
                        <th>Course Schedule & Time</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Day Left</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($courses as $c) { ?>
                        <tr>
                            <td data-course-id="<?= $c->id; ?>"><?php echo $c->category; ?></td>
                            <td><?php echo $c->course; ?></td>
                            <td class="text-center"><?php echo $c->course_duration; ?> Days</td>
                            <td><?php echo $c->start_datetime . ' ~ ' . $c->end_datetime; ?>
                                <sup><b>(<?php echo $c->duration; ?> hours)</b></sup></td>
                            <td class="text-right"><?php echo GBP($c->price); ?></td>
                            <td class="text-center"><?php echo isConfirmed($c->status); ?></td>
                            <td class="text-center"><?php echo $c->days_left; ?></td>
                            <td class="text-center">
                                <?php if ($c->status == 'Confirmed' && $c->days_left > 1) { ?>
                                    <span class="btn btn-danger btn-xs booked-cancel"
                                          data-id="<?= $c->id; ?>"
                                          data-price="<?= GBP($c->price); ?>"
                                          data-days-left="<?= $c->days_left; ?>"
                                          data-refund-percentage="<?= refundPercentage($c->days_left); ?>"
                                          data-refund-amount="<?= refundAmount($c->price, $c->days_left); ?>"
                                    <i class="fa fa-times"></i>
                                    Cancel
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <h3>Cancellation Policy</h3>

            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Days before course start date</th>
                    <th>Amount to be refunded</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>28 days or more</td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td>21 days or more</td>
                    <td>50%</td>
                </tr>
                <tr>
                    <td>14 days or more</td>
                    <td>25%</td>
                </tr>
                <tr>
                    <td>7 days or less</td>
                    <td>0%</td>
                </tr>
                </tbody>
            </table>


            <p class="text-red">You can re-schedule your course instead of cancelling
                <a href="mailto:info@samsoncourses.com">
                    <i class="fa fa-envelope-o"></i>
                    info@samsoncourses.com
                </a>
            </p>

            <?= $pagination; ?>
        <?php } else { ?>

            <div style="padding: 100px 25px;">
                <center>
                    <a href="<?= site_url('booking/course'); ?>" class="btn btn-success">
                        Click Here to Book Course
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </center>
            </div>
        <?php } ?>

        <p>&nbsp;</p>
        <p>&nbsp;</p>

        <?php if ($practices) { ?>
            <h3 class="text-center" style="font-weight: bold">Your Practice Courses</h3>
            <p>&nbsp;</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Expired</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($practices as $p) { ?>
                        <tr>
                            <td><?php echo $p->course; ?></td>
                            <td class="text-right"><?php echo GBP($p->total_pay); ?></td>
                            <td class="text-center"><?php echo deadline($p->expiry_date); ?></td>
                            <td class="text-center"><?php echo isConfirmed($p->status); ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('scenario-practice/exam/explore/' . $p->course_id); ?>"
                                   class="btn btn-primary"><i class="fa fa-play"></i> Start Practice</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <h3>Cancellation Policy</h3>

            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Days before course start date</th>
                    <th>Amount to be refunded</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>28 days or more</td>
                    <td>100%</td>
                </tr>
                <tr>
                    <td>21 days or more</td>
                    <td>50%</td>
                </tr>
                <tr>
                    <td>14 days or more</td>
                    <td>25%</td>
                </tr>
                <tr>
                    <td>7 days or less</td>
                    <td>0%</td>
                </tr>
                </tbody>
            </table>


            <p class="text-red">You can re-schedule your course instead of cancelling
                <a href="mailto:info@samsoncourses.com">
                    <i class="fa fa-envelope-o"></i>
                    info@samsoncourses.com
                </a>
            </p>

            <?= $pagination; ?>
        <?php } else { ?>

            <div style="padding: 100px 25px;">
                <center>
                    <a href="<?= site_url('booking/course'); ?>" class="btn btn-success">
                        Click Here to Book Course
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </center>
            </div>
        <?php } ?>
    </div>
</div>


<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="bookedCancelForm" id="bookedCancelForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Cancel Booked</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Refund Percentage </label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="refund_percentage" id="refund_percentage" class="form-control"
                                       readonly>
                                <span class="input-group-addon text-bold">% of <span id="price"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Refund Amount</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">&pound;</span>
                                <input type="text" name="refund_amount" id="refund_amount" class="form-control"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Cancel Remarks</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="student_remark" name="student_remark" rows="3">I need cancel my course due to personal problem or something else.</textarea>
                        </div>
                    </div>

                    <p class="text-red">You can re-schedule your course
                        <br/>instead of cancelling
                        <a href="mailto:info@samsoncourses.com">
                            <i class="fa fa-envelope-o"></i>
                            info@samsoncourses.com
                        </a>
                    </p>
                    <div id="respond"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="course_booked_id" id="course_booked_id"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">

                        <b>Close</b>
                    </button>
                    <button type="button" class="btn btn-primary" id="cancelBookedSave">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const refValue  = urlParams.get('status');

        if (refValue === 'success') {
            toastr.success("Payment made successfully.", "Success");
        }
    })

    $('body').on('click', '.booked-cancel', function () {
        $('#course_booked_id').val($(this).data('id'));
        $('#price').text($(this).data('price'));
        $('#refund_amount').val($(this).data('refund-amount'));
        $('#refund_percentage').val($(this).data('refund-percentage'));
        $('#cancelModal').modal('show');
    });

    /*Booked Cancel save*/
    $('body').on('click', '#cancelBookedSave', function (e) {
        e.preventDefault();
        var formData = $("#bookedCancelForm").serialize();

        $.ajax({
            url       : "booking/cancel",
            type      : 'post',
            dataType  : 'json',
            data      : formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                if (respond.Status === 'OK') {
                    location.href = '<?= base_url('booking'); ?>';
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });

    function daysdifference(firstDate, secondDate) {
        var startDay = new Date(firstDate);
        var endDay   = new Date(secondDate);

        var millisBetween = startDay.getTime() - endDay.getTime();
        var days          = millisBetween / (1000 * 3600 * 24);

        return Math.round(Math.abs(days));
    }


</script>

