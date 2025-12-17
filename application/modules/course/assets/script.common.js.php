<script type="text/javascript">
    $('.view-invoice').on('click', function(){
        var id      = $(this).attr('id');
        $.ajax({
            url: "booking/invoice/"+id,
            type: 'get',
            dataType: 'json',
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function(respond) {

                $('#paymentDetails').html(respond.Msg);
                $('#paymentDetailsModal').modal('show');
            }
        });
    });

    $('body').on('click', '.want2buy', function () {
        var checked = $(this).is(":checked");
        var id      = $(this).val();
        if( checked ){
            $(`#slot_${id}`).removeClass('hidden');

        } else {
            $(`#slot_${id}`).addClass('hidden');
            $(`#slot_${id}`).find('.ckbx').prop('checked', '');
            $(`#slot_${id}`).find('.ckbx').prop('checked', false);
        }
        cartDetails();
    });

    $('body').on('click', '.ckbx', function () {
        cartDetails();
    });

    function cartDetails(){
        var totalAmount = 0;
        $(".want2buy").each(function (key, elem) {
            if ($(elem).is(':checkbox:checked')) {
                var id = $( this ).val();
                var price   = $(elem).attr('data-price');
                var dateCount = $(`#slot_${id}`).find('.ckbx:checked').length;
                totalAmount += Number(price)*dateCount;

                let cart_price = parseInt($("#car_total_amount").text());

                if (!isNaN(cart_price)) {
                    cart_price += parseInt(price);
                    cancel_coupon();
                }

                $("#car_total_amount").text(cart_price)
            }
        });

        var numberOfCourseChecked = $('input.ckbx:checked').length;
        $("#crs_quantity").text(numberOfCourseChecked);
        $("#total_amount").text(totalAmount);

        $("#input_total_amount").val(totalAmount);
    }

    $('body').on('click', '#bookingSave', function (e) {
        e.preventDefault();
        var formData = $("#bookingForm").serialize();

        $.ajax({
            url: "booking/add_to_cart",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function(respond) {
                if(respond.Status==='OK'){
                    //coursePaymentId = respond.Msg
                    location.href = '<?= base_url('booking/checkout/'); ?>'+respond.Msg;
                }else{
                    $('#respond').html('<p class="ajax_error">'+respond.Msg+'</p>');
                }
            }
        });
    });


    $('body').on('click', '#guestBookingSave', function (e) {
        e.preventDefault();
        var total_amount = $('#total_amount').text();
        var formData = $("#bookingForm").serialize() + '&total_amount=' + total_amount;
        var iframe = true;
        
        $.ajax({
            url: "course-booking-validate",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
                $('#personal_info').find('small').remove();
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


    $('#student_id').on('select2:select', function (e) {
        var student_id      = $("#student_id").val();
        $.ajax({
            url: "admin/course/booked/related_data/"+student_id,
            type: 'get',
            dataType: 'json',
            beforeSend: function(){
                $('#bookCourse').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function(respond) {
                $('#bookCourse').html(respond.Msg);
            }
        });
    });

    $('body').on('click', '#adminBookingSave', function (e) {
        e.preventDefault();
        var formData = $("#bookingForm").serialize();

        $.ajax({
            url: "admin/course/booked/create_action",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function(respond) {
                if(respond.Status==='OK'){
                    $.get( 'paypal/bookingConfirmationMail/' +  respond.Msg );
                    setTimeout(function(){
                        location.href = '<?= base_url('admin/course/booked/create'); ?>';
                    }, 1000);
                } else {
                    $('#respond').html('<p class="ajax_error">'+respond.Msg+'</p>');
                }
            }
        });
    });
</script>    