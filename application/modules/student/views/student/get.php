<?php if(!$students){ ?>
    <p class="ajax_notice">No Student Found under <?= $exam_name; ?></p>
    
    <p class="text-center">
        <a href="admin/student/create?id=<?= $exam_id; ?>" target="_blank" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Click here to add Student
        </a>
    </p>
<?php } else { ?>
    
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">Search</span>
            <input type="text" class="form-control" id="keyword" 
            onkeyup="searchGMC_Number();"
            autocomplete="off"
            placeholder="Search by Code-Number & Name"/>
        </div>    
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" >
            <thead>
                <tr>
                    <th width="40" class="text-center">Mark</th>
                    <th width="80" class="text-center">GMC/G No</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody id="scenario">
            <?php foreach ($students as $s) { 

                $checked = in_array($s->id, $marked ) ? ' checked ' : '';
                $options = "<input {$checked} class='mark' type='checkbox' name='scenario[]' value='{$s->id}'>";
                ?>
                <tr>
                    <td class="text-center"><?php echo $options; ?></td>
                    <td class="text-center search"><?php echo $s->gmc; ?></td>
                    <td class="search"><?php echo $s->full_name; ?></td>                          
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    
    <script type="text/javascript">
        function searchGMC_Number() {    
            var search, lrnr, tr, i, name, match;
            search  = $('#keyword').val().toUpperCase();    
            lrnr    = document.querySelector('#scenario');
            tr      = lrnr.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                name  = $(tr[i]).find('td.search').text().toUpperCase();
                match = name.indexOf(search);            
                if (match > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }   

        $('.mark').on('click', function() {
            var len = $(".mark:checked").length; 
            $('#selected').text(len);
        });
    </script>  

<?php } ?>
  

