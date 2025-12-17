<script type="text/javascript">
//$(function () {        
//    $('table.highchart').highchartTable();
//    $('table.highchart-log').highchartTable();
//    $('table.highchart-income').highchartTable();
//});      

        
function student_enroll_action( id, status, reload = false ){
    
    var msg = '';
    if( status === 'Enrolled'){
        msg = 'Are you sure you want to approve?';
    } 
    if( status === 'Blocked'){
        msg = 'Are you sure you want to block?';
    }
    if( status === 'Delete'){
        msg = 'Are you sure you want to cancel?';
    }
    
    var yes = confirm( msg );
    if(!yes){ return false; }

    $.ajax({
        url: 'ajax/set_enroll_status',
        type: "post",
        dataType: 'json',
        data: { id : id, status: status },
        beforeSend: function () {
            $(`#row_${id}`).css('background','#ebffad');
        },
        success: function (respond) {
            $(`#row_${id}`).fadeOut('slow');
            if(reload === true ){
                location.reload();
            }
//            console.log ( respond );
        }
    });
}
</script>