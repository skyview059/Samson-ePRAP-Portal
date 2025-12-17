<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.css">

<style>
    *{
        font-family: "Jost", sans-serif;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 45px !important;
    }

    .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus {
        outline: unset;
    }

    .d-none {
        display: none;
    }

    .card {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 5px;
        overflow: hidden;
    }

    .card-body {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
    }

    .card-header:first-child {
        border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
    }
    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        font-weight: bold;
        background: #4387CA;
        color: white;
    }

    .table>thead:first-child>tr:first-child>th {
        padding: 5px 15px;
    }

    .table{
        margin-bottom: 0;
    }

    .courses{
        border: 1px solid #4387CA;
        background: #EBF6FF;
        font-weight: 600!important;
    }

    .date_slot{
        height:35px;
        background: #4387CA;
        color:white;
        border-radius: 5px !important;
    }

    .cart_section{
        background: #20278B;
        color: white
    }

    .cart_section .table>thead>tr>td {
        vertical-align: middle;
        line-height: 20px;
    }

    .rounded {
        border-radius: 10px !important;
        overflow: hidden;
        margin: 15px 0;
    }

    .rounded {
        background: #4387CA;
    }

    .discount{
        background: #20278B;
        color: white
    }

    .form-control, .select2-container--default .select2-selection--single{
        border-radius: 6px !important;
        box-shadow: none;
    }

    .form-control:hover{
        box-shadow: none;
    }

    .hero-content {
        text-align: center;
        height: 350px;
        width: 100%;
        padding: 25px;
        color: white;
        display:flex;
        align-items:center;
        justify-content:center;
        flex-direction: column;
        background-size: cover;
        margin-top: -1px;
    }

    .login, .register{
        margin: 0 5px;
        border-radius: 15px;
        padding: 5px 25px;
    }

    #guestBookingSave {
        border-radius: 15px;
        border: none;
        padding: 8px 20px;
    }

    #coupon {
        position: relative;
        display: inline-flex;
        width: 90%;
    }

    #coupon_apply {
        position: absolute;
        right: 3%;
        padding: 9px 25px;
        background: #4AA2F1;
        border-radius: 27px;
    }

    #coupon_apply:hover{
        color: #eee;
    }

    #cart_ballance {
        background: #3C4D9D;
    }

    @media screen and (max-width: 991px)  {
        .hero-content {
            height: 100%;
        }
    }

    #cart_details{
        background: #3C4D9D;
        padding: 10px;
    }

    .footer-amount {
        text-align: right;
        padding: 0 10px;
    }
    .footer-label {
        padding: 0 10px;
    }
</style>

<!--<div class="hero-content" style="background-image: url('assets/admin/dist/img/book-course.png');">-->
<!--    <p style="font-size: 45px"> Register Now Book a Course</p>-->
<!--    <div>-->
<!--       <button class="btn btn-info register"> Sign Up</button>-->
<!--       <button class="btn btn-success login"> Log In </button>-->
<!--    </div>-->
<!--</div>-->

