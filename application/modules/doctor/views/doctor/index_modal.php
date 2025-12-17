<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Suggestion List for Recruitment Manager</h4>
            </div>

            <div class="modal-body" >
                <div class="js_respond"></div>
                <div class="get_shortlist"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default on-close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span> 
                    Close
                </button>
                <button type="button" class="btn btn-primary " onclick="save_suggestion();">
                    <i class="fa fa-save"></i> 
                    Save Suggestion
                </button>
            </div>
        </div>
    </div>
</div>



<script>
    
    function yes(){        
        $('.show_hiddens').css('display', 'block');
        $('.modal-footer').css('display','block');
        $('.revers_hiddens').css('display','none');
    }
    
    function no(){
        $('#suggestion').trigger("reset");
        $("#recruitment_manager_id").val('').trigger('change');
        $('#popup').modal('hide');
    }
    
    $('.on-close').on('click',function(){
        $('.modal-footer').removeAttr('style');
    });
    
    function popup() {
//        var id = $('#recruitment_manager_id').val();
//        $('.modal-footer').css('display','none');
        var FormData = $('#suggestion').serialize();
        
        $('.js_respond').empty();
        $('#popup').modal({
            show: 'false',
            backdrop: 'static'
        });
        
        $.ajax({
            url: 'admin/doctor/get_shortlist',
            type: "POST",
            dataType: "HTML",
            data: FormData,
            beforeSend: function () {
                $('.get_shortlist').html('<p class="ajax_processing">Loading...</p>');
            },
            success: function (msg) {
                $('.get_shortlist').html(msg);
            }
        });
    }
    
    
    
    function save_suggestion () {
        var formData = $('#suggestion').serialize();
        $.ajax({
            url: 'admin/doctor/save_suggestion',
            type: "POST",
            dataType: "JSON",
            data: formData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Loading...</p>');
            },
            success: function (respond) {
                $('.js_respond').html(respond.Msg);
                if(respond.Status === 'OK'){
                    setTimeout(function(){ location.reload(); }, 1000);
                }
            }
        });
    }        
</script>