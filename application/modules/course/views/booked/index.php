<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
    .form-group {
        overflow: hidden;
    }
</style>
<?php load_module_asset('exam', 'css'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1> Course Booking List <small>Control panel</small>
        <a class="btn btn-primary" href="<?= Backend_URL; ?>course/booked/create">
            + Book Course as Admin
        </a>
    </h1>

    <ol class="breadcrumb">
        <li><a href="<?= site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?= Backend_URL ?>course">Course</a></li>
        <li class="active">Booked</li>
    </ol>
</section>

<section class="content personaldevelopment">

    <?= bookingTabAsStatus($status); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <!--<pre><?php // $sql; ?></pre>-->
            <?php $this->load->view('index_filter'); ?>
        </div>

        <div class="box-body">
            <?= $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th>Student</th>
                        <th>Booked Date</th>
                        <th>Course Name</th>
                        <th>Type</th>
                        <th>Course Date</th>
                        <th class="text-center">Days Left</th>
                        <th>Status</th>
                        <th class="text-right">Amount</th>
                        <th>Cancelled on</th>
                        <th>IsAttend?</th>
                        <th class="text-center" width="150">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($bookeds as $booked) { ?>
                        <tr>
                            <td><?= ++$start ?></td>
                            <td><p class="no-margin"><?= $booked->full_name; ?><br/>
                                    <em><?= $booked->email; ?> | <?= "{$booked->phone_code}-{$booked->phone}"; ?></em>
                                </p>
                            </td>
                            <td><?= $booked->booked_on; ?></td>
                            <td>
                                <a href="admin/course/read/<?= $booked->course_id; ?>">
                                    <?= $booked->course; ?>
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </td>
                            <td><?= ucfirst($booked->type); ?></td>
                            <td><?= $booked->start_date . ' ~ ' . $booked->end_date; ?></td>
                            <td class="text-center"><?= $booked->days_left; ?></td>
                            <td><?= isConfirmed($booked->status); ?></td>
                            <td class="text-right"><?= GBP($booked->paid); ?></td>
                            <td><?= globalDateFormat($booked->cancelled_at); ?></td>
                            <td>
                                <span class="course_attendance btn btn-block <?= ($booked->attendance === 'Yes') ? 'btn-success' : 'btn-warning' ?> btn-xs"
                                      id="course_attendance_<?= $booked->id; ?>" data-id="<?= $booked->id; ?>"
                                      data-attendance="<?= ($booked->attendance === 'Yes') ? 'No' : 'Yes'; ?>"
                                      onclick="return confirm('Confirm Attendance Update')">
                                <?= ($booked->attendance == 'Yes') ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-fw fa-close"></i>'; ?>
                                <?= $booked->attendance; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($booked->status != 'Cancelled') { ?>
                                    <button type="button" class="btn btn-xs btn-warning rechedule"
                                            data-id="<?= $booked->id; ?>" title="ReSchedule">
                                        <i class="fa fa-fw fa-random"></i>
                                    </button>

                                    <button type="button" class="btn btn-xs btn-primary admin_note"
                                            data-id="<?= $booked->id; ?>"
                                            data-note="<?= htmlentities($booked->admin_remark ?: ''); ?>"
                                            title="Admiin Note">
                                        <i class="fa fa-file"></i>
                                    </button>

                                    <button type="button" class="btn btn-xs btn-danger booked-cancel"
                                            data-id="<?= $booked->id; ?>"
                                            data-note="<?= htmlentities($booked->admin_remark ?: ''); ?>"
                                            title="Cancel">
                                        <i class="fa fa-fw fa-window-close"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-success manual-payment"
                                            data-booking-id="<?= $booked->id; ?>"
                                            data-payment-id="<?= $booked->payment_id; ?>"
                                            data-amount="<?= ($booked->paid); ?>"
                                            data-gateway="<?= ($booked->gateway); ?>"
                                            data-note="<?= htmlentities($booked->admin_remark ?: ''); ?>"
                                            title="Verify Manual Payment">
                                        <i class="fa fa-check-square-o"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8">
                                <?= showLabelTxt($booked->student_remark, 'Student Note'); ?>
                                <?= showLabelTxt($booked->admin_remark, 'Admin Note'); ?>
                                <?= isPaymentReturned($booked->is_payment_returned, $booked->refund_amount); ?>
                                <?= smsNotify($booked->sms_notifed); ?>
                            </td>
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
                    <?= $pagination ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Manual Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="bookedPaymentModal" id="bookedPaymentModal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Manual Payment Confirmation</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">Booking Status</div>
                            <div class="col-md-9">
                                <label><input type="radio" name="booking_status" value="Pending"> Pending &nbsp;&nbsp;
                                </label>
                                <label><input type="radio" name="booking_status" value="Confirmed" checked> Confirmed
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">Payment Status</div>
                            <div class="col-md-9">
                                <label><input type="radio" name="payment_status" value="Paid"> Yes, Paid &nbsp;&nbsp;
                                </label>
                                <label><input type="radio" name="payment_status" value="Due" checked> No, Due </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">Paid Amount</div>
                            <div class="col-md-9">
                                <input type="number" name="payment_amount" id="payment_amount" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">Paid using</div>
                            <div class="col-md-9">
                                <?= gatewaysRadio(); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">Remarks</div>
                            <div class="col-md-9">
                                <textarea class="form-control" id="admin_txt" name="admin_remark" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="respond"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="payment_id" id="payment_id"/>
                    <input type="hidden" name="course_booked_id" id="course_booked_id"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBookingPayment">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="bookedCancelForm" id="bookedCancelForm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Cancel Booked</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">Is Payment Return</div>
                            <div class="col-md-9">
                                <label><input type="radio" class="is_payment_returned" id="is_payment_returned_yes"
                                              name="is_payment_returned" value="Yes"> Yes &nbsp;&nbsp; </label>
                                <label><input type="radio" class="is_payment_returned" id="is_payment_returned_no"
                                              name="is_payment_returned" value="No" checked> No </label>
                            </div>
                        </div>
                        <div class="form-group" id="refund_amount_show" style="display: none;">
                            <div class="col-md-3">Refund Amount</div>
                            <div class="col-md-9">
                                <input type="number" name="refund_amount" id="refund_amount" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">Cancel Remarks</div>
                            <div class="col-md-9">
                                <textarea class="form-control" id="admin_txt" name="admin_remark" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="respond"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="course_booked_id" id="course_booked_id"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cancelBookedSave">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Admin Note Modal -->
<div class="modal fade" id="admin_note" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="admin_note" id="admin_note_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Admin Note</h3>
                </div>
                <div class="modal-body">
                    <div id="respond"></div>
                    <textarea class="form-control" id="admin_note_txt" name="admin_remark" rows="5"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="course_booked_id" id="course_booked_id_an"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAdminNote">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ReSchedule Modal -->
<div class="modal fade" id="rechedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" id="content">

    </div>
</div>


<script type="text/javascript">
    $('body').on('click', '.manual-payment', function (e) {
        var course_booked_id = $(this).data('booking-id');
        $('#course_booked_id').val(course_booked_id);
        $('#payment_amount').val($(this).data('amount'));
        $('#payment_id').val($(this).data('payment-id'));
        var gateway = $(this).data('gateway');
        $(`#${gateway}`).prop("checked", true);
        $('#admin_txt').val($(this).data('note'));
        $('#paymentModal').modal('show');
    });

    $('body').on('click', '.booked-cancel', function (e) {
        var course_booked_id = $(this).data('id');
        $('#course_booked_id').val(course_booked_id);
        $('#admin_txt').val($(this).data('note'));
        $('#cancelModal').modal('show');
    });

    /*save Booking Payment by Admin*/
    $('body').on('click', '#saveBookingPayment', function (e) {
        e.preventDefault();
        var formData = $("#bookedPaymentModal").serialize();

        $.ajax({
            url       : "admin/course/booked/save_manual_payment",
            type      : 'post',
            dataType  : 'json',
            data      : formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                if (respond.Status === 'OK') {
                    location.href = '<?= base_url('course/booked'); ?>';
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });


    $('body').on('click', '.is_payment_returned', function () {
        var is_payment = $(this).val();
        if (is_payment === 'Yes') {
            $("#refund_amount_show").show();
        } else {
            $("#refund_amount_show").hide();
        }
    });


    /*Booked Cancel save*/
    $('body').on('click', '#cancelBookedSave', function (e) {
        e.preventDefault();
        var formData = $("#bookedCancelForm").serialize();

        $.ajax({
            url       : "admin/course/booked/cancel",
            type      : 'post',
            dataType  : 'json',
            data      : formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                if (respond.Status === 'OK') {
                    location.href = '<?= base_url('course/booked'); ?>';
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });


    /*Add Admin Note to Booking */
    $('body').on('click', '.admin_note', function (e) {
        var course_booked_id = $(this).data('id');
        $('#course_booked_id_an').val(course_booked_id);
        $('#admin_note_txt').val($(this).data('note'));
        $('#admin_note').modal('show');
    });

    $('body').on('click', '#saveAdminNote', function (e) {
        e.preventDefault();
        var formData = $("#admin_note_form").serialize();

        $.ajax({
            url       : "admin/course/booked/admin_note",
            type      : 'post',
            dataType  : 'json',
            data      : formData,
            beforeSend: function () {
                $('#respond_an').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                if (respond.Status === 'OK') {
                    location.href = '<?= base_url('course/booked'); ?>';
                } else {
                    $('#respond_an').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });

    /*Add Admin Note to Booking */
    $('body').on('click', '.rechedule', function (e) {
        var id = $(this).data('id');
        $('#rechedule').modal('show');

        $.ajax({
            url     : "admin/course/booked/reschedule",
            type    : 'post',
            dataType: 'html',
            data    : {id: id},
            success : function (respond) {
                $('#content').html(respond);
            }
        });
    });

    $(document).on('click', '.course_attendance', function (e) {
        e.preventDefault();
        var id         = $(this).attr("data-id")
        var attendance = $(this).attr("data-attendance")
        $.ajax({
            url       : "admin/course/booked/attendance",
            type      : 'post',
            dataType  : 'json',
            data      : {id: id, attendance: attendance},
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                if (respond.Status === 'OK') {
                    location.reload();
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });

</script>

