<div class="modal fade" id="modal-compose-message">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="compose" id="compose" method="post" enctype="multipart/form-data"
                  action="<?php echo base_url('admin/message/send_message_from_modal'); ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Compose New Message</h4>
                </div>
                <div class="modal-body">
                    <div id="respond"></div>
                    <input type="hidden" name="student_id" value="<?php echo $id; ?>"/>
                    <input type="hidden" name="subject" value="<?php echo $subject; ?>"/>
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="compose_send_btn">
                        <i class="fa fa-envelope-o"></i>
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( "#compose_send_btn" ).click(function( e ) {
        e.preventDefault();

        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances['message'].updateElement();
        }

        var fd = $('#compose').serialize();

        var desc = CKEDITOR.instances['message'].getData();
        if(!desc) {
            $('#respond').html('<p class="ajax_error">Please write message..</p>');
            return false;
        }

        $.ajax({
            url: 'admin/message/send_message_from_modal',
            type: "POST",
            dataType: "json",
            cache: false,
            data: fd,
            beforeSend: function(){
                $('#respond').html('<p class="ajax_processing">Please Wait... Checking....</p>');
            },
            success: function( jsonData ){
                if(jsonData.Status === 'OK'){
                    $('#respond').html( jsonData.Msg );
                    setTimeout(function(){
                        $('#modal-compose-message').modal('hide');
                        $('#compose').reset();
                    }, 2000);
                } else {
                    $('#respond').html( jsonData.Msg );
                }
            }
        });
    });
</script>

<?php loadCKEditor5ClassicBasic(['#message']); ?>

<script>
    $(document).ready(function () {
        $('.select2').select2({placeholder: "Select an Email Template", allowClear: true});
    });
</script>