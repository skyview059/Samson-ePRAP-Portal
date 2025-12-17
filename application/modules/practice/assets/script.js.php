<script type="text/javascript">
    $(function(){
        $('.multi-dp').datepicker({
            multidate: true,
            format: 'yyyy-mm-dd', 
            startDate: '1d',
            todayHighlight: true
        });
    });  
    $(document).on('click', '.book-practice', function () {   
        var id = $(this).attr('id');
        $('#practice_schedule_id').val( id );        
        $('#bookingModal').modal('show');
    });
    
    $(document).on('click', '#send-booking', function (e) {
        e.preventDefault();
        var formData = $("#practiceBookingForm").serialize();

        $.ajax({
            url: "practices/book_my_seat",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                if (respond.Status === 'OK') {
                     location.reload();
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });    
    
    $(document).on('click', '.cancel-practice', function () {   
        var id = $(this).attr('id');
        $('#practice_booked_id').val( id );        
        $('#cancelModal').modal('show');
    });
    
    $(document).on('click', '#send-cancel', function (e) {
        e.preventDefault();
        var formData = $("#practiceCancelForm").serialize();

        $.ajax({
            url: "practices/cancel",
            type: 'post',
            dataType: 'json',
            data: formData,
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                if (respond.Status === 'OK') {
                     location.reload();
                } else {
                    $('#respond').html('<p class="ajax_error">' + respond.Msg + '</p>');
                }
            }
        });
    });
    
    
</script>