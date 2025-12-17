<div class="box">
    <div class="box-header">
        <h3 class="box-title text-red">Need to Work Here only assigned Exam list here </h3>
    </div>
    <div class="box-body">
        <?php if(!$exams){ ?>
            <p class="ajax_notice">No Mock Exam Found!</p>
        <?php } ?>
        <?php foreach($exams as $e) { ?>                    
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Mock Exam</th>
                    <th>Centre</th>
                    <th>Date & Time</th>
                </tr>                
                <tr>
                    <td><?php echo $e->name; ?></td>
                    <td><?php echo $e->centre; ?><br/>
                        <em><span class="label_v2">Address:</span> <?php echo $e->address; ?></em>
                    </td>
                    <td><?php echo globalDateTimeFormat($e->datetime); ?></td>
                </tr>
            </table>        
        <?php } ?>
    </div>
</div>
