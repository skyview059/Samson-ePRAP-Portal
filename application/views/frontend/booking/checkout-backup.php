<?php load_module_asset('course', 'css'); ?>
<style type="text/css">

    /* CSS for Credit Card Payment form */
    .credit-card-box .panel-title {
        display: inline;
        font-weight: bold;
    }

    .credit-card-box .form-control.error {
        border-color: red;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);
    }

    .credit-card-box label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }

    .credit-card-box .payment-errors {
        font-weight: bold;
        /*color: red;*/
        padding: 5px 8px;
        margin-top: 10px;
    }

    .credit-card-box label {
        display: block;
    }

    /* The old "center div vertically" hack */
    .credit-card-box .display-table {
        display: table;
    }

    .credit-card-box .display-tr {
        display: table-row;
    }

    .credit-card-box .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 50%;
    }

    /* Just looks nicer */
    .credit-card-box .panel-heading img {
        min-width: 180px;
    }

    #payBtn {
        margin-top: 15px;
    }

</style>
<div class="booking-tab">
    <div class="tab-content">
        <?php
        if (!$item_price) {
            echo "<h4>Sorry, You did not select any course. <br> Please go back to course page and try again !</h4>";
        }
        ?>
        <div class="payment_details">
            <h3>Dear Student,</h3>
            <p><strong>Courses Selected:</strong>
                <?php getInvoiceCourses($cart_id); ?>
            </p>
            <p>You have Selected <?= $item_qty; ?> courses. <br>
                Total Price : &pound;<?= $item_price; ?></p>
        </div>

        <center>
            <h3>Your Total Unpaid Amount : <?= GBP($item_price); ?></h3>
            <h3 style="color:red;">You have <span id="time">--:--</span> min to complete your payment. </h3>
        </center>

        <div class="row">
            <div class="col-md-12 text-center">

                <div class="row">

                    <!--Start::paypal payment-->
                    <div class="col-md-3 text-center col-md-offset-3">
                        <form name="PPForm" id="PPForm" action="<?= $paypal_url; ?>" method="post">
                            <input type="hidden" name="custom" value="<?= $cart_id; ?>"/>
                            <input type="hidden" name="invoice" value="<?= $invoice_id; ?>"/>
                            <input type="hidden" name="cmd" value="_xclick"/>
                            <input type="hidden" name="item_name" value="<?= $SiteName; ?>"/>
                            <input type="hidden" name="quantity" value="<?= $item_qty; ?>"/>
                            <input type="hidden" name="amount" value="<?= $item_price; ?>"/>
                            <input type="hidden" name="business" value="<?= $paypal_email ?>"/>
                            <input type="hidden" name="currency_code" value="GBP"/>
                            <input type="hidden" name="lc" value="UK"/>
                            <input type="hidden" name="country" value="UK"/>
                            <input type="hidden" name="cpp_header_image"
                                   value="<?php echo site_url('/assets/theme/images/logo.png'); ?>"/>
                            <input type="hidden" name="no_shipping" value="1"/>
                            <input type="hidden" name="no_note" value="0"/>
                            <input type="hidden" name="return" value="<?= site_url('booking'); ?>"/>
                            <input type="hidden" name="notify_url" value="<?= site_url('paypal/ipn'); ?>"/>
                            <input type="hidden" name="cancel_return"
                                   value="<?php echo site_url('booking/cancel'); ?>"/>
                            <!--<input type="submit" value="Proceed To PayPal" name="_submit" class="paypal paypal-button" id="btnSuiv" />-->
                            <button type="submit" name="_submit" class="paypal paypal-button" id="btnSuiv">
                                <span class="paypal-button-title">
                                    Pay with
                                </span>
                                <span class="paypal-logo">
                                    <i>Pay</i><i>Pal</i>
                                </span>
                            </button>
                        </form>
                    </div>
                    <!--End::paypal payment-->

                    <!--Start::stripe payment-->
