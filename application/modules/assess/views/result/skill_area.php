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
    <h1> Skill Area Result</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Skill Area</li>
    </ol>
</section>

<section class="content">       
    <div class="box">            

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed result">
                    <thead>
                        <tr>
                            <th rowspan="2" class="col-md-3">Station</th>
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
                        foreach ($results as $result) {
                            
                            $consultation = ($result->consultation) ? '<i class="fa fa-check"></i>' : '';
                            $diagnosis = ($result->diagnosis) ? '<i class="fa fa-check"></i>' : '';
                            $examination = ($result->examination) ? '<i class="fa fa-check"></i>' : '';
                            $findings = ($result->findings) ? '<i class="fa fa-check"></i>' : '';
                            $issues = ($result->issues) ? '<i class="fa fa-check"></i>' : '';
                            $language = ($result->language) ? '<i class="fa fa-check"></i>' : '';
                            $listening = ($result->listening) ? '<i class="fa fa-check"></i>' : '';
                            $management = ($result->management) ? '<i class="fa fa-check"></i>' : '';
                            $rapport = ($result->rapport) ? '<i class="fa fa-check"></i>' : '';
                            $time = ($result->time) ? '<i class="fa fa-check"></i>' : '';
                           
                        ?>
                        <tr>
                            <td><?= $result->name?></td>
                            <td class="text-center"><?= $consultation?></td>
                            <td class="text-center"><?= $diagnosis?></td>
                            <td class="text-center"><?= $examination?></td>
                            <td class="text-center"><?= $findings;?></td>
                            <td class="text-center"><?= $issues;?></td>
                            <td class="text-center"><?= $language;?></td>
                            <td class="text-center"><?= $listening;?></td>
                            <td class="text-center"><?= $management;?></td>
                            <td class="text-center"><?= $rapport;?></td>
                            <td class="text-center"><?= $time;?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>