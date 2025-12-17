$('#signin').on('click', function(e){
    e.preventDefault();    
    var credential = $('#credential').serialize();
    $.ajax({
        url: 'auth_student/login',
        type: "POST",
        dataType: "json",
        cache: false,
        data: credential,
        beforeSend: function(){
            $('#respond').html('<p class="ajax_processing">Please Wait... Checking....</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                $('#respond').html( jsonData.Msg );                
                window.location.href = "";
            } else if(jsonData.Status === 'OTP'){
//                console.log(jsonData.Msg);
//                $('#otpresponse').text(`Put this code "${jsonData.Msg}"", If you don’t get an SMS`);
                $('.otp_verfication').css('display','block');
                $('.js_forget_password').css('display','none');
                $('.js_login').css('display','none');
            } else {
                $('#respond').html( jsonData.Msg );
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            $('#respond').html( '<p> XML: '+ XMLHttpRequest + '</p>' );
            $('#respond').append( '<p>  Status: '+ textStatus + '</p>' );
            $('#respond').append( '<p> Error: '+ errorThrown + '</p>' );            
        }  
    });        
});


$('.js_forgot').on('click', function(){
    $('.js_login').slideUp('slow');
    $('.js_forget_password').slideDown('slow');
});

$('.js_back_login').on('click', function(){
    $('.js_forget_password').slideUp('slow');
    $('.js_login').slideDown('slow');
});

$('#forgot_pass').click(function(){
    var formData = $('#forgotForm').serialize();    
    $.ajax({
        url: 'auth_student/forgot_pass',
        type: "POST",
        dataType: 'json',
        data: formData,
        beforeSend: function () {
            $('.formresponse')
                    .html('<p class="ajax_processing">Checking user...</p>')
                    .css('display','block');
        },
        success: function ( jsonRespond ) {
            if( jsonRespond.Status === 'OK'){
                $('.formresponse').html( jsonRespond.Msg );                
            } else {
                $('.formresponse').html( jsonRespond.Msg );
            }                
        }
    });
    return false;
});

$('#otp_submit').on('click', function(e){
    e.preventDefault();    
    var otp_credential = $('#otp_credential').serialize();
    $.ajax({
        url: 'auth_student/otp_verify',
        type: "POST",
        dataType: "json",
        cache: false,
        data: otp_credential,
        beforeSend: function(){
            $('#otpresponse').html('<p class="ajax_processing">Please Wait... Checking....</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                $('#otpresponse').html( jsonData.Msg );                
                window.location.href = "";
            } else {
                $('#otpresponse').html( jsonData.Msg );
            }                                    
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            $('#otpresponse').html( '<p> XML: '+ XMLHttpRequest + '</p>' );
            $('#otpresponse').append( '<p>  Status: '+textStatus + '</p>' );
            $('#otpresponse').append( '<p> Error: '+ errorThrown + '</p>' );            
        }  
    });        
});

$('#otp_resend').on('click', function (e) {
    e.preventDefault();
    reset_timer();

    $.ajax({
        url: 'auth_student/otp_resend',
        type: "get",
        dataType: "json",
        beforeSend: function(){
            $('#otpresponse').html('<p class="ajax_processing">Please Wait...</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                // console.log(jsonData.Msg);
                jQuery('#otp_code').val(null);
                $('#otpresponse').html( '<p class="ajax_success">Verification Code Resend Has Been Successful, Please Try New Code!</p>' );
                $('#email_otp_msg').html( '<p class="ajax_success" style="margin-top: 15px">Verification code has been resent to your Phone!</p>' );
            } else {
               window.location.href = "";
            }                                    
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            $('#otpresponse').html( '<p> XML: '+ XMLHttpRequest + '</p>' );
            $('#otpresponse').append( '<p> Status: '+textStatus + '</p>' );
            $('#otpresponse').append( '<p> Error: '+ errorThrown + '</p>' );            
        }  
    }); 

});

$('#otp_resend_in_mail').on('click', function (e) {
    e.preventDefault();
    reset_timer();

    $.ajax({
        url: 'auth_student/otp_resend?mail=true',
        type: "get",
        dataType: "json",
        beforeSend: function(){
            $('#otpresponse').html('<p class="ajax_processing">Please Wait...</p>');
        },
        success: function( jsonData ){
            if(jsonData.Status === 'OK'){
                jQuery('#otp_code').val(null);
                $('#otpresponse').html( '<p class="ajax_success">Verification Code Resend Has Been Successful, Please Try New Code!</p>' );
                $('#email_otp_msg').html( '<p class="ajax_success" style="margin-top: 15px">Verification code has been resent to your Email!</p>' );
            } else {
               window.location.href = "";
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            $('#otpresponse').html( '<p> XML: '+ XMLHttpRequest + '</p>' );
            $('#otpresponse').append( '<p> Status: '+ textStatus + '</p>' );
            $('#otpresponse').append( '<p> Error: '+ errorThrown + '</p>' );            
        }  
    });
});

function show_pass() {
    let x = document.getElementById("inputPassword");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}