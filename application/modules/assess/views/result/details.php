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
    .drinkcard-cc{
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
        opacity: 0.2;
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
    <div class="panel panel-default">
        <div class="panel-heading">Results</div>
        <div class="panel-body table-responsive">
            <div class="col-md-6">
                <table class="table table-condensed table-bordered table-striped">
                    <tr><td width="200"><?php echo $results->number_type; ?> Reference Number</td><td width="5">:</td><td><?php echo $results->gmc_number; ?></td></tr>
                    <tr><td>Name</td><td>:</td><td><?= "{$results->fname} {$results->mname} {$results->lname}"; ?></td></tr>                
                    <tr><td>Test</td><td>:</td><td><?= $results->exam_name; ?></td></tr>                
                    <tr><td>Date</td><td>:</td><td><?= globalDateTimeFormat($results->datetime); ?></td></tr>                
                    <tr><td>Pass/Fail</td><td>:</td><td><?= passOrFailColor($pass_or_fail); ?></td></tr>                
                </table>
            </div>
            <p><strong>Your Scores</strong><br/>
                <?php echo $passing_criteria_str; ?></p>
      <!--            <p><strong>Your Scores</strong><br/>
                      From August 2020, candidates must achieve or exceed both 
                      the pass mark and gain a pass in  minimum of 9 stations to pass
                      the <?= $results->exam_name; ?> assessment</p>
      
                  <p>You scored <?= $total_score; ?> and passed <?= $passed_station; ?> stations.</p> 
                  <p>A minimum scored of <?= $req_pass_mark; ?> was required to pass.</p>
      
                  <p>Your scores of each station:</p>-->
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Initial Approach</div>
        <div class="panel-body table-responsive"><table class="table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width='40'>S/L</th>
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
                    foreach ($results->details as $result) {
                        ?>
                        <tr id="result_details_id_<?= $result->id; ?>">
                            <td class="text-center"><?= sprintf('%02d', ++$sl); ?></td>
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
            </table></div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Face</div>
        <div class="panel-body table-responsive"><table class="table table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width='40'>S/L</th>
                        <th>Scenario</th>
                        <th class="text-center">Face</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 0;
                    foreach ($results->details as $result) {
                        ?>
                        <tr id="result_details_id_<?= $result->id; ?>">
                            <td class="text-center"><?= sprintf('%02d', ++$sl); ?></td>
                            <td><?= $result->name; ?></td>
                            <td class="text-center">
                        <?php echo getFace($result->face); ?>
                            </td>
                        </tr>
<?php } ?>
                </tbody>
            </table></div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Quantitative Feedback</div>
        <div class="panel-body table-responsive"><table class="table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width='40'>S/L</th>
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
                        <tr id="result_details_id_<?= $result->id; ?>">
                            <td class="text-center"><?= sprintf('%02d', ++$sl); ?></td>
                            <td><?= $result->name; ?></td>
                            <td class="text-center"><?= $result->technical_skills; ?></td>
                            <td class="text-center"><?= $result->clinical_skills; ?></td>
                            <td class="text-center"><?= $result->interpersonal_skills; ?></td>
                            <td class="text-center"><?= $total_score; ?></td>
                            <td class="text-center"><?= number_format_fk($result->pass_mark, 2); ?></td>
                            <td class="text-center"><?= passOrFailColor($grade); ?></td>
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
                        <th class="text-center"><?= passOrFailColor($pass_or_fail); ?></th>
                    </tr>
                </tfoot>
            </table></div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Qualitative Feedback</div>
        <div class="panel-body table-responsive"><table class="table table-condensed table-striped table-bordered">
                <thead>
                    <tr>    
                        <th class="text-center" width='40'>S/L</th>
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
                        <tr id="result_details_id_<?= $result->id; ?>">
                            <td class="text-center"><?= sprintf('%02d', ++$sl); ?></td>
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
            </table>  </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Examiner’s comments</div>
        <div class="panel-body table-responsive"><table class="table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width='40'>S/L</th>
                        <th width="350">Scenario</th>
                        <th class="text-center">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 0;
                    foreach ($results->details as $result) {
                        ?>
                        <tr id="result_details_id_<?= $result->id; ?>">
                            <td class="text-center"><?= sprintf('%02d', ++$sl); ?></td>
                            <td><?= $result->name; ?></td>
                            <td class="text-center"><?php echo $result->examiner_comments; ?></td>
                        </tr>
<?php } ?>
                </tbody>
            </table></div>
    </div>
    <div class="box-footer text-center with-border">
        <a href="admin/assess/result/download/<?= "{$s_id}/{$es_id}"; ?>" class="btn btn-lg btn-info">
            <i class="fa fa-fw fa-download"></i> 
            Download
        </a>
    </div>
</section>

