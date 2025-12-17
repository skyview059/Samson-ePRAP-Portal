<h3>Let’s Join Whats App Group</h3>
<div class="panel panel-default">
  <div class="panel-heading">Whats App Group</div>
  <div class="panel-body clearfix"> 

      <table class="table table-striped table-bordered table-condensed">
          <thead>
                <tr>
                    <th width="200">Link Category</th>
                    <th>Group Name</th>
                </tr>
          </thead>  
          <?php foreach($links as $key => $value) {?>
          <tr>
              <td><?php echo $key; ?></td>
              <td><?= $value; ?></td>              
          </tr>
          <?php } ?>
                
      </table>
  </div>
</div>