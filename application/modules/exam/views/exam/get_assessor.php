<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">Search</span>
        <input type="text" autocomplete="off" class="form-control" id="keyword" 
        onkeyup="searchScenario();" 
        placeholder="Search Assessor"/>
    </div>    
</div>


<div class="table-responsive">
    <div style="padding:0 15px;">
        
        <table class="table table-striped table-condensed table-bordered" id="datatable">
            <thead>
                <tr>
                    <th width="40" class="text-center">Mark</th>
                    <th>Full Name</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody id="scenario">
            <?php foreach ($assessors as $as) { 

                $checked = in_array($as->id, $marked ) ? ' checked ' : '';
                $options = "<input {$checked} type='checkbox' name='user_id[{$as->id}]' value='{$as->id}'>";
                ?>
                <tr>
                    <td class="text-center"><?php echo $options; ?></td>                    
                    <td class="name"><?php echo $as->first_name .' '. $as->last_name; ?></td>                    
                    <td><?php echo $as->role_name; ?></td>                    
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function searchScenario() {    
        var search, lrnr, tr, i, name, match;
        search  = $('#keyword').val().toUpperCase();    
        lrnr    = document.querySelector('#scenario');
        tr      = lrnr.getElementsByTagName('tr');

        for (i = 0; i < tr.length; i++) {
            name  = $(tr[i]).find('td.name').text().toUpperCase();            
            match = name.indexOf(search);            
            if (match > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    } 
</script>    