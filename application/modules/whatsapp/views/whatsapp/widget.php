<div class="form-group">
    <input type="hidden" name="link_for" value="<?= $link_for; ?>"/>
    <label for="passing_criteria" class="col-sm-2 control-label">Whatsapp Group <sup>*</sup></label>
    <div class="col-sm-8">
        <div class="col-md-12">            
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">Select:</span>
                    <select class="form-control" name="whatsapp_id" id="whatsapp_id">
                        <?= getDropDownWhatsapp($link_for); ?>
                    </select>                    
                </div>
            </div>           
        </div>
    </div>
</div>

<div class="modal fade" id="popup_wa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create New WhatsApp Group</h4>
            </div>

            <div class="modal-body">  
                <div id="respond"></div>
                <div class="form-group">
                    <label for="label" class="col-sm-3">Group For </label>
                    <div class="col-sm-9">
                        <input id="wa_link_for" value="<?= $link_for; ?>" type="text" class="form-control" readonly="readonly"/>   
                    </div>
                </div>  
                <div class="form-group">
                    <label for="label" class="col-sm-3">Group Name </label>
                    <div class="col-sm-9">
                        <input type="text" id="wa_title" class="form-control"/>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="label" class="col-sm-3">Group Link </label>
                    <div class="col-sm-9">
                        <input type="text" id="wa_link" class="form-control"/>
                    </div>
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span> 
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="add_new_wa_group">
                    <i class="fa fa-save"></i>
                    Create and Select Group
                </button>
            </div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#whatsapp_id').on('change', function () {
        var value = $(this).val();
        if (value === 'new') {
            $('#popup_wa').modal({ show: 'false' });
        } else {
            $('#new_whatsapp').hide();
        }
    });
    
    $(document).on('click', '#add_new_wa_group', function(){ 
        var error = 0;
        var wa_title = $('#wa_title').val();
        if (!wa_title) {
            $('#wa_title').addClass('required');
            error = 1;
        } else {
            $('#wa_title').removeClass('required');
        }
        var wa_link = $('#wa_link').val();
        if (!wa_link) {
            $('#wa_link').addClass('required');
            error = 1;
        } else {
            $('#wa_link').removeClass('required');
        }
        
        if(error){ return false; }

        $.ajax({
            type: 'POST',
            data: {
                link_for: $('#wa_link_for').val(),
                title: $('#wa_title').val(),
                link: $('#wa_link').val()
            },
            url: 'admin/whatsapp/widget_action',
            dataType: 'json',
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Sending...</p>');
            },
            success: function (respond) {                                
                if (respond.Status === 'OK') { 
                    $('#whatsapp_id').html(respond.Msg);                    
                    $('#popup_wa').modal('hide');
                    $('#respond').html('');
                }
            }
        });
    });
</script>