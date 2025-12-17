<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('scenario', 'css'); ?>

<style>
    #accordion {
        margin: auto;
        max-width: 100%;
        padding-top: 15px;
    }

    #accordion.panel-group .panel {
        margin-bottom: 15px;
    }

    #accordion .panel-heading a {
        display: block;
        position: relative;
        font-weight: bold;
        color: black;

        &::after {
            content: "";
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 5px;
            position: absolute;
            right: 0;
            top: 0;
            transform: rotate(45deg);
        }

        &[aria-expanded="true"]::after {
            transform: rotate(-135deg);
            top: 5px;
        }
    }
</style>
<section class="content-header">
    <h1><?php echo getExamName($exam_id); ?> Scenario <small>Details</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'scenario') ?>">Scenario</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo scenarioTabs($id, 'read'); ?>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">Details View <span class="col-md-6 pull-right text-right">
          <a style="background: no-repeat; border: 0; font-weight: 600; text-decoration: underline;"
             href="<?php echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=candidate') ?>" target="_blank"
             class="btn btn-primary"><i class="fa fa-print"></i> Candidate Instructions</a>
          <a style="background: no-repeat; border: 0; font-weight: 600; text-decoration: underline;"
             href="<?php echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=patient') ?>" target="_blank"
             class="btn btn-primary"><i class="fa fa-print"></i> Patient Information</a>
          <a style="background: no-repeat; border: 0; font-weight: 600; text-decoration: underline;"
             href="<?php echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=full') ?>" target="_blank"
             class="btn btn-primary"><i class="fa fa-print"></i> Examiner Information</a>
                <!--    <a style="background: no-repeat; border: 0; font-weight: 600; text-decoration: underline;"-->
                <!--       href="--><?php //echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=full') ?><!--"-->
                <!--       target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Full Page</a>-->
                </span></div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <tr>
                    <td width="170">Scenario No</td>
                    <td><?php echo $reference_number; ?></td>
                </tr>
                <tr>
                    <td>Presentation</td>
                    <td><?php echo $presentation; ?></td>
                </tr>
                <tr>
                    <td>Diagnosis</td>
                    <td><?php echo $name; ?></td>
                </tr>
            </table>


            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseItem1">Candidate
                                Instructions</a>
                        </h3>
                    </div>
                    <div id="collapseItem1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $candidate_instructions; ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseItem2">Patient
                                Information</a>
                        </h3>
                    </div>
                    <div id="collapseItem2" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $patient_information; ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#examiner_information">
                                Examiner's Prompt
                            </a>
                        </h3>
                    </div>
                    <div id="examiner_information" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $examiner_information; ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#setup">
                                Set up
                            </a>
                        </h3>
                    </div>
                    <div id="setup" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $setup; ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#exam_findings">
                                Examination Findings
                            </a>
                        </h3>
                    </div>
                    <div id="exam_findings" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $exam_findings; ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#approach">
                                Approach
                            </a>
                        </h3>
                    </div>
                    <div id="approach" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $approach; ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#explanation">
                                Explanation
                            </a>
                        </h3>
                    </div>
                    <div id="explanation" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php echo $explanation; ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-6">
                    Created on: <?php echo globalDateTimeFormat($created_at); ?><br>
                    Updated on: <?php echo globalDateTimeFormat($updated_at); ?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?php echo site_url(Backend_URL . "scenario?id={$exam_id}") ?>"
                       class="btn btn-default">
                        <i class="fa fa-long-arrow-left"></i>
                        Back
                    </a>
                    <a href="<?php echo site_url(Backend_URL . 'scenario/update/' . $id) ?>"
                       class="btn btn-primary">
                        <i class="fa fa-edit"></i>
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>