<!--                    <div class="col-md-offset-3 col-md-3  text-right">-->
<!--                        <button type="button" name="stripe" class="paypal paypal-button" id="btnStripe">-->
<!--                            <span class="paypal-button-title">-->
<!--                                Pay by-->
<!--                            </span>-->
<!--                            <span class="paypal-logo">-->
<!--                                Card-->
<!--                            </span>-->
<!--                        </button>-->
<!--                    </div>-->
                    <!--Start::stripe payment-->

                    <!--Start::flutterWave payment-->
                    <div class="col-md-3  text-right">
                        <button type="button" name="flutterwave" class="paypal paypal-button" id="btnFlutterwave">
                            <span class="paypal-button-title">
                                Pay with
                            </span>
                            <span class="paypal-logo">
                                Local Currency
                            </span>
                        </button>
                    </div>
                    <!--End::flutterWave payment-->


<!--                    <form>-->
<!--                        <div> Your order is ₦2,500 </div>-->
<!--                        <button type="button" id="start-payment-button" onclick="makePayment()">Pay Now</button>-->
<!--                    </form>-->


                    <div class="col-md-3 text-left">
                        <?php //if ($worldpay_active) { ?>
                        <form name="worldpay" id="worldpay" action="<?php echo site_url('worldpay/request.php'); ?>"
                              method="post" style="width: 180px; margin: auto;">
                            <input type="hidden" name="invoice" value="<?= $invoice_id; ?>"/>
                            <a class="btn btn-warning" href="<?= site_url('booking/course?id=' . $cart_id); ?>">Change
                                Order Items</a>
                            <!--<input type="submit" value="Proceed To WorldPay" name="worldpay_btn" class="paypal" id="worldpay_btn" />-->
                        </form>
                        <?php // } ?>
                    </div>
                </div>


                <!--
                <form action="" name="cash_on_delivery" id="cash" method="post">
                        <input type="submit" name="cash" value="Cash on Transport" class="cash"/>
                </form>
                -->

                <br clear="all"/>
                <p>If you are not able to pay by PayPal or Stripe,<br/>
                    please contact <?= $SiteName; ?> <br/>
                    Phone: <?= $PhoneNumber; ?> <br/>
                    Email <?= $IncomingEmail; ?> <br/>
                    to arrange your booking.</p>
            </div>
        </div>

    </div>
</div>

