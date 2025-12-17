<script>
    $('#student_id').on('change', function(){
        var id = $(this).val();
        $('.student_id').val( id );        
                
        $.ajax({
            url: 'admin/personal_dev_plan/check',
            type: "POST",
            dataType: 'text',
            data: { id: id },
            beforeSend: function () {
                $('#student_btn').addClass('hidden');
                $('#student_notice').removeClass('hidden');
                $('#student_notice')
                        .html('<p class="ajax_processing">Loading...</p>')
                        .css('display','block');
            },
            success: function ( respond ) {                
                if( respond === '0'){
                    $('#student_btn').removeClass('hidden');
                    $('#student_notice').addClass('hidden');
                } else {
                    $('#student_notice')
                        .html('<p class="ajax_error">Personal Development Plan Already Setted...</p>');
                }
            }
        });
    });
</script>