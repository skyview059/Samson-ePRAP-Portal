<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .result thead tr th,
    .result tfoot tr th,
    .result td {
        vertical-align: middle;
    }
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
</style>
<section class="content-header">
    <h1> View Result</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Results</li>
    </ol>
</section>

<section class="content">       
    <div class="box">            
        <div class="box-header with-border">

            <h1>Results</h1>
            <hr/>
            <div class="row">

                <div class="col-md-6">
                    <table class="table table-condensed table-bordered table-striped">
                        <tr><td width="200"><?php echo $results->number_type; ?> Reference Number</td><td width="5">:</td><td><?php echo $results->gmc_number; ?></td></tr>
                        <tr><td>Name</td><td>:</td><td><?= "{$results->fname} {$results->mname} {$results->lname}"; ?></td></tr>                
                        <tr><td>Test</td><td>:</td><td><?= $results->exam_name; ?></td></tr>                
                        <tr><td>Date</td><td>:</td><td><?= globalDateTimeFormat($results->datetime); ?></td></tr>                
                        <tr><td>Pass/Fail</td><td>:</td><td><?= 'xxx'; ?></td></tr>                
                    </table>
                </div>
            </div>

            <p><strong>Your Scores</strong><br/>
                From 1 January 2017, candidates must achieve or exceed both 
                the pass mark and gain a pass in  minimum of 11 stations to pass
                the {$corse_name} assessment</p>

            <p>You scored 87 and passed 5 stations 
                a minimum scored of 118.8 was required to pass</p>

            <p>your scores of each station</p>
        </div>
        <div class="box-body">
            <div class="table-responsive">                
                <h4 class="h4 text-center"><u>Quantitative Feedback</u></h4>
                <table class="table table-striped table-bordered table-condensed result">
                    <thead>
                        <tr>
                            <th rowspan="2" class="col-md-3">Station</th>
                            <th class="text-center">Data-gathering, technical and assessment skills</th>
                            <th class="text-center">Clinical management skills</th>
                            <th class="text-center">Interpersonal skills</th>
                            <th rowspan="2" class="text-center">Your total score</th>
                            <th rowspan="2" class="text-center">Pass score needed</th>
                            <th rowspan="2" class="text-center">Pass/Fail</th>
                        </tr>
                        <tr>
                            <th class="text-center">Your Score (maximum 4)</th>
                            <th class="text-center">Your Score (maximum 4)</th>
                            <th class="text-center">Your Score (maximum 4)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $total_assessment = $total_clinical = $total_interpersonal = $grand_total_score = $total_pass_mark = 0;
                        foreach ($results->details as $result) {
                            $total_score = $result->technical_skills + $result->clinical_skills + $result->interpersonal_skills;
                            $total_assessment += $result->technical_skills;
                            $total_clinical += $result->clinical_skills;
                            $total_interpersonal += $result->clinical_skills;
                            $grand_total_score += $total_score;
                            $total_pass_mark += $result->pass_mark;
                            $grade = ($result->pass_mark >= 5.5) ? 'Pass' : 'Fail';
                            ?>
                            <tr>
                                <td><?= $result->name ?></td>
                                <td class="text-center"><?= $result->technical_skills ?></td>
                                <td class="text-center"><?= $result->clinical_skills ?></td>
                                <td class="text-center"><?= $result->clinical_skills ?></td>
                                <td class="text-center"><?= $total_score; ?></td>
                                <td class="text-center"><?= number_format_fk($result->pass_mark, 2); ?></td>
                                <td class="text-center"><?= $grade; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Score</th>
                            <th class="text-center"><?= $total_assessment; ?></th>
                            <th class="text-center"><?= $total_clinical; ?></th>
                            <th class="text-center"><?= $total_interpersonal; ?></th>
                            <th class="text-center"><?= $grand_total_score; ?></th>
                            <th class="text-center"><?= number_format_fk($total_pass_mark, 2); ?></th>
                            <th class="text-center"><?= ($grand_total_score >= $total_pass_mark) ? 'Pass' : 'Fail'; ?></th>
                        </tr>
                    </tfoot>
                </table>
                <h4 class="h4 text-center"><u>Qualitative Feedback</u></h4>
                <table class="table table-striped table-bordered table-condensed result">
                    <thead>
                        <tr>
                            <th class="col-md-3">Station</th>
                            <th class="text-center">Consultation</th>
                            <th class="text-center">Diagnosis</th>
                            <th class="text-center">Examination</th>
                            <th class="text-center">Findings</th>
                            <th class="text-center">Issues</th>
                            <th class="text-center">Language</th>
                            <th class="text-center">Listening</th>
                            <th class="text-center">Management</th>
                            <th class="text-center">Rapport</th>
                            <th class="text-center">Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $cross = '<img src="' . site_url('assets/theme/images/cross.png') . '">';

                        foreach ($results->details as $result) {

                            $consultation = ($result->consultation) ? $cross : '';
                            $diagnosis = ($result->diagnosis) ? $cross : '';
                            $examination = ($result->examination) ? $cross : '';
                            $findings = ($result->findings) ? $cross : '';
                            $issues = ($result->issues) ? $cross : '';
                            $language = ($result->language) ? $cross : '';
                            $listening = ($result->listening) ? $cross : '';
                            $management = ($result->management) ? $cross : '';
                            $rapport = ($result->rapport) ? $cross : '';
                            $time = ($result->time) ? $cross : '';
                            ?>
                            <tr>
                                <td><?= $result->name ?></td>
                                <td class="text-center"><?= $consultation ?></td>
                                <td class="text-center"><?= $diagnosis ?></td>
                                <td class="text-center"><?= $examination ?></td>
                                <td class="text-center"><?= $findings; ?></td>
                                <td class="text-center"><?= $issues; ?></td>
                                <td class="text-center"><?= $language; ?></td>
                                <td class="text-center"><?= $listening; ?></td>
                                <td class="text-center"><?= $management; ?></td>
                                <td class="text-center"><?= $rapport; ?></td>
                                <td class="text-center"><?= $time; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>


<section class="content"> 

    <?php foreach ($scenarios as $scn) { ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $scn->name; ?></h3>
            </div>

            <div class="box-body">

                <div style="padding-left:25px;">
                    <fieldset>
                        <legend>Initial Approach</legend>
                        <table class="table table-condensed table-bordered text-center">
                            <tr>
                                <th>Name of the patient </th>
                                <th>Greet the patient </th>
                                <th>Introduces himself </th>
                                <th>State the role</th>
                                <th>Checks patient’s name preference </th>
                                <th>Starts station well</th>                                
                            </tr>
                            <tr>                                
                                <td><?php echo $scn->patient; ?></td>
                                <td><?php echo $scn->greet_the_patient; ?></td>
                                <td><?php echo $scn->introduces_himself; ?></td>
                                <td><?php echo $scn->state_the_role; ?></td>
                                <td><?php echo $scn->name_preference; ?></td>
                                <td><?php echo $scn->starts_station_well; ?></td>
                            </tr>                            
                        </table>
                    </fieldset>
                     
                             
                    
                
                    <fieldset>
                        <legend>Face</legend>
                        <?php echo $scn->face; ?> Smiley
                    </fieldset>

                    <fieldset>
                        <legend>Quantitative Feedback</legend>                   
                        <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <th>Data-gathering, technical and assessment skills</th>
                                <th>Clinical management Skills</th>
                                <th>Interpersonal Skills</th>
                                <th>Total Mark </th>
                            </tr>
                            <tr>                                                
                                <td><?= $scn->technical_skills; ?></td>
                                <td><?= $scn->clinical_skills; ?></td>
                                <td><?= $scn->interpersonal_skills; ?></td>
                                <td class="text-center"><span class="badge bg-yellow"><?= $scn->technical_skills + $scn->clinical_skills + $scn->interpersonal_skills; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                    </fieldset>
                
                
                    <fieldset>
                        <legend>Qualitative Feedback</legend> 
                        <table class="table table-bordered">
                            <tbody>
                                <tr>                                                
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
                                <tr>                                                
                                    <td class="text-center"><?= showCross($scn->consultation); ?></td>
                                    <td class="text-center"><?= showCross($scn->issues); ?></td>
                                    <td class="text-center"><?= showCross($scn->diagnosis); ?></td>
                                    <td class="text-center"><?= showCross($scn->examination); ?></td>
                                    <td class="text-center"><?= showCross($scn->findings); ?></td>
                                    <td class="text-center"><?= showCross($scn->management); ?></td>
                                    <td class="text-center"><?= showCross($scn->rapport); ?></td>
                                    <td class="text-center"><?= showCross($scn->listening); ?></td>
                                    <td class="text-center"><?= showCross($scn->language); ?></td>
                                    <td class="text-center"><?= showCross($scn->time); ?></td>
                                </tr>

                            </tbody>
                        </table>                                                
                    </fieldset>
                    
                    
                    <fieldset>
                        <legend>Examiner’s comments</legend>                    
                        <blockquote><?= $scn->examiner_comments; ?></blockquote>
                    </fieldset>
                </div>                       
            </div>
        </div>
    <?php } ?>
</section>