<!--Start::Stripe Payment Modal -->
<div class="modal fade" id="stripePaymentModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl" id="stripePayment">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>
                <h3 class="modal-title">Invoice ID # <?= $invoice_id; ?></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- CREDIT CARD FORM STARTS HERE -->
                        <div class="panel panel-default credit-card-box">
                            <div class="panel-heading">
                                <div class="row display-tr">
                                    <h3 class="panel-title display-td">Payment Details</h3>
                                    <div class="display-td">
                                        <img class="img-responsive pull-right"
                                             src="assets/admin/dist/img/credit/accepted_c22e0.png">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <!--                                <form method="post" id="paymentFrm" enctype="multipart/form-data">-->
                                <!--                                    <div class="row">-->
                                <!--                                        <div class="col-xs-12">-->
                                <!--                                            <div class="form-group">-->
                                <!--                                                <label for="card-num">CARD NUMBER</label>-->
                                <!--                                                <div class="input-group">-->
                                <!--                                                    <input -->
                                <!--                                                        type="text"-->
                                <!--                                                        class="form-control number"-->
                                <!--                                                        name="card_num"-->
                                <!--                                                        id="card-num"-->
                                <!--                                                        placeholder="Valid Card Number"-->
                                <!--                                                        autocomplete="off"-->
                                <!--                                                        maxlength="16"-->
                                <!--                                                        required autofocus -->
                                <!--                                                        />-->
                                <!--                                                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>-->
                                <!--                                                </div>-->
                                <!--                                            </div>                            -->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                    <div class="row">-->
                                <!--                                        <div class="col-xs-4 col-md-4">-->
                                <!--                                            <div class="form-group input-group">-->
                                <!--                                                <label for="card-expiry-month"><span class="hidden-xs">EXP MONTH</span></label>-->
                                <!--                                                <input -->
                                <!--                                                    type="text"-->
                                <!--                                                    class="form-control number" -->
                                <!--                                                    name="card-expiry-month"-->
                                <!--                                                    id="card-expiry-month"-->
                                <!--                                                    placeholder="MM"-->
                                <!--                                                    autocomplete="off"-->
                                <!--                                                    maxlength="2"-->
                                <!--                                                    required -->
                                <!--                                                    />-->
                                <!---->
                                <!--                                            </div>-->
                                <!--                                        </div>-->
                                <!--                                        <div class="col-xs-4 col-md-4">-->
                                <!--                                            <div class="form-group input-group">-->
                                <!--                                                <label for="card-expiry-year"><span class="hidden-xs">EXP YEAR</span></label>-->
                                <!--                                                <input -->
                                <!--                                                    type="text" -->
                                <!--                                                    class="form-control number" -->
                                <!--                                                    name="exp_year"-->
                                <!--                                                    id="card-expiry-year"-->
                                <!--                                                    placeholder="YYYY"-->
                                <!--                                                    autocomplete="off"-->
                                <!--                                                    maxlength="4" -->
                                <!--                                                    required -->
                                <!--                                                    />-->
                                <!--                                            </div>-->
                                <!--                                        </div>-->
                                <!---->
                                <!--                                        <div class="col-xs-4 col-md-4 pull-right">-->
                                <!--                                            <div class="form-group">-->
                                <!--                                                <label for="card-cvc">CV CODE</label>-->
                                <!--                                                <input -->
                                <!--                                                    type="text" -->
                                <!--                                                    class="form-control number"-->
                                <!--                                                    name="cvc"-->
                                <!--                                                    id="card-cvc"-->
                                <!--                                                    placeholder="CVC"-->
                                <!--                                                    autocomplete="off"-->
                                <!--                                                    maxlength="3"-->
                                <!--                                                    required-->
                                <!--                                                    />-->
                                <!--                                            </div>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                    -->
                                <!--                                    <div class="row">-->
                                <!--                                        <div class="col-xs-12">-->
                                <!--                                            <input type="hidden" name="cart_id" value="-->
                                <?php //echo $cart_id; ?><!--" />-->
                                <!--                                            <button class="btn btn-success btn-lg btn-block" id="payBtn" type="submit">Pay -->
                                <?php //= GBP($item_price); ?><!--</button>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                    <div class="row">-->
                                <!--                                        <div class="col-xs-12">-->
                                <!--                                            <div class="payment-errors" id="payment-errors"></div>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                </form>-->


                                <form id="payment-form">
                                    <div id="link-authentication-element">
                                        <!-- Elements will create authentication element here -->
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div id="payment-element">
                                                <!-- Elements will create form elements here -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>"/>
                                            <button class="btn btn-success btn-lg btn-block" id="payBtn" type="submit">
                                                Pay <?= GBP($item_price); ?></button>
                                        </div>
                                    </div>

                                    <div id="error-message" style="margin-top: 15px">
                                        <!-- Display error message to your customers here -->
                                    </div>
                                </form>

                                <div id="messages" role="alert" style="display: none;"></div>
                            </div>
                        </div>
                        <!-- CREDIT CARD FORM ENDS HERE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Start::Stripe Payment Modal -->





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    //Start::first promoter
    fpr("referral", {email: "<?php echo getLoginStudentData('student_email')?>"});
    //End::first promoter

    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                timer = duration;
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveMinutes = '<?= $payout_limit; ?>';
        var display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
    };
</script>

