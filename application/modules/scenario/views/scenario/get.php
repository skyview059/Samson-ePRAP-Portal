<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">Search</span>
        <input type="text" autocomplete="off" class="form-control" id="keyword" 
        onkeyup="searchScenario();" 
        placeholder="Search Scenario"/>
    </div>    
</div>

<?php
    
    if(!$scenarios){
        echo '<p class="ajax_notice">No Scenario Found!.</p>';
        echo "<p><a class='btn btn-link' href=\"admin/scenario/create?id={$exam_id}\">Click Here</a> to add scenario</p>";
    }  else {
    
?>
<div class="table-responsive">

    <table class="table table-striped table-bordered" >
        <thead>
            <tr>
                <th width="40" class="text-center">Mark</th>
                <th width="80" class="text-center">Ref. No</th>
                <th>Scenario Name</th>
            </tr>
        </thead>
        <tbody id="scenario">
        <?php foreach ($scenarios as $scenario) { 
            $checked = in_array($scenario->id, $marked ) ? ' checked ' : '';
//            $options = "<input onclick='save_single_scenario('".$exam_schedule_id.','.$scenario->id."')' {$checked} class='mark' type='checkbox' name='scenario[]' value='{$scenario->id}'  />";
            ?>
            <tr>
                <td class="text-center">
                    <input type="checkbox" name="scenario[]" id="scenario_<?php echo $exam_schedule_id.'_'.$scenario->id?>" <?php echo $checked;?> class="mark" value="<?php echo $scenario->id;?>" onclick="save_assign_scenario(<?php echo $exam_schedule_id;?>, <?php echo $scenario->id;?>)" />
                    <?php // echo $options; ?>
                </td>
                <td class="text-center ref_id"><?php echo sprintf('%03d', $scenario->reference_number); ?></td>
                <td class="ref_id"><?php echo $scenario->name; ?></td>                          
            </tr>
        <?php } ?>
        </tbody>
    </table>
    
</div>
<?php } ?>
<script type="text/javascript">
    function searchScenario() {    
        var search, lrnr, tr, i, name, match;
        search  = $('#keyword').val().toUpperCase();    
        lrnr    = document.querySelector('#scenario');
        tr      = lrnr.getElementsByTagName('tr');

        for (i = 0; i < tr.length; i++) {
            name  = $(tr[i]).find('td.ref_id').text().toUpperCase();            
            match = name.indexOf(search);            
            if (match > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }   
    
    function save_assign_scenario(exam_schedule_id, scenario_id){
        var checked = $('#scenario_'+exam_schedule_id+'_'+scenario_id).is(':checked');
        var checkid = $('#scenario_'+exam_schedule_id+'_'+scenario_id);
        if(exam_schedule_id==='' || exam_schedule_id===0){
            toastr.error("Exam schedule is required!");
            (checked) ? checkid.prop("checked", false) : checkid.prop("checked", true);
            return false;
        }
       
        if(scenario_id==='' || scenario_id===0){
            toastr.error("Scenario is required!");
            (checked) ? checkid.prop("checked", false) : checkid.prop("checked", true);
            return false;
        }
        
        $.ajax({
            url: 'admin/scenario/save_assign_scenario',
            type: 'POST',
            dataType: 'json',
            data: {exam_schedule_id: exam_schedule_id, scenario_id: scenario_id},
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success: function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    toastr.remove();
                    toastr.success("Exam scenario updated successfully!");
                }else{
                    toastr.error("Something went wrong");
                    (checked) ? checkid.prop("checked", false) : checkid.prop("checked", true);
                }
            }
        });
        
        //Get count checked length
        var len = $(".mark:checked").length; 
        $('#selected').text(len);
        
        return false;
    }
    

</script>    

