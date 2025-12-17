<script type="text/javascript">    
    $(document).on('click', '.link_for', function(){        
        var link_for = $(this).val();        
        $.ajax({
            type: 'POST',
            data: { link_for: link_for },
            url: 'admin/whatsapp/get_rel_id',
            dataType: 'html',
            beforeSend: function () {
                $('#rel_id').html('<option value="0">--Loading...--</option>');
            },
            success: function (respond) {
                $('#rel_id').html( respond );
            }
        });      
    });   
</script>    