<!--Start::Stripe payment-->
<!--<script type="text/javascript" src="https://js.stripe.com/v3/"></script>-->
<?php $stripe_key = $this->config->item('stripe_key'); ?>
<script type="text/javascript">
    $('body').on('click', '#btnStripe', function () {
        $.ajax({
            dataType: 'json',
            method: 'post',
            url: '<?php echo site_url('booking/stripe_payment'); ?>',
            data: {cart_id: <?php echo $cart_id; ?>},

            beforeSend() {
                swal.fire({
                    title: 'Processing your request...',
                    allowOutsideClick: false,
                });
                swal.showLoading();
            },

            success: function (response) {
                setInterval(function () {
                    swal.close();
                }, 3000)

                $('#stripePaymentModal').modal('show');

                const client_secret = response.payment.client_secret;
                const elements = stripe.elements({clientSecret: client_secret});
                const paymentElement = elements.create('payment');
                paymentElement.mount('#payment-element');
                // Create and mount the linkAuthentication Element to enable autofilling customer payment details
                const linkAuthenticationElement = elements.create("linkAuthentication");
                linkAuthenticationElement.mount("#link-authentication-element");

                const form = document.getElementById('payment-form');
                let submitted = false;
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    $('#error-message').removeClass('alert alert-danger');
                    $('#error-message').empty();

                    // Disable double submission of the form
                    if (submitted) {
                        return;
                    }
                    submitted = true;
                    form.querySelector('button').disabled = true;

                    const nameInput = document.querySelector('#name');

                    // Confirm the payment given the clientSecret
                    // from the payment intent that was just created on
                    // the server.
                    stripe.confirmPayment({
                        elements,
                        redirect: "if_required"
                    }).then(function (response) {
                        if (response.error) {
                            form.querySelector('button').disabled = false;
                            submitted = false;

                            if (response.error.type === "card_error" || response.error.type === "validation_error") {
                                $('#error-message').addClass('alert alert-danger');
                                $('#error-message').text(response.error.message);
                            } else {
                                toastr.error('An unexpected error occurred.', 'Error');
                            }
                        } else {
                            $.ajax({
                                dataType: 'json',
                                method: 'post',
                                url: '<?php echo site_url('booking/confirm_payment'); ?>',
                                data: {payment: response.paymentIntent, cart_id: <?php echo $cart_id; ?>},

                                success: function (response) {
                                    if (response.success) {
                                        $('#stripePaymentModal').modal('hide');
                                        $('#error-message').addClass('alert alert-success');
                                        $('#error-message').text(response.message);
                                        // Override global options

                                        toastr.success(response.message, 'Success');
                                        location.href = '<?= base_url('booking/'); ?>';

                                    } else {
                                        form.querySelector('button').disabled = false;

                                        toastr.error(response.message, 'Invalid');
                                        toastr.options.escapeHtml = true;

                                        $('#error-message').addClass('alert alert-danger');
                                        $('#error-message').text(response.message);
                                    }
                                }
                            })
                        }
                    });
                });
            },

            error(response) {
                console.log(response);
            }
        })
    });

    //set your publishable key
    //Stripe('<?php echo $stripe_key;?>');
    //Live key


    const stripe = Stripe('<?php echo $stripe_key;?>', {
        apiVersion: '2020-08-27',
    });


    //callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
        if (response.error) {
            //enable the submit button
            $('#payBtn').removeAttr("disabled");
            //display the errors on the form
            // $('#payment-errors').attr('hidden', 'false');
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html(response.error.message);
        } else {
            let stripeForm = $("#paymentFrm");
            //get token id
            let token = response['id'];
            //insert the token into the form
            stripeForm.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            //submit form to the server
            let dataPost = stripeForm.serialize();

            $.ajax({
                url: '<?php echo site_url('booking/make_payment'); ?>',
                type: "post",
                data: dataPost, //this is formData
                dataType: "json",
                cache: false,
                beforeSend: function () {
                    toastr.warning("Please Wait...");
                },
                success: function (data) {
                    // Remove current toasts using animation
                    toastr.clear();

                    if (data.success) {
                        //Start::first promoter
                        fpr("referral", {email: "<?php echo getLoginStudentData('student_email')?>"});
                        //End::first promoter

                        stripeForm[0].reset();
                        $('#stripePaymentModal').modal('hide');
                        $('#payment-errors').addClass('alert alert-success');
                        $('#payment-errors').text(data.message);
                        // Override global options

                        toastr.success(data.message, 'Success');
                        location.href = '<?= base_url('booking/'); ?>';

                    } else {
                        toastr.error(data.message, 'Invalid');
                        toastr.options.escapeHtml = true;

                        $('#payment-errors').addClass('alert alert-danger');
                        $('#payment-errors').text(data.message);
                    }
                }
            });
        }
    };

    $(document).ready(function () {
        //on form submit
        $("#paymentFrm").submit(function (event) {
            event.preventDefault();
            //disable the submit button to prevent repeated clicks
            $('#payBtn').attr("disabled", "disabled");

            //create single-use token to charge the user
            // Stripe.createToken({
            //     number: $('#card-num').val(),
            //     cvc: $('#card-cvc').val(),
            //     exp_month: $('#card-expiry-month').val(),
            //     exp_year: $('#card-expiry-year').val()
            // }, stripeResponseHandler);

            //submit from callback
            return false;
        });

        // only numbers are allowed
        $(".number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+v, Command+V
                (e.keyCode === 118 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl+V, Command+V
                (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl+A, Command+V
                ((e.keyCode === 65 || e.keyCode === 97 || e.keyCode === 103 || e.keyCode === 99 || e.keyCode === 88 || e.keyCode === 120) && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
</script>
<!--End::Stripe payment-->


<!--Start::FlutterWave payment-->
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    let price = <?= $item_price ?>;
    let local_rate = <?php echo getLocalCurrencyRate($item_price, $source='NGN', $destination='GBP'); ?>;

    $('body').on('click', '#btnFlutterwave', function () {

        $.ajax({
            dataType: 'json',
            method: 'post',
            url: '<?php echo site_url('booking/stripe_payment'); ?>',
            data: {cart_id: <?php echo $cart_id; ?>},

            beforeSend(){
                swal.fire({
                    title: 'Processing your request...',
                    allowOutsideClick: false,
                });
                swal.showLoading();
            },

            success:function (response ){
                setInterval(function (){
                    swal.close();
                }, 3000)

                FlutterwaveCheckout({
                    public_key: "<?= $this->config->item('fw_public_key') ?>",
                    tx_ref: "txref-DI0NzMx13",
                    amount: local_rate,
                    currency: "NGN",
                    payment_options: "card, banktransfer, ussd",
                    meta: {
                        source: "docs-inline-test",
                        consumer_mac: "92a3-912ba-1192a",
                    },
                    customer: {
                        email: "<?php echo getLoginStudentData('student_email')?>",
                        phone_number: "01728692643",
                        name: "<?php echo getLoginStudentData('student_name')?>",
                    },
                    customizations: {
                        title: "Invoice ID: <?= $invoice_id; ?>",
                        description: "ePRAP Course payment : " + price,
                        logo: "https://checkout.flutterwave.com/assets/img/rave-logo.png",
                    },

                    callback: function (data){
                        $.ajax({
                            dataType: 'json',
                            method: 'post',
                            url: '<?php echo site_url('booking/flutterwave_confirm_payment'); ?>',
                            data: { payment : data, cart_id: <?php echo $cart_id; ?>},

                            success: function (response){
                                // console.log("flutterwave_confirm_payment: ", response);

                                if (response.success) {
                                    location.href = '<?= base_url('booking/?status=success'); ?>';
                                    toastr.success(response.message, 'Success');
                                } else {
                                    toastr.error(response.message, 'Invalid');
                                    toastr.options.escapeHtml = true;
                                }
                            }
                        });
                    },
                    onclose: function() {
                        // console.log("Payment cancelled!");
                        toastr.info("Payment cancelled!", 'Oops!');
                    }
                });
            },

            error(response){
                console.log(response);
            }
        })
    });
</script>
<!--End::FlutterWave payment-->