<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>View Result</title>
        <link rel="icon" href="assets/theme/images/fav.ico">
        <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css" type='text/css' media='all' />
        <style type="text/css">
            .result thead tr th,
            .result tfoot tr th,
            .result td 
            {
                vertical-align: middle;
                padding: 5px;
                font-size: 13px;
            }   
            table.table td { padding: 3px 5px; }        
            table.table th { padding: 3px 5px; }        
            
            fieldset {
                padding: 15px;
                margin-bottom: 20px;
            }
            legend {
                padding: 5px 10px;
                margin-bottom: 5px;
                font-size: 13pt;
                color: #333;        
                width: inherit;
            }
            fieldset, legend {
                border: 1px solid #DDD;
            }
            .drinkcard-cc{
                -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
                filter: grayscale(100%);                
                opacity: 0.1;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="body">
                <div class="box-body">
                    
                    <section class="content">       
                        <div class="box">            
                            <div class="box-header with-border">
                                <table class="table" style="margin:0;">
                                    <tr>
                                        <td width="250"><h1 style="margin:0;padding: 0;">Results</h1></td>
                                        <td align="right"><img alt="Samson Courses" src="<?php echo site_url('assets/admin/dist/img/logo.png'); ?>"/></td>
                                    </tr>
                                </table>
                                
                                <hr/>
                                <div class="row">

                                    <div class="col-md-6">
                                        <table class="table table-condensed table-bordered table-striped">
                                            <tr><td width="250"><?php echo $results->number_type; ?> Reference Number</td><td width="5">:</td><td><?php echo $results->gmc_number; ?></td></tr>
                                            <tr><td>Name</td><td>:</td><td><?= "{$results->fname} {$results->mname} {$results->lname}"; ?></td></tr>                
                                            <tr><td>Test</td><td>:</td><td><?= $results->exam_name; ?></td></tr>                
                                            <tr><td>Date</td><td>:</td><td><?= globalDateTimeFormat($results->datetime); ?></td></tr>                
                                            <tr><td>Pass/Fail</td><td>:</td><td><?= $pass_or_fail; ?></td></tr>                
                                        </table>
                                    </div>
                                </div>

                                <p><strong style="font-weight:bold;">Your Scores</strong><br/>
                                <?php echo $passing_criteria_str;?></p>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">   
                                    <fieldset>
                                        <legend>Initial Approach</legend>
                                        <table class="table table-condensed table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width='40'>S/L</th>
                                                    <th>Scenario</th>
                                                    <th class="text-center">Identifies Patient</th>
                                                    <th class="text-center">Greet the patient </th>
                                                    <th class="text-center">Introduces himself </th>
                                                    <th class="text-center">State the role</th>
                                                    <th class="text-center">Checks patient’s name preference </th>
                                                    <th class="text-center">Starts station well</th>                                
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $sl = 0;
                                            foreach ($results->details as $result) { ?>
                                                <tr>                                
                                                    <td><?= ++$sl; ?></td>
                                                    <td><?= $result->name; ?></td>
                                                    <td class="text-center"><?php echo $result->patient; ?></td>
                                                    <td class="text-center"><?php echo $result->greet_the_patient; ?></td>
                                                    <td class="text-center"><?php echo $result->introduces_himself; ?></td>
                                                    <td class="text-center"><?php echo $result->state_the_role; ?></td>
                                                    <td class="text-center"><?php echo $result->name_preference; ?></td>
                                                    <td class="text-center"><?php echo $result->starts_station_well; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Face</legend>
                                        <table class="table table-striped table-condensed table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width='40'>S/L</th>
                                                    <th>Scenario</th>
                                                    <th class="text-center">Face</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $sl = 0;
                                            foreach ($results->details as $result) { ?>
                                                <tr>   
                                                    <td><?= ++$sl; ?></td>
                                                    <td><?= $result->name; ?></td>
                                                    <td class="text-center">
                                                        <?php echo getFace($result->face); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </fieldset>

                                    <!--  <h4 class="h4 text-center"><u>Quantitative Feedback</u></h4>-->                                                                                                                                                                                   
                                    <fieldset>
                                        <legend>Quantitative Feedback</legend>                   
                                        <table class="table table-condensed table-striped table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width='40'>S/L</th>
                                                    <th>Scenario</th>
                                                    <th class="text-center">Data-gathering, technical and assessment skills</th>
                                                    <th class="text-center">Clinical management Skills</th>
                                                    <th class="text-center">Interpersonal Skills</th>
                                                    <th class="text-center">Total Mark </th>
                                                    <th class="text-center">Pass Score</th>
                                                    <th class="text-center">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sl = 0;
                                                $total_assessment = $total_clinical = $total_interpersonal = $grand_total_score = $total_pass_mark = 0;
                                                foreach ($results->details as $result) {
                                                    $total_score = $result->technical_skills + $result->clinical_skills + $result->interpersonal_skills;
                                                    $total_assessment += $result->technical_skills;
                                                    $total_clinical += $result->clinical_skills;
                                                    $total_interpersonal += $result->interpersonal_skills;
                                                    $grand_total_score += $total_score;
                                                    $total_pass_mark += $result->pass_mark;
                                                    $grade = ($total_score >= $result->pass_mark ) ? 'Pass' : 'Fail';
                                                    ?>
                                                    <tr>  
                                                        <td><?= ++$sl; ?></td>
                                                        <td><?= $result->name; ?></td>
                                                        <td class="text-center"><?= $result->technical_skills; ?></td>
                                                        <td class="text-center"><?= $result->clinical_skills; ?></td>
                                                        <td class="text-center"><?= $result->interpersonal_skills; ?></td>
                                                        <td class="text-center"><?= $total_score; ?></td>
                                                        <td class="text-center"><?= number_format_fk($result->pass_mark, 2); ?></td>
                                                        <td class="text-center"><?= $grade; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-right">Total Score</th>
                                                    <th class="text-center"><?= $total_assessment; ?></th>
                                                    <th class="text-center"><?= $total_clinical; ?></th>
                                                    <th class="text-center"><?= $total_interpersonal; ?></th>
                                                    <th class="text-center"><?= $grand_total_score; ?></th>
                                                    <th class="text-center"><?= number_format_fk($total_pass_mark, 2); ?></th>
                                                    <th class="text-center"><?= $pass_or_fail; ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>

                                    <!-- <h4 class="h4 text-center"><u>Qualitative Feedback</u></h4> -->                                
                                    <fieldset>
                                        <legend>Qualitative Feedback</legend> 
                                        <table class="table table-condensed table-striped table-bordered">
                                            <thead>
                                                <tr>    
                                                    <th width='40'>S/L</th>
                                                    <th>Scenario</th>
                                                    <th class="text-center">Consultation</th>
                                                    <th class="text-center">Issues</th>
                                                    <th class="text-center">Diagnosis</th>
                                                    <th class="text-center">Examination</th>
                                                    <th class="text-center">Findings</th>
                                                    <th class="text-center">Management</th>
                                                    <th class="text-center">Rapport</th>
                                                    <th class="text-center">Listening</th>
                                                    <th class="text-center">Language</th>
                                                    <th class="text-center">Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cross = '<img src="' . site_url('assets/theme/images/cross.png') . '">';
                                                $sl = 0;
                                                foreach ($results->details as $result) {
                                                    ?>
                                                    <tr>     
                                                        <td><?= ++$sl; ?></td>
                                                        <td><?= $result->name; ?></td>
                                                        <td class="text-center"><?= showCross($result->consultation); ?></td>
                                                        <td class="text-center"><?= showCross($result->issues); ?></td>
                                                        <td class="text-center"><?= showCross($result->diagnosis); ?></td>
                                                        <td class="text-center"><?= showCross($result->examination); ?></td>
                                                        <td class="text-center"><?= showCross($result->findings); ?></td>
                                                        <td class="text-center"><?= showCross($result->management); ?></td>
                                                        <td class="text-center"><?= showCross($result->rapport); ?></td>
                                                        <td class="text-center"><?= showCross($result->listening); ?></td>
                                                        <td class="text-center"><?= showCross($result->language); ?></td>
                                                        <td class="text-center"><?= showCross($result->time); ?></td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>                                                
                                    </fieldset>



                                    <fieldset>
                                        <legend>Examiner’s comments</legend>
                                        <table class="table table-condensed table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th width='40'>S/L</th>
                                                <th width="220">Scenario</th>
                                                <th class="text-center">Comment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $sl = 0;
                                            foreach ($results->details as $result) { ?>
                                                <tr>      
                                                    <td><?= ++$sl; ?></td>
                                                    <td><?= $result->name; ?></td>
                                                    <td class="text-center"><?php echo $result->examiner_comments; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </section>

                    
                    
                </div>
            </div>
        </div>
    </body>
</html>