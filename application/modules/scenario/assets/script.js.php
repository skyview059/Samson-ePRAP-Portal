<script>
    function delScen( id ){
        var yes = confirm('Confirm Delete');
        
        if(!yes){
            return false;
        }
        
        $.ajax({
            url: 'admin/scenario/ajax_del_action',
            type: "post",
            dataType: 'json',
            data: { id : id },
            beforeSend: function () {
                $(`#row_${id}`).css('background','red');
            },
            success: function (respond) {                
                if(respond.Status === 'OK'){
                    $(`#row_${id}`).fadeOut('slow');
                } else {
                    $(`#row_${id}`).css('background','inherit');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var msg = 'Something Went Wrong. Please Contact to Developer';
                $('#respond').html( `<p class="ajax_error">${msg}</p>` );
                $('html, body').animate({ scrollTop: $("#respond").offset().top }, 2000);                
            }
        });    
    }
    
    
    $(function(){         
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                $('.scen_id').iCheck("uncheck");
                $('.fa', this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                $('.scen_id').iCheck("check");
                $('.fa', this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });
    });
    
    
    
    function multiDelete(e) {
        e.preventDefault();
             
        var yes = confirm('Confirm Delete');        
        if(!yes){
            return false;
        }        
        
        var formData = $('#scenarios').serialize();
        
        $.ajax({
            url: 'admin/scenario/ajax_batch_delete',
            type: 'POST',
            dataType: "json",
            cache: false,
            data: formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Deleting. Please wait...</p>');                
            },
            success: function (jsonData) {
                $('#respond').html(jsonData.Msg);  
                if (jsonData.Status === 'OK') { 
                     setTimeout(function() { location.reload(); }, 1000 );                    
                }               
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var msg = 'Something Went Wrong. Please Contact to Developer';
                $('#respond').html( `<p class="ajax_error">${msg}</p>` );
            }
        });
        return false;
    }
</script>    