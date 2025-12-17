<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Result  <small>Panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Results</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Exam Tree</div>
                <div class="panel-body" style="height:600px; overflow-y: scroll;">                    
                    <?php echo $view_tree; ?>                    
                </div>
            </div>         
        </div>
        
        
        
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    View Exam Results
                </div>
                <div class="panel-body" style="padding: 20px;">
                    <?php
                    if(!$id){
                        echo '<div class="box-body">';
                        echo '<p class="ajax_notice">Please select exam to see result</p>';
                        echo '</div>';
                    } else {
                ?>
                
                    
                    <h2 class="no-margin">Exam Name: <?php echo $course_name; ?></h2>
                    <h4>
                        Centre: <?php echo ($centre_name); ?>, <?php echo ($centre_address); ?><br/>
                        Date & Time: <?php echo globalDateTimeFormat($datetime); ?>
                    </h4>                        

                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="40">S/L</th>
                                    <th>Student</th>
                                    <th class="text-center">Total Score</th>
                                    <th class="text-center">Pass Mark</th>
                                    <th class="text-center">Stations Passed</th>
                                    <th class="text-center">Required Stations</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Exam</th>
                                    <th class="text-center">Exam Date & Time</th>
                                    <th class="text-center" width="180">Result</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($results as $result) {                                    
                                    $sum = Tools::getStudentResultSummery( $result->student_id, $result->exam_schedule_id );
                                    ?>
                                    <tr>
                                        <td><?php echo ++$start; ?></td>
                                        <td><?php echo "{$result->fname} {$result->mname} {$result->lname}"; ?></td>
                                        <td class="text-center"><?php echo $sum['your_score']; ?></td>
                                        <td class="text-center"><?php echo $sum['required_score']; ?></td>
                                        <td class="text-center"><?php echo $sum['passed_station']; ?></td>
                                        <td class="text-center"><?php echo $sum['required_pass_station']; ?></td>
                                        <td class="text-center"><?php echo passOrFailColor($sum['pass_fail_result']); ?></td>
                                        <td class="text-center"><?php echo $result->exam_name; ?></td>
                                        <td class="text-center"><?php echo globalDateTimeFormat($result->exam_datetime); ?></td>
                                        <td class="text-center">
                                            <?php
                                            echo anchor(site_url(Backend_URL . "assess/result/details/{$result->student_id}/{$result->exam_schedule_id}" ), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-primary"');
                                            echo anchor(site_url(Backend_URL . "assess/result/download/{$result->student_id}/{$result->exam_schedule_id}"), '<i class="fa fa-fw fa-download"></i> Download', 'class="btn btn-xs btn-info"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                   
                
                <div class="box-footer">
                    <div class="row">                
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Results: <?php echo $total_rows ?></span>

                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination ?>
                        </div>                
                    </div>
                </div>
                    <?php } ?>
                </div>
            </div> 

            
        </div>
    </div>
    
    
</section>