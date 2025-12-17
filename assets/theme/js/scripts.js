function newsletterSubscribe() {
    
    var formData = jQuery('#newsletter').serialize();
    
    var error = 0;
    
    var fName = jQuery('#fName').val();
    if (!fName) {
        jQuery('#fName').addClass('required');
        error = 1;
    } else {
        jQuery('#fName').removeClass('required');
    }
    var newsletter_email = jQuery('#newsletter_email').val();
    if (!newsletter_email) {
        jQuery('#newsletter_email').addClass('required');
        error = 1;
    } else {
        jQuery('#newsletter_email').removeClass('required');
    }
       
    var email = $('#newsletter_email').val();
    if (validateEmail(email) == false || !email) {
        $('#newsletter-msg').html('<span class="text-danger">Invalid Email</span>').show();
        error = 1;
    } else {
        $('#newsletter-msg').hide();
    }
    if (error == 0) {
        $.ajax({
            type: "POST",
            url: "newsletter_subscriber/Newsletter_subscriber_frontview/create_action_ajax",
            dataType: 'json',
            data: formData,
            success: function (jsonData) {
                $('#newsletter-msg').html(jsonData.Msg).slideDown('slow');
                if (jsonData.Status === 'OK') {
                    $('#newsletter-msg').delay(5000).slideUp('slow');
                    document.getElementById("newsletter").reset();
                } else {
                    $('#newsletter-msg').delay(5000).slideUp('slow');
                }
                console.log(jsonData);
            }
        });
    }
}


jQuery(window).on('scroll', function () {
    if (jQuery(this).scrollTop() > 100) {
        jQuery('.scrollup').fadeIn();
    } else {
        jQuery('.scrollup').fadeOut();
    }
});

jQuery('.scrollup').on('click', function () {
    jQuery("html, body").animate({scrollTop: 0}, 700);
    return false;
});

function DigitOnly(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode !== 8 && unicode !== 9)//Excepting the backspace and tab keys
    {
        if (unicode < 46 || unicode > 57 || unicode === 47) //If not a number or decimal point
            return false; //Disable key press
    }
}
function validateEmail(sEmail) {
    var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}
// Manage ACL 
function enrollment( id ) {
    jQuery('#js_respond').empty();
    jQuery('#enrollment').modal({
        show: 'false',
        backdrop: 'static'
    });
    jQuery('#es_id').val( id );
    
    jQuery.ajax({
        url: "student_portal/mock_exam_enrolled_summery",
        type: "POST",
        dataType: "text",
        data: {id: id},
        beforeSend: function () {
            jQuery('#summery').html('<p class="ajax_processing">Loading...</p>');
        },
        success: function (msg) {
            jQuery('#summery').html(msg);
        }
    });
}
function enrollment_confim( id ) {
    var form = jQuery('#form').serialize();
    jQuery.ajax({
        url: "student_portal/enrolled_confirm",
        type: "POST",
        dataType: "JSON",
        data: form,
        beforeSend: function () {
            jQuery('#summery').html('<p class="ajax_processing">Loading...</p>');
        },
        success: function (respond) {
            jQuery('#summery').html( respond.Msg );
            setTimeout(function(){ location.reload(); }, 1000);
        }
    });
}