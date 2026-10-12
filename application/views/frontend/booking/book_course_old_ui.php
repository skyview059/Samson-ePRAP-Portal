<?php // load_module_asset('course','css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.css">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px !important;
        }
        .want2buy,
        .practice2buy {
            height: 35px !important;
            width: 35px  !important;
        }

        .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus {
            outline: unset;
        }

        .d-none {
            display: none;
        }

        /* old price when a promo code discount is applied */
        .old_price {
            text-decoration: line-through;
            color: #999 !important;
            font-weight: normal !important;
        }
    </style>

    <div class="container">
        <form id="bookingForm" method="post" action="<?php echo site_url('course-booking-action'); ?>" target="_parent"
              name="bookingForm" class="form-horizontal mt-70">

            <input type="hidden" name="first_promoter" value="<?php echo html_escape($this->input->get('ref')); ?>">
            <?php echo form_guard_fields('booking'); ?>

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
                                    <?php echo getPhoneCode(); ?>
                                </select>
                            </div>

                            <div class="mb-15 col-md-6">
                                <input type="text" placeholder="Phone" name="phone" class="form-control">
                            </div>
                        </div>

                        <?php /*
                        <div class="row">
                            <div class="col-md-6">
                                <select name="country_id" class="form-control select2" id="country_id">
                                    <?php echo getDropDownCountries('', 'Select Country'); ?>
                                </select>
                            </div>
                        </div>
                        */ ?>

                    </fieldset>
                    <?php endif;?>

                    <fieldset>
                        <legend><h4> Live Courses </h4></legend>
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
                                       // no dates -> course can't be booked (same check as book_course_new_ui.php)
                                       $date_slot = !empty(json_decode($c['dates']));
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
                           <?php } } ?>
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
                                <td width="225">Course Qty</td>
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
                        <div class="icheck-primary mb-15 hidden d-none">
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
                                    <a href="https://www.geniusclass.co.uk/page/terms-conditions" style="color: #4AA2F1" target="_blank"> Terms & Conditions </a>
                                    and
                                    <a href="https://www.geniusclass.co.uk/page/privacy-policy" style="color: #4AA2F1" target="_blank"> Privacy Policy.</a>
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

        let appliedCoupon = null;

        function couponError(msg) {
            $('.coupon_error_msg').removeClass('d-none').html('<b>' + msg + '</b>');
        }

        // the server discounts one course: the last promo code course that is selected
        function findCouponCourse(courseIDs) {
            let matched = null;
            (courseIDs || []).forEach(function (courseID) {
                const elem = $('.want2buy[value="' + parseInt(courseID) + '"]:checked');
                if (elem.length) {
                    matched = elem;
                }
            });
            return matched;
        }

        $('#coupon_apply').on('click', function () {

            let value = $('#coupon').val();

            $.ajax({
                type: "GET",
                dataType: "json",
                data: {coupon: value},
                url: "check-promo-code",

                beforeSend() {
                    $('.coupon_error_msg').addClass('d-none');
                    $('#coupon_apply').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Checking...');
                },
                complete() {
                    $('#coupon_apply').prop('disabled', false).html(' Apply ');
                },
                error() {
                    couponError('Could not check the promo code. Please try again.');
                },
                success: function (response) {
                    const promo = response.promocode;

                    if (promo === null){
                        couponError('Promo code is invalid.');
                        return;
                    }

                    if (promo.already_used && 'yes' !== promo.use_multiple){
                        couponError('You have already use this coupon.');
                        return;
                    }

                    const isLoggedIn = <?= json_encode(auth('student')); ?>;

                    // check:: this coupon for this auth user or not
                    if (parseInt(promo.is_special) === 1 && (!isLoggedIn || !promo.students.includes(parseInt(isLoggedIn.id)))){
                        couponError('This coupon for special user.');
                        return;
                    }

                    const date = new Date();
                    const year = date.getFullYear();
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day   = date.getDate().toString().padStart(2, '0');

                    const now = `${year}-${month}-${day}`;

                    if (!(promo.start_date <= now && now <= promo.end_date)) {
                        couponError('Promo code expired.');
                        return;
                    }

                    if ($('.want2buy:checked').length === 0) {
                        couponError('Please select a course before applying a promo code.');
                        return;
                    }

                    if (!findCouponCourse(promo.courses)) {
                        couponError('The promo code is not for this course.');
                        return;
                    }

                    appliedCoupon = {
                        code: promo.code,
                        amount: parseFloat(promo.amount),
                        discount_type: promo.discount_type,
                        courses: promo.courses || [],
                    };

                    $('.coupon_applied').val(promo.code);
                    $('.coupon').addClass('d-none');
                    $('.note').addClass('d-none');
                    showCoupon();
                }
            })
        });

        // draw the discount rows from the cart total calculated by cartDetails()
        function showCoupon() {
            $('.cart .coupon_row').remove();
            $('#total_amount').removeClass('old_price');
            if (!appliedCoupon) {
                return;
            }

            const matched = findCouponCourse(appliedCoupon.courses);
            const base = parseFloat($('#input_total_amount').val()) || 0;
            let total = base;
            const discounted = matched && $('#slot_' + matched.val()).find('.ckbx:checked').length > 0;

            // discount only when the matched course is in the total (a date is selected)
            if (discounted) {
                $('#total_amount').addClass('old_price');
                const price = parseFloat(matched.attr('data-price'));

                let discount = 0;

                if (appliedCoupon.discount_type === 'Fixed') {
                    discount = appliedCoupon.amount;
                }

                if (appliedCoupon.discount_type === 'Percentage') {
                    discount = price * appliedCoupon.amount / 100;
                }

                // never discount more than the course price (same as the server)
                total = base - Math.min(discount, price);
            }

            total = Math.round(total * 100) / 100;
            const courseName = matched ? matched.closest('tr').find('td').eq(1).text() : '<em>Select a matching course</em>';

            const html = `<tr class="coupon_row">
                                <td> Discount (${appliedCoupon.code}) <span style="color:red; cursor: pointer" onclick="cancel_coupon()"> <i class="fa fa-times-circle" aria-hidden="true"></i> </span></td>
                                <td>:</td>
                                <td>
                                    ${appliedCoupon.discount_type === 'Fixed' ? '£' + appliedCoupon.amount : appliedCoupon.amount + '%'}
                                </td>
                            </tr>
                            <tr class="coupon_row">
                                <td> Total After Discount (£) </td>
                                <td>:</td>
                                <td id="coupon_total_amount" style="color:green; font-weight: bold;">
                                    ${Number.isInteger(total) ? total : total.toFixed(2)}
                                </td>
                            </tr>
                            <tr class="coupon_row">
                                <td colspan="3">
                                    <span style="display: block"><strong>Coupon applied for:</strong></span>
                                    ${courseName}
                                </td>
                            </tr>`;

            $('.cart tbody').append(html);
        }

        // cartDetails() (script.common.js.php) recalculates the cart on these clicks -> redraw the discount after it
        $(function () {
            $('body').on('click', '.want2buy, .ckbx, .practice2buy', showCoupon);
        });

        function cancel_coupon() {
            appliedCoupon = null;
            $('.cart .coupon_row').remove();
            $('#total_amount').removeClass('old_price');
            $('.coupon_applied').val('');
            $('.coupon').removeClass('d-none');
            $('.note').removeClass('d-none');
            $('.coupon_error_msg').addClass('d-none');
        }
    </script>

<?php load_module_asset('course', 'js', 'script.common.js.php'); ?>