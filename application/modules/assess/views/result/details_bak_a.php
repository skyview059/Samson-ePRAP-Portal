<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .result thead tr th,
    .result tfoot tr th,
    .result td 
    {
        vertical-align: middle;
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
                <h4 class="h4 text-center"><u>Qualitative Feedback</u></h4>
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
                            $total_pass_mark +=$result->pass_mark;
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
                            <th class="text-center"><?= ($grand_total_score>=$total_pass_mark) ? 'Pass' : 'Fail'; ?></th>
                        </tr>
                    </tfoot>
                </table>
                <h4 class="h4 text-center"><u>Quantitative Feedback</u></h4>
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
<section>
    
    
    
    <div class="box-body">
            
        
        <?php    foreach ($scenarios as $scn ){ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-group panel-group" id="accordion">                            
                            <div class="panel box box-primary">
                                <div class="box-header with-border panel-heading" role="tab" id="headingOne">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="more-less glyphicon glyphicon-minus"></i>
                                            Initial Approach
					</a>
                                    </h4>
                                </div>
                                <div id="collapseOne"  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-8 col-md-offset-3">
                                                <div class="form-group">
                                                    <label for="patient_name" class="col-sm-3 control-label">Name of the patient :</label>
                                                    <div class="col-sm-9"><?php echo $scn->patient; ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="greet_the_patient" class="col-sm-3 control-label">Greet the patient :</label>
                                                    <div class="col-sm-9"><?php echo $scn->greet_the_patient; ?></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="introduces_himself" class="col-sm-3 control-label">Introduces himself :</label>
                                                    <div class="col-sm-9"><?php echo $scn->introduces_himself; ?></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="state_the_role" class="col-sm-3 control-label">State the role :</label>
                                                    <div class="col-sm-9"><?php echo $scn->state_the_role; ?></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="name_preference" class="col-sm-3 control-label">Checks patient’s name preference :</label>
                                                    <div class="col-sm-9"><?php echo $scn->name_preference; ?></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="starts_station_well" class="col-sm-3 control-label">Starts station well :</label>
                                                    <div class="col-sm-9"><?php echo $scn->starts_station_well; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Face
					</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        <div class="row">
                                            
                                            <div class="text-center">
                                                <div class="cc-selector">                                                    
                                                    <?php echo $scn->face; ?>
                                                    Smiley                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Quantitative Feedback
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th> Station </th>
                                                            <th class="col-md-4">Data-gathering, technical and assessment skills</th>
                                                            <th class="col-md-2">Clinical management Skills</th>
                                                            <th class="col-md-2">Interpersonal Skills</th>
                                                            <th class="col-md-1">Total Mark </th>
                                                        </tr>
                                                        <tr>
                                                            <td><?= $scn->name; ?></td>
                                                            <td><?= $scn->technical_skills; ?></td>
                                                            <td><?= $scn->clinical_skills; ?></td>
                                                            <td><?= $scn->interpersonal_skills; ?></td>
                                                            <td class="text-center"><span class="badge bg-yellow"><?= $scn->technical_skills+$scn->clinical_skills+$scn->interpersonal_skills; ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Qualitative Feedback
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th class="col-md-2"> Station </th>
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
                                                            <td><?= $scn->name; ?></td>
                                                            <td class="text-center"><?= $scn->consultation; ?></td>
                                                            <td class="text-center"><?= $scn->issues; ?></td>
                                                            <td class="text-center"><?= $scn->diagnosis; ?></td>
                                                            <td class="text-center"><?= $scn->examination; ?></td>
                                                            <td class="text-center"><?= $scn->findings; ?></td>
                                                            <td class="text-center"><?= $scn->management; ?></td>
                                                            <td class="text-center"><?= $scn->rapport; ?></td>
                                                            <td class="text-center"><?= $scn->listening; ?></td>
                                                            <td class="text-center"><?= $scn->language; ?></td>
                                                            <td class="text-center"><?= $scn->time; ?></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Examiner's Overall Judgement
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="overall_judgment" class="col-sm-4 control-label">Overall Judgment: </label>
                                                    <div class="col-sm-8">
                                                        <?= $scn->overall_judgment; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Examiner’s comments
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <?= $scn->examiner_comments; ?>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>           
        <?php } ?>
    </div>
</section>