<div class="container">
    <form id="bookingForm" method="post" action="<?php echo site_url('course-booking-action'); ?>" target="_parent"
          name="bookingForm" class="form-horizontal mt-70">

        <input type="hidden" name="first_promoter" value="<?php echo $this->input->get('ref'); ?>">

        <div class="row">
            <div class="col-md-8 mb-15">

                <?php if ( getLoginStudentData( 'student_id' ) ): ?>
                    <div class="card mb-15 ">
                        <p class="card-header"> Hello, <?= getLoginStudentData( 'student_name' ) ?> </p>
                    </div>
                <?php endif;?>

                <?php if ( !getLoginStudentData( 'student_id' ) ): ?>
                    <div class="registerForm">
                        <div class="card" style="margin-bottom: 30px" id="personal_info">
                            <div class="card-header">
                                Personal Information
                            </div>
                            <div class="card-body cart_section">
                                <div class="mb-15">
                                    <label for="first_name"> First Name </label>
                                    <input type="text" placeholder="First Name" name="first_name" id="first_name" class="form-control">
                                </div>

                                <div class="mb-15">
                                    <label for="last_name"> Last Name </label>
                                    <input type="text" placeholder="Last Name" name="last_name" id="last_name" class="form-control">
                                </div>

                                <div class="mb-15">
                                    <label for="email"> Email </label>
                                    <input type="text" placeholder="Email" name="email" id="email" class="form-control">
                                </div>

                                <label for="phone_code"> Contact Number </label>
                                <div class="row">
                                    <div class="mb-15 col-md-6">
                                        <select name="phone_code" class="form-control select2" aria-label="phone_code">
                                            <?php $phone_code = '';
                                            echo getPhoneCode($phone_code); ?>
                                        </select>
                                    </div>

                                    <div class="mb-15 col-md-6">
                                        <input type="text" placeholder="0000000000" name="phone" class="form-control" aria-label="phone">
                                    </div>
                                </div>

                                <div class="mb-15">
                                    <label for="country_id"> Country </label>
                                    <select class="form-control select2" name="country_id" id="country_id">
                                        <?php echo getDropDownCountries('', 'Select country of origin'); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="loginForm d-none">
                        <div class="card" style="margin-bottom: 30px">
                            <div class="card-header">
                                Log In Form
                            </div>
                            <div class="card-body cart_section">
                                <div class="mb-15">
                                    <label for="email"> Email </label>
                                    <input type="text" placeholder="Email" id="guestEmail" class="form-control">
                                </div>

                                <div class="mb-15">
                                    <label for="password"> Password </label>
                                    <input type="password" placeholder="Password" id="guestPassword" class="form-control">
                                </div>

                                <div class="text-right mb-15">

                                    <button class="btn btn-info" onclick="login()" type="button"> Log In </button>
                                </div>

                                <span id="login"></span>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <div class="card courses">
                    <div class="card-header">
                        Courses
                    </div>

                    <div class="card-body">
                        <table class="table table-borderless table-condensed course_table">
                            <thead>
                            <tr style="background: #20278B; color: white">
                                <th class="text-center"> Select</th>
                                <th> Name</th>
                                <th> Price</th>
                                <th class="text-center"> Dates & Available Seat</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($course_plans as $plan) { ?>
                                <?php foreach ($plan['courses'] as $course) { ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="icheck-primary icheck-inline">
                                                <input name="id[<?= $course['id']; ?>]"
                                                       id="id_<?= $course['id']; ?>"
                                                       class=""
                                                       type="checkbox"
                                                       value="<?= $course['id']; ?>"
                                                       data-price="<?= $course['price']; ?>"

                                                       onclick="buyCourse(<?= $course['id']; ?>)"

                                                    <?php echo count(json_decode($course['dates'])) === 0 ? 'disabled' : ''?>
                                                    <?php echo $course['isSelected'] ? 'checked="checked"' : ''; ?> />

                                                <label for="id_<?= $course['id']; ?>"></label>
                                            </div>
                                        </td>

                                        <td id="course_name_<?= $course['id']; ?>"><?= $course['name']; ?></td>

                                        <td class="text-center" style="color: #4387CA; font-weight: bold"><?= GBP($course['price']); ?></td>
                                        <td class="text-center" id="course_<?= $course['id']; ?>">
                                            <?php
                                            if (count(json_decode($course['dates'])) > 0){ ?>
                                                <select name="slot_id[<?php echo $course['id']; ?>]"
                                                        id="<?php echo $course['id']; ?>"

                                                        class="form-control date_slot" aria-label="">

                                                    <option disabled selected style="color: #eee;">--Select Your Seat--</option>

                                                    <?php
                                                    foreach (json_decode($course['dates']) as $date) {
                                                        echo '<option value="' . $date->id . '">' . date('d M y H:i A', strtotime($date->start_date)) . " ($date->AvailableSeat)" . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            }else {
                                                echo "No Date Found to Enroll";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Your Cart Details
                    </div>

                    <div class="card-body cart_section">
                        <table class="table table-borderless table-condensed cart">
                            <thead id="cart_item">

                            </thead>
                        </table>

                        <div class="row" id="cart_details">

                        </div>
                    </div>
                </div>

                <!--Start Coupon Apply-->
                <div class="card" style="margin: 20px 0;">
                    <div class="card-body discount">

                        <p class="coupon_error_msg text-center d-none" style="color: red"> <b> Promo code is invalid.</b> </p>

<!--                        <div class="input-group coupon_apply rounded">-->
<!--                            <input type="text" class="form-control" placeholder="Promo code" id="coupon">-->
<!--                            <span class="input-group-addon" style="padding: 0">-->
<!--                                <button class="btn btn-secondary" id="coupon_apply" type="button"> Apply </button>-->
<!--                            </span>-->
<!--                        </div>-->


                        <div class="coupon_apply">
                            <input type="text" class="form-control BTN_input" placeholder="Promo code" id="coupon">
                            <button class="btn btn-secondary" id="coupon_apply" type="button"> Apply </button>
                        </div>

                        <table class="table table-borderless table-condensed cart">
                            <thead>
                            <tr class="hideOrShow d-none">
                                <td>
                                    Discount:
                                </td>
                                <td class="text-right">
                                    <span id="discount_amount"> </span>
                                </td>
                            </tr>

                            <tr class="hideOrShow d-none">
                                <td>
                                    Coupon:
                                </td>
                                <td class="text-right">
                                    <span id="applied_code" style="font-weight: 700!important;">  </span>
                                </td>
                            </tr>
                            <tr class="hideOrShow d-none">
                                <td>
                                    Applied for:
                                </td>
                                <td class="text-right">
                                    <small id="applied_course" style="font-size: 75%;">  </small>
                                </td>
                            </tr>
                            <tr class="hideOrShow d-none">
                                <td colspan="2" style="color: #F8ED18;"><small>Note:<i>If you have multiple coupon code then booking separately.</i></small></td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--End Coupon Apply-->

                <div class="card">
                    <div class="card-header">
                        Comments
                    </div>
                    <div class="card-body discount">
                       <textarea class="form-control" rows="3" name="customer_comments" style="margin-bottom: 30px"
                                      placeholder="Type your comments"></textarea>

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

                        <button id="guestBookingSave" class="btn btn-primary" style="background: #4AA2F1">
                            Continue to Payment
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div id="respond"></div>
            </div>
        </div>

        <input readonly type="text" value="00" class="sr-only" id="real_amount">
        <input readonly type="text" value="00" class="sr-only" name="total_amount" id="total_amount">
        <input readonly type="text" value="" class="coupon_name sr-only"  name="coupon_name">
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // start:: remove from localStorage when user page reload
        localStorage.removeItem('guest_course');
        localStorage.removeItem('coupon');
    // start:: remove from localStorage when user page reload

    $('.select2').select2();

    $('.login, .register').on('click', function (){
        $('.loginForm, .registerForm').toggle('d-none');
    });

    function login() {
        let password = $('#guestPassword').val();
        let email = $('#guestEmail').val();

        $.ajax({
            type: "post",
            dataType: "json",

            data: { email,password },
            url: "guest-login",

            success: function (response) {
                if (response.Status === 'FAIL'){
                    $('#login').html(response.Msg)
                }

                if (response.Status === 'OK'){
                    $('#login').html(response.Msg)
                    setInterval(function (){
                        localStorage.removeItem('coupon');
                        window.location.reload();
                    }, 1000)
                }
            }
        })
    }

    function buyCourse(course_id) {
        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];

        cart.push({
            course_id: course_id,
            course_name: $('#' + 'course_name_' + course_id).text(),
            course_price: $('#' + 'id_' + course_id).data('price') ,
            date_slot: 0,
        });

        if (!isDuplicate(course_id) && $('#id_' + course_id).is(':checked')) {
            localStorage.setItem('guest_course', JSON.stringify(cart));
        }else {
            removeFromCart(course_id);
            // clean date slot option
            let selectElement = $('#' + course_id);
            selectElement.val('--Select Your Seat--').trigger('clear');
        }


        // check selected course id & coupon course id same or not
        const coupon = JSON.parse(localStorage.getItem('coupon'));
        if (coupon){
            if (course_id == coupon.course_id) {
                localStorage.removeItem('coupon');
                window.location.reload();
            }
        }

        checkDateSlotSelectOrNot();
        isSelected();
        showCart();
    }

    // select date slot
    $('.date_slot').change(function() {
        let optionLabel = $(this).find('option:selected').text();

        let course_id = $(this).attr('id');
        let slot_id = parseInt($(this).val());

        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];

        cart.forEach(function(item) {
            if (item.course_id == course_id) {
                item.date_slot = slot_id;
                item.option_label = optionLabel;
            }
        });

        localStorage.setItem('guest_course', JSON.stringify(cart));

        isSelected();
        showCart();
    });

    function isDuplicate(course_id) {
        let isDuplicate = false;

        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];

        for (let i = 0; i < cart.length; i++) {
            if (cart[i].course_id == course_id) {
                isDuplicate = true;
                break;
            }
        }
        return isDuplicate;
    }

    function isSelected() {
        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];

        cart.forEach(function(item) {
            $('#' + 'id_' + item.course_id).attr('checked', true);
        });
    }
    isSelected();

    function removeFromCart(course_id) {
        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];

        const index = cart.findIndex(item => item.course_id == course_id);

        if (index !== -1) {
            cart.splice(index, 1);
            localStorage.setItem('guest_course', JSON.stringify(cart));
        }
    }

    function showCart() {
        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];
        let total_amount = 0;

        $('#cart_item').empty();

        cart.forEach(function(item) {
            let cart_table_row = `
                    <tr style="vertical-align: middle">
                        <td>
                            <span style="font-weight: 600">${item.course_name}</span> <br>
                            <small> ${item.option_label ? item.option_label : ''} </small>
                        </td>
                        <td class="text-right">
                            <span id="crs_quantity"> £${item.course_price} </span>
                        </td>
                    </tr>
                `;

            total_amount += item.course_price;
            $('#cart_item').append(cart_table_row);
        });

        const coupon = JSON.parse(localStorage.getItem('coupon'));

        if (coupon){
            if (coupon.discount_type === 'Fixed') {
                total_amount = total_amount - coupon.amount;
            }

            if (coupon.discount_type === 'Percentage') {
                total_amount = total_amount - (total_amount * coupon.amount / 100);
            }
        }

        let cart_footer = `
                    <div class="col-md-8 col-xs-8 footer-label">
                        Total Amount (£)
                    </div>
                    <div class="col-md-4 col-xs-4 footer-amount">
                        £${total_amount}
                    </div>
                 `;

       $('#total_amount').val(total_amount);
       $('#cart_details').html(cart_footer);
    }
    showCart();

    function checkDateSlotSelectOrNot() {
        const cart = JSON.parse(localStorage.getItem('guest_course')) || [];
        $('.slot_msg').remove();

        cart.forEach(function(item) {
            if (item.date_slot  < 1){
                $('#' + 'course_' + item.course_id).append('<small class="text-danger slot_msg"><i>The seat must be required.</i></small>');
            }

            if (item.date_slot > 0) {
                let selectElement = $('#' + item.course_id);
                if (selectElement.val() != item.date_slot) {
                    selectElement.val(item.date_slot).trigger('change');
                }
            }
        });
    }
    checkDateSlotSelectOrNot();

    $('#coupon_apply').on('click', function () {
        let value = $('#coupon').val();
        getCoupon(value);
    });

    function getCoupon(value){
        $.ajax({
            type: "GET",
            dataType: "json",
            data: {coupon: value},
            url: "check-promo-code",

            beforeSend() {
                $('.coupon_error_msg').addClass('d-none');
            },
            success: function (response) {
                // console.log(response.promocode);

                if (response.promocode === null){
                    $('.coupon_error_msg').removeClass('d-none').html('<b>Promo code is invalid.</b>');
                    return;
                }

                // this check for authenticate user
                // authenticate user can use multiple time or no
                if (response.promocode.already_used && 'yes' !== response.promocode.use_multiple){
                    $('.coupon_error_msg').removeClass('d-none').html('<b>You have already use this promo code.</b>');
                    return;
                }

                const isLoggedIn = <?= json_encode(auth('student')); ?>;

                // check:: this coupon for this auth user or not
                if (parseInt(response.promocode.is_special ) === 1 && !isLoggedIn ){
                    $('.coupon_error_msg').removeClass('d-none').html('<b> This coupon for special user.</b>');
                    return;
                }

                if (parseInt(response.promocode.is_special ) === 1 && isLoggedIn && !response.promocode.students.includes(parseInt(isLoggedIn.id))){
                    $('.coupon_error_msg').removeClass('d-none').html('<b> This coupon for special user.</b>');
                    return;
                }

                const date = new Date();
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day   = date.getDate().toString().padStart(2, '0');

                const now = `${year}-${month}-${day}`;

                if (response.promocode && (response.promocode.start_date <= now && now <= response.promocode.end_date)) {
                    // Start::selected course id & coupon course id same or not
                    const cartItems = JSON.parse(localStorage.getItem('guest_course'));
                    const courseIDs = response.promocode.courses;

                    const matchedCourses = cartItems.filter(item => courseIDs.includes(item.course_id))
                        .map(item => (item.course_name)).join(', ');

                    const couponData = {
                        amount: response.promocode.amount,
                        code: response.promocode.code,
                        discount_type: response.promocode.discount_type,
                        course_id: response.promocode.course_id,
                        name: matchedCourses,
                    };

                    const courseExists = courseIDs.some(courseID => cartItems.some(item => item.course_id === courseID));

                    if (courseExists) {
                        localStorage.setItem('coupon', JSON.stringify(couponData));
                    } else {
                        $('.coupon_error_msg').removeClass('d-none').html('<b> The promo code is not for this course.</b>');
                        localStorage.removeItem('coupon')
                    }
                    // End::check selected course id & coupon course id same or not

                    $('#real_amount').val(response.promocode.amount); // set for::if user modify localstorage data
                    showCoupon();
                    showCart();
                }else {
                    $('.coupon_error_msg').removeClass('d-none').html('<b>Promo code expired.</b>');
                }
            }
        })
    }

    function showCoupon(){
        const coupon = JSON.parse(localStorage.getItem('coupon'));

        if (coupon){
            $('#applied_code').html(coupon.code + '<span style="color:red" onclick="cancel_coupon()"> <i class="fa fa-times-circle"></i> </span>');
            $('#applied_course').text(coupon.name);
            $('.coupon_name').val(coupon.code);

            if (coupon.discount_type === 'Fixed') {
                $('#discount_amount').text('£' + coupon.amount);
            }

            if (coupon.discount_type === 'Percentage') {
                $('#discount_amount').text(coupon.amount + '%');
            }

            $('.coupon_apply').addClass('d-none');
            $('.hideOrShow').removeClass('d-none');

            // check for::if user modify localstorage data then regenerate again
            if (coupon.amount != $('#real_amount').val()){
                getCoupon(coupon.code);
            }
        }
    }
    showCoupon();

    function cancel_coupon(){
        localStorage.removeItem('coupon')
        window.location.reload();
    }

    $('body').on('click', '#guestBookingSave', function (e) {
        e.preventDefault();
        let total_amount = $('#total_amount').text();
        let formData = $("#bookingForm").serialize() + '&total_amount=' + total_amount;
        let iframe = true;

        $.ajax({
            url: "course-booking-validate",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
                $('.error-message').remove();
                $('.course_table').parent().find('small').remove();
            },
            success: function(response) {
                $('#respond').empty();

                $.each(response[0], function (field, error_msg) {
                    $(`input[name="${field}"]`).removeClass('mb-15');
                    $(`input[name="${field}"]`).parent().append(`<small class="text-warning error-message"><em>${error_msg}</em></small>`);

                    if(field === 'id[]'){
                        $('.course_table').parent().append(`<small class="text-warning error-message"><em>${error_msg}</em></small>`);
                    }

                    if( parseFloat($(".want2buy:checked").length) > 0 && field === 'slot_id[]'){
                        $('.course_table').parent().append(`<small class="text-warning error-message"><em>${error_msg}</em></small>`);
                    }

                    if(field === 'country_id'){
                        $('#country_id').parent().append(`<small class="text-warning error-message"><em>${error_msg}</em></small>`);
                    }
                });

                if( response.Status === 'OK' ){
                    $('#bookingForm').submit();
                }
            },
        });
    });
</script>

<!--<script>-->
<!--    // Disable right-click-->
<!--    document.addEventListener('contextmenu', function (e) {-->
<!--        e.preventDefault();-->
<!--    });-->
<!---->
<!--    // Disable Ctrl+U-->
<!--    document.addEventListener('keydown', function (e) {-->
<!--        if (e.ctrlKey && e.key === 'u') {-->
<!--            e.preventDefault();-->
<!--        }-->
<!--    });-->
<!---->
<!--    // Disable Ctrl+Shift+I-->
<!--    document.addEventListener('keydown', function (e) {-->
<!--        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'I') {-->
<!--            e.preventDefault();-->
<!--        }-->
<!--    });-->
<!--</script>-->