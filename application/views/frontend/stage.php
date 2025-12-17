<?php echo getStudentProcessBar(); ?>

    <h3>Update Stage</h3>

<div class="panel panel-default">
  <div class="panel-heading">Stage of Progression:</div>
  <div class="panel-body">
  <div id="respond"></div> 
<form name="save_doc" id="save_status" method="POST" class="form-horizontal">
    <table class="table">
        <tr>
            <th width="100px">S/L</th>
            <th width="500px">Title</th>
            <th width="150">Completed</th>
            <th width="400">Certificate</th>        
            <th width="300">Action</th>        
        </tr>

        <?php foreach($progress as $p) {?>
        <tr>
            <td><?= ++$sl; ?></td>
            <td><?= $p->title; ?></td>
            <td>
                <?php if($p->id==$p->progression_id){
                    echo $p->completed;
                    }else{
                ?>
                <input type="checkbox" class="isComplete" id="<?= $p->id;?>" name="completed_<?= $p->id;?>" id="progression_id_<?= $p->id;?>" value="Yes" />
                <?php }?>
                
            </td>
            <td>
                <?php if($p->id==$p->progression_id){
                    if(empty($p->file)){
                        echo '<em style="color:#ccc;">No Certificate Submited. (Delete to submit file again)</em>';
                    } else {
                        echo download_attachment($p->file);
                    }
                    }else{
                ?>
                <input type="file" id="file_<?= $p->id;?>" class="hidden" name="progression_file_<?= $p->id;?>"/>
                <?php }?>
            </td>
            <td>
                <?php if($p->id==$p->progression_id){?>
                
                    <?php if($p->file){                         
                            echo filePreviewBtn( $p->file ); 
                     } ?>
                
                    <a href="<?= site_url('delete_stage/' . $p->student_progression_id) ?>" 
                       onclick="return confirm('Confirm Delete');"
                       class="btn btn-danger btn-xs">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete &nbsp;                                           
                        
                    </a>
                <?php  }else{?>
                    <button type="button" id="btn_<?= $p->id;?>" class="btn btn-primary hidden" onclick="return saveStatus(<?= $p->id;?>);" >
                        <i class="fa fa-save"></i>
                        Save
                    </button>
                <?php }?>
                
            </td>
        </tr>
        <?php } ?>

    </table>
</form>
  </div>
</div>

<script type="text/javascript">
    
    $('.isComplete').on('click', function(){
        var id = $(this).attr('id');
        var checked = $(this).is(":checked");
        if( checked === true ){
            $(`#file_${id}`).removeClass('hidden');
            $(`#btn_${id}`).removeClass('hidden');
        } else {
            $(`#file_${id}`).addClass('hidden');
            $(`#btn_${id}`).addClass('hidden');
        }
    });
    
    
    function saveStatus(id) {
//        e.preventDefault();
//        $( "#save_status" ).submit();
        var data = new FormData(document.getElementById("save_status"));
        data.append('progression_id', id);
        
        $.ajax({
            url: 'upload_stage',
            type: "POST",
            dataType: "json",
            data: data,
            enctype: 'multipart/form-data',
            beforeSend: function () {
                $('#respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success: function (respond) {
                $('#respond').html(respond.Msg);
                if (respond.Status === 'OK') {
                    document.getElementById("save_status").reset();
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
            processData: false, // tell jQuery not to process the data
            contentType: false   // tell jQuery not to set contentType
        });
        return false;
    }
    
    function selectProgressionCheckbox(progression_id){
        if($('#progression_id_'+progression_id).is(":checked")){
            $("#progression_row_"+progression_id).show('slow');
        }else{
            $("#progression_row_"+progression_id).hide('slow');
        }
        
    }

</script>


