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

    .drinkcard-cc {
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
        opacity: 0.2;
    }
</style>

<div class="container">
    <div class="row" style="padding: 30px 0">
        <div class="col-md-6">
            <h2 style="margin: 0">Practice Result</h2>
        </div>
        <div class="col-md-6">
            <a href="<?= site_url('scenario-practice/exam/practice/' . $es_id); ?>"
               class="btn btn-primary pull-right"><i class="fa fa-long-arrow-left"></i> Back to Scenarios</a>
        </div>
    </div>

    <section class="content">
        <div class="panel panel-info">
            <div class="panel-heading">Results</div>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                    <table class="table table-condensed table-bordered table-striped">
                        <tr>
                            <td width="200">Name</td>
                            <td width="5">:</td>
                            <td><?php echo $results->student_name; ?></td>
                        </tr>
                        <tr>
                            <td>Exam</td>
                            <td>:</td>
                            <td><?= $results->exam_name; ?></td>
                        </tr>
                        <tr>
                            <td>Scenario</td>
                            <td>:</td>
                            <td><?= $results->scenario_name; ?></td>
                        </tr>
                        <tr>
                            <td>Pass/Fail</td>
                            <td>:</td>
                            <td><?= passOrFailColor($pass_or_fail); ?></td>
                        </tr>
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
        <div class="panel panel-info">
            <div class="panel-heading">Initial Approach</div>
            <div class="panel-body table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">Identifies Patient</th>
                        <th class="text-center">Greet the patient</th>
                        <th class="text-center">Introduces himself</th>
                        <th class="text-center">State the role</th>
                        <th class="text-center">Checks patient’s name preference</th>
                        <th class="text-center">Starts station well</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center"><?php echo $results->patient; ?></td>
                        <td class="text-center"><?php echo $results->greet_the_patient; ?></td>
                        <td class="text-center"><?php echo $results->introduces_himself; ?></td>
                        <td class="text-center"><?php echo $results->state_the_role; ?></td>
                        <td class="text-center"><?php echo $results->name_preference; ?></td>
                        <td class="text-center"><?php echo $results->starts_station_well; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Face</div>
            <div class="panel-body table-responsive">
                <table class="table table-striped table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th>Scenario</th>
                        <th class="text-center">Face</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= $results->scenario_name; ?></td>
                        <td class="text-center">
                            <?php echo getFace($results->face); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Quantitative Feedback</div>
            <div class="panel-body table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Data-gathering, technical and assessment skills</th>
                        <th class="text-center">Clinical management Skills</th>
                        <th class="text-center">Interpersonal Skills</th>
                        <th class="text-center">Total Mark</th>
                        <th class="text-center">Pass Score</th>
                        <th class="text-center">Result</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_assessment = $total_clinical = $total_interpersonal = $grand_total_score = $total_pass_mark = 0;

                    $total_score         = $results->technical_skills + $results->clinical_skills + $results->interpersonal_skills;
                    $total_assessment    += $results->technical_skills;
                    $total_clinical      += $results->clinical_skills;
                    $total_interpersonal += $results->interpersonal_skills;
                    $grand_total_score   += $total_score;
                    $total_pass_mark     += $results->pass_mark;
                    $grade               = ($total_score >= $results->pass_mark) ? 'Pass' : 'Fail';
                    ?>
                    <tr>
                        <td></td>
                        <td class="text-center"><?= $results->technical_skills; ?></td>
                        <td class="text-center"><?= $results->clinical_skills; ?></td>
                        <td class="text-center"><?= $results->interpersonal_skills; ?></td>
                        <td class="text-center"><?= $total_score; ?></td>
                        <td class="text-center"><?= number_format_fk($results->pass_mark, 2); ?></td>
                        <td class="text-center"><?= passOrFailColor($grade); ?></td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-right">Total Score</th>
                        <th class="text-center"><?= $total_assessment; ?></th>
                        <th class="text-center"><?= $total_clinical; ?></th>
                        <th class="text-center"><?= $total_interpersonal; ?></th>
                        <th class="text-center"><?= $grand_total_score; ?></th>
                        <th class="text-center"><?= number_format_fk($total_pass_mark, 2); ?></th>
                        <th class="text-center"><?= passOrFailColor($pass_or_fail); ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Qualitative Feedback</div>
            <div class="panel-body table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
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
                    </thead>
                    <tbody>
                    <?php
                    $cross = '<img src="' . site_url('assets/theme/images/cross.png') . '">';
                    ?>
                    <tr>
                        <td class="text-center"><?= showCross($results->consultation); ?></td>
                        <td class="text-center"><?= showCross($results->issues); ?></td>
                        <td class="text-center"><?= showCross($results->diagnosis); ?></td>
                        <td class="text-center"><?= showCross($results->examination); ?></td>
                        <td class="text-center"><?= showCross($results->findings); ?></td>
                        <td class="text-center"><?= showCross($results->management); ?></td>
                        <td class="text-center"><?= showCross($results->rapport); ?></td>
                        <td class="text-center"><?= showCross($results->listening); ?></td>
                        <td class="text-center"><?= showCross($results->language); ?></td>
                        <td class="text-center"><?= showCross($results->time); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Examiner’s comments</div>
            <div class="panel-body table-responsive">
                <?php echo $results->examiner_comments; ?>
            </div>
        </div>
    </section>
</div>