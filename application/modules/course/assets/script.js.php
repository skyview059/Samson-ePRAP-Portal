<link rel="stylesheet" href="assets/lib/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css">
<script src="assets/lib/plugins/moment/min/moment.min.js"></script>
<script src="assets/lib/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function removeRow( div_id ){
        if(!confirm('Are you sure to remove this dateset?')){
            return false;
        } 
        $( div_id ).remove();
    }
    
    
    function deleteRowAndDB( row_id ){
        var yes = confirm('Are you sure to Delete this schedule?');
        if(yes){
            $.ajax({
                type: 'POST',
                data: { row_id: row_id },
                url: 'admin/course/delete_row',
                dataType: 'json',
                beforeSend: function () {
                    $( `#row_${ row_id }` ).css('background-color','red');
                },
                success: function (respond) {
                    $( `#row_${ row_id }` ).fadeOut();
                    setTimeout(function(){ $( `#row_${ row_id }` ).remove(); }, 2000);                    
                    console.log( respond );
                }
            });     
        }
    }
    
    $(function ( ) {        
        var index = 4;
        $('#add_new_box').click(function () {                                                   
            var html = `<tr id="row_${index}">
                            <td>${index}</td>                                
                            <td><input type="text" name="dates[${index}][start][date]" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index}][start][hh]" class="form-control">
                                            <?= numericDropDown2(1, 12, 1, 9 ); ?>
                                        </select>
                                    </span>
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index}][start][mm]" class="form-control">
                                            <?= numericDropDown2(0, 45, 15, 0 ); ?>
                                        </select>  
                                    </span>
                                    <span class="input-group-addon">
                                        <label><input type="radio" name="dates[${index}][start][slot]" checked="checked" value="AM">&nbsp;AM&nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio" name="dates[${index}][start][slot]" value="PM">&nbsp;PM&nbsp;&nbsp;&nbsp;</label>
                                    </span>
                                </div>
                            </td>
                            <td><input type="text" name="dates[${index}][end][date]" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index}][end][hh]" class="form-control">
                                            <?= numericDropDown2(1, 12, 1, 9 ); ?>
                                        </select>
                                    </span>

                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index}][end][mm]" class="form-control">
                                            <?= numericDropDown2(0, 45, 15, 0 ); ?>
                                        </select>  
                                    </span>

                                    <span class="input-group-addon">
                                        <label><input type="radio" name="dates[${index}][end][slot]" value="AM">&nbsp;AM&nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio" name="dates[${index}][end][slot]" checked="checked" value="PM">&nbsp;PM&nbsp;&nbsp;&nbsp;</label>
                                    </span>
                                </div>
                            </td>
                            <td><span class="input-group-btn">
                                    <span class="btn btn-xs btn-danger" onclick=" removeRow('#row_${index}'); ">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </span>
                            </td>
                        </tr>`;                                        
            $('#dates_row tbody').append( html );
            ++index;
            $('.js_datepicker').datepicker({ 
                format: 'yyyy-mm-dd',                
                autoclose: true,
                todayBtn: true,
                todayHighlight: true
            });
        });        
        
        $('#add_row_for_update').click(function () {            
            var html = `<tr id="row_${index2}">
                            <td class="text-center">--</td>                                
                            <td>
                                <input name="dates[${index2}][id]"  value="null" type="hidden"/>
                                <input type="text" name="dates[${index2}][start][date]" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>                                
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index2}][start][hh]" class="form-control">
                                            <?= numericDropDown2(1, 12, 1, 9 ); ?>
                                        </select>
                                    </span>
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index2}][start][mm]" class="form-control">
                                            <?= numericDropDown2(0, 45, 15, 0 ); ?>
                                        </select>  
                                    </span>
                                    <span class="input-group-addon">
                                        <label><input type="radio" name="dates[${index2}][start][slot]" checked="checked" value="AM">&nbsp;AM&nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio" name="dates[${index2}][start][slot]" value="PM">&nbsp;PM&nbsp;&nbsp;&nbsp;</label>
                                    </span>
                                </div>
                            </td>
                            <td><input type="text" name="dates[${index2}][end][date]" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index2}][end][hh]" class="form-control">
                                            <?= numericDropDown2(1, 12, 1, 9 ); ?>
                                        </select>
                                    </span>

                                    <span class="input-group-addon adjust-dp">
                                        <select name="dates[${index2}][end][mm]" class="form-control">
                                            <?= numericDropDown2(0, 45, 15, 0 ); ?>
                                        </select>  
                                    </span>

                                    <span class="input-group-addon">
                                        <label><input type="radio" name="dates[${index2}][end][slot]" value="AM">&nbsp;AM&nbsp;&nbsp;&nbsp;</label>
                                        <label><input type="radio" name="dates[${index2}][end][slot]" checked="checked" value="PM">&nbsp;PM&nbsp;&nbsp;&nbsp;</label>
                                    </span>
                                </div>
                            </td>
                            <td><input type="text" class="form-control" value="0" style="width:75px;" readonly="readonly" /></td>
                            <td><span class="input-group-btn">
                                    <span class="btn btn-xs btn-danger" onclick="removeRow('#row_${index2}'); ">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </span>
                            </td>                            
                        </tr>`;
            $('#course_dates tbody').append( html );
            ++index2;            
            $('.js_datepicker').datepicker({ 
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayBtn: true,
                todayHighlight: true
            });
        });        	
    });
    
    

    $('.want2buy').on('click', function(){            
        var checked = $(this).is(":checked");
        var id      = $(this).val();    
        if( checked ){            
            $(`#slot_${id}`).removeClass('hidden');
        } else {
            $(`#slot_${id}`).addClass('hidden');            
            $(`#slot_${id}`).find('.ckbx').prop('checked', '');
            $(`#slot_${id}`).find('.ckbx').prop('checked', false);
        }        
    });
</script>    