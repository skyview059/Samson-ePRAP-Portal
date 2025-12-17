<link href="assets/lib/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
    }
    .pagination>li>a:hover {border-color: transparent;}
    .pagination { margin: 0;}
</style>
<div class="table-responsive">
    <div style="padding:0 15px;">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
            <tr>
                <th width="40" class="text-center">Mark</th>
                <th width="80" class="text-center">Ref. No</th>
                <th>Scenario Name</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($scenarios as $scenario) { 

                $checked = in_array($scenario->id, $marked ) ? ' checked ' : '';
                $options = "<input {$checked} class='mark' type='checkbox' name='scenario[]' value='{$scenario->id}'>";
                ?>
                <tr>
                    <td class="text-center"><?php echo $options; ?></td>
                    <td class="text-center"><?php echo sprintf('%03d', $scenario->reference_number); ?></td>
                    <td><?php echo $scenario->name; ?></td>                          
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="assets/lib/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="assets/lib/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#datatable").dataTable({ 
            "pageLength": 1000,
            "order": [[ 1, 'asc' ]]
        });
        
        $('.mark').on('click', function() {
            var len = $(".mark:checked").length; 
            $('#selected').text(len);
        });
    });
    
    
    $(document).keypress(function(e){        
        console.log( e.keyCode );
    });
</script>    

