<style type="text/css">
    table.table tr th,
    table.table tr td{
        vertical-align: middle;
    }
</style>
<h3>Results</h3>
<div class="panel panel-default">
  <div class="panel-heading">List of Mock Exam Result</div>
  <div class="panel-body">
      <?php
            if(!$results){
                echo '<p class="ajax_notice">No Mock Exam Result Found You!</p>';
            } else {
        ?>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>                            
                        <th width="50">SL</th>
                        <th>Mock Exam</th>
                        <th>Exam Centre</th>
                        <th>Centre Address</th>
                        <th>Exam Date & Time</th>
                        <th>Result Published at</th>
                        <th class="text-center" style='width: 20%;'>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    foreach ($results as $key => $result) {
                        $link = site_url("results/{$result->exam_schedule_id}");
                        $download_link = site_url('results/download/'.$result->exam_schedule_id);
                    ?>
                    <tr>
                        <td><?= sprintf('%02d',$start++); ?></td>
                        <td><?= $result->exam_name; ?></td>
                        <td><?= getShortContent($result->centre_name, 20); ?></td>
                        <td><?= getShortContent($result->address,20); ?></td>
                        <td><?= globalDateTimeFormat($result->datetime); ?></td>
                        <td><?= globalDateTimeFormat($result->published_at); ?></td>
                        <td class="text-center">
                            
                            <a style="color: #0e5389; font-weight: bold; margin-right: 15px;" href="<?= $link; ?>" class="">
                                View Result                                 
                                
                            </a>
                            
                            <a style="color: #0e5389; font-weight: bold;" href="<?= $download_link; ?>" class="">
                                Download                                   
                                
                            </a>
                                                        
                        </td>
                    </tr>
                    <?php } //if?>
                </tbody>
            </table>
        </div>
            <?php } ?> 
  </div>
</div>
