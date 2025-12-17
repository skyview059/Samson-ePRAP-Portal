<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>View Result</title>
        <link rel="icon" href="assets/theme/images/favicon.jpg">
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
            
        </style>
    </head>
    <body>
        <div id="container">
            <div id="body">
                <div class="box-body">
                    <div class="table-responsive">
                        <address class="text-center" style="font-size: 16px; font-weight: 600; text-align: center;">
                            Name: <?php echo "{$results->fname} {$results->mname} {$results->lname}"; ?><br/>
                            <?php echo $results->number_type;?> Number : <?php echo $results->gmc_number; ?><br>
                            Exam: <?php echo $results->exam_name; ?><br>
                            Date & Time: <?php echo globalDateTimeFormat($results->datetime); ?>
                        </address>
                        <h4 class="text-center"><u>Domain</u></h4>
                        <table class="table table-bordered result">
                            <thead>
                                <tr>
                                    <th rowspan="2">Station</th>
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
                        <h4 class="h4 text-center"><u>Skill Area</u></h4>
                        <table class="table table-bordered result">
                            <thead>
                                <tr>
                                    <th>Station</th>
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
                                $checked = '<svg version="1.1" width="12" height="12" viewBox="0 0 12 12"><path d="M10.125 1.313l-5.625 5.625-2.625-2.625-1.875 1.875 4.5 4.5 7.5-7.5z"></path></svg>';
                                foreach ($results->details as $result) {

                                    $consultation = ($result->consultation) ? $checked : '';
                                    $diagnosis = ($result->diagnosis) ? $checked : '';
                                    $examination = ($result->examination) ? $checked : '';
                                    $findings = ($result->findings) ? $checked : '';
                                    $issues = ($result->issues) ? $checked : '';
                                    $language = ($result->language) ? $checked : '';
                                    $listening = ($result->listening) ? $checked : '';
                                    $management = ($result->management) ? $checked : '';
                                    $rapport = ($result->rapport) ? $checked : '';
                                    $time = ($result->time) ? $checked : '';
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
        </div>
    </body>
</html>