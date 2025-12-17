<?php // load_module_asset('course','css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.css">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px !important;
        }

        .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus {
            outline: unset;
        }

        .d-none {
            display: none;
        }
    </style>

    <div class="container">
        <form id="bookingForm" method="post" action="<?php echo site_url('course-booking-action'); ?>" target="_parent"
              name="bookingForm" class="form-horizontal mt-70">

            <input type="hidden" name="first_promoter" value="<?php echo $this->input->get('ref'); ?>">

            <div class="row">
                <div class="col-md-8">
                    <?php if ( !getLoginStudentData( 'student_id' ) ): ?>
                        <fieldset id="personal_info">
                        <legend><h4> Personal Information </h4></legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-15">
                                    <input type="text" placeholder="First Name" name="first_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-15">
                                    <input type="text" placeholder="Last Name" name="last_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-15">
                            <input type="email" placeholder="Email" name="email" class="form-control">
                        </div>
                        <div class="row">
                            <div class="mb-15 col-md-6">
                                <select name="phone_code" class="form-control select2">
                                    <?php $phone_code = '';
                                    echo getPhoneCode($phone_code); ?>
                                </select>
                            </div>

                            <div class="mb-15 col-md-6">
                                <input type="text" placeholder="Phone" name="phone" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <select name="country_id" class="form-control select2" id="country_id">
                                    <?php echo getDropDownCountries('', 'Select Country of Origin'); ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <?php endif;?>

                    <fieldset>
                        <legend><h4> Courses </h4></legend>
                        <table class="table table-bordered table-striped table-condensed course_table">
                            <thead>
                            <tr>
                                <th width="40"> Select</th>
                                <th width="300"> Name</th>
                                <th width="100" class="text-center"> Price</th>
                                <th> Dates & Available Seat</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($course_plans as $plan) { ?>
                                <?php foreach ($plan['courses'] as $c) {
                                       $date_slot = showBookingDates($c, $course_payment_id) !== '<p><small><em>No Date Found to Enroll</em><small></p>';
                                  ?>
                                    <tr>
                                        <td>
                                            <input name="id[<?= $c['id']; ?>]"
                                                   class="want2buy form-control"
                                                   type="checkbox"
                                                   value="<?= $c['id']; ?>"
                                                   data-price="<?= $c['price']; ?>"
                                                   <?= !$date_slot ? 'disabled' : ''?>
                                                <?php echo $c['isSelected'] ? 'checked="checked"' : ''; ?> />
                                        </td>
                                        <td><?= $c['name']; ?></td>
                                        <td class="text-center"><?= GBP($c['price']); ?></td>
                                        <td style="padding: 0 10px;">
                                            <?= showBookingDates($c, $course_payment_id); ?>
                                        </td>
                                    </tr>
                           <?php } }?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset>
                        <legend><h4>Your Cart Details</h4></legend>
                        <table class="table table-striped cart">
                            <tbody>
                            <tr>
                                <td width="145">Course Qty</td>
                                <td width="5">:</td>
                                <td>
                                    <span id="crs_quantity">0</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Amount (£)</td>
                                <td>:</td>
                                <td>
                                    <span id="total_amount" style="color:red; font-weight: bold;">0</span>
                                    <input type="hidden" name="total_amount" id="input_total_amount">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <!--Start Coupon Apply-->
                    <div class="coupon" style="margin-bottom: 20px;">
                        <div class="input-group coupon_apply">
                            <input type="text" class="form-control" placeholder="Promo code" id="coupon">
                            <span class="input-group-addon" style="padding: 0">
                                <button class="btn btn-secondary" id="coupon_apply" type="button"> Apply </button>
                            </span>
                        </div>

                        <input type="hidden" class="form-control coupon_applied" value="" name="coupon_name">

                        <small class="d-none coupon_error_msg" style="color: red"> <b> Promo code is invalid.</b> </small>
                    </div>
                    <!--End Coupon Apply-->

                    <div class="note">
                        <small style="color: #ea8b50; display: block; margin-bottom: 10px"> <b>Note:</b> If you have multiple coupon code then booking separately. </small>
                    </div>

                    <textarea class="form-control" rows="3" name="customer_comments" style="margin-bottom: 10px"
                              placeholder="Comments"></textarea>

                    <div class="mb-15">
                        <div class="icheck-primary mb-15">
                            <input type="checkbox" id="personal_data" name="personal_data" />
                            <label for="personal_data">
                                <small>
                                    I want to be discovered and contacted by employers/recruitment managers.
                                    <p>All personal data collected by samsoncourses is governed by our <a href="https://www.samsoncourses.com/privacy-policy" style="color: #4AA2F1" target="_blank"> privacy policy</a>. By using this site you consent to use of personal data in accordance with the <a href="https://www.samsoncourses.com/privacy-policy" style="color: #4AA2F1" target="_blank"> privacy policy</a>.</p>
                                </small>
                            </label>
                        </div>

                        <div class="icheck-primary mb-15">
                            <input type="checkbox" id="terms_and_conditions" name="terms_and_conditions" />
                            <label for="terms_and_conditions">
                                <small>
                                    I have read and agreed to the
                                    <a href="https://www.samsoncourses.com/terms-and-conditions" style="color: #4AA2F1" target="_blank"> Terms & Conditions </a>
                                    and
                                    <a href="https://www.samsoncourses.com/privacy-policy" style="color: #4AA2F1" target="_blank"> Privacy Policy.</a>
                                </small>
                            </label>
                        </div>
                    </div>

                    <button id="guestBookingSave" class="btn btn-primary mb-70">
                        Continue to Payment
                        <i class="fa fa-arrow-right"></i>
                    </button>

                    <div id="respond"></div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        $('#coupon_apply').on('click', function () {

            let value = $('#coupon').val();

            $.ajax({
                type: "GET",
                dataType: "json",
                data: {coupon: value},
                url: "check-promo-code",

                beforeSend() {
                    $('.coupon_error_msg').addClass('d-none');
                },
                success: function (response) {
                    if (response.promocode === null){
                        $('.coupon_error_msg').removeClass('d-none');
                        $('.coupon_error_msg').html('<b>Promo code is invalid.</b>');
                        return;
                    }

                    if (response.promocode.already_used && 'yes' !== response.promocode.use_multiple){
                        $('.coupon_error_msg').removeClass('d-none');
                        $('.coupon_error_msg').html('<b>You have already use this coupon.</b>');
                        return;
                    }

                    const date = new Date();
                    const year = date.getFullYear();
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day   = date.getDate().toString().padStart(2, '0');

                    const now = `${year}-${month}-${day}`;

                    if (response.promocode && (response.promocode.start_date <= now && now <= response.promocode.end_date)) {
                        $('.coupon_applied').val(response.promocode.code);

                        let course_id = response.promocode.course_id;
                        let total_amount = 0;
                        let match_course = false;

                        $(".want2buy").each(function (key, elem) {
                            if ($(elem).is(':checkbox:checked')) {
                                let id = $(this).val();
                                let price = parseInt($(elem).attr('data-price'));

                                if (course_id === id) {
                                    match_course = true;

                                    $('.coupon').addClass('d-none')
                                    $('.note').addClass('d-none');

                                    if (response.promocode.discount_type === 'Fixed') {
                                        total_amount += price - response.promocode.amount;
                                    }

                                    if (response.promocode.discount_type === 'Percentage') {
                                        let discountAmount = (price * response.promocode.amount) / 100;
                                        total_amount += price - discountAmount;
                                    }
                                } else {
                                    total_amount += price;
                                }

                                $('.coupon_error_msg').removeClass('d-none');
                            }
                        });

                        let html = `<tr>
                                        <td> Discount (${response.promocode.code}) <span style="color:red" onclick="cancel_coupon()"> <i class="fa fa-times-circle" aria-hidden="true"></i> </span></td>
                                        <td>:</td>
                                        <td>
                                            ${response.promocode.amount} ${response.promocode.discount_type === 'Percentage' ? '%' : ''}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> Total </td>
                                        <td>:</td>
                                        <td id="car_total_amount">
                                            ${total_amount}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="display: block"><strong>Coupon applied for:</strong></span>
                                            ${response.promocode.name}
                                        </td>
                                    </tr>`

                        if (match_course){
                            $('.cart').append(html);
                        }

                    } else {
                        $('.coupon_error_msg').removeClass('d-none')
                        $('.coupon_error_msg').html('<b>ddf.</b>');
                    }
                }
            })
        });

        function cancel_coupon() {
            $('.cart tr:last-child').remove();
            $('.cart tr:last-child').remove();
            $('.cart tr:last-child').remove();
            $('.coupon').removeClass('d-none');
            $('.note').removeClass('d-none');
            $('.coupon_error_msg').addClass('d-none');
        }
    </script>

<?php load_module_asset('course', 'js', 'script.common.js.php'); ?>