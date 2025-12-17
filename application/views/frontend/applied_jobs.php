
<h3>Applied Jobs</h3>
<div class="panel panel-default">
    <div class="panel-heading">List of Applied Jobs</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>                            
                        <th width="50">SL</th>
                        <th>Job Title</th>
                        <th width="220">Status</th>
                        <th width="180">Application Time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $i = 1;
                    foreach ($jobs as $j) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $j->post_title; ?></td>
                            <td><?php echo $j->status; ?></td>
                            <td><?php echo globalDateTimeFormat($j->created_at); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Record: <?php echo $total_rows ?></span>                        
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</div>
