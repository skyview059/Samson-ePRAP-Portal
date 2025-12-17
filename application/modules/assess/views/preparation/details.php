<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('scenario', 'css'); ?>
<section class="content-header">
    <h1>Exam Preparation  <small>Read</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'exam_preparation') ?>">Exam Preparation</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">

        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="box-title">Details View</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?php echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=candidate') ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Candidate Instructions</a>
                    <a href="<?php echo site_url(Backend_URL . 'scenario/print/' . $id . '?is=full') ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Full Page</a>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered">   
                <tr>
                    <td width="170">Scenario No</td>
                    <td width="5">:</td>
                    <td><?php echo $reference_number; ?></td>
                </tr>
                <tr>
                    <td>Title/Name</td>
                    <td>:</td>
                    <td><?php echo $name; ?></td>
                </tr>
                <tr>
                    <td>Candidate Instructions</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $candidate_instructions; ?>
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td>Patient Information</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $patient_information; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td>Examiner Information</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $examiner_information; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td>Set up</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $setup; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td>Exam Findings</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $exam_findings; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td>Approach</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $approach; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td>Explanation</td>
                    <td>:</td>
                    <td class="doc2html">
                        <div class="bg">
                            <?php echo $explanation; ?>
                        </div>                        
                    </td>                    
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><a href="<?php echo site_url(Backend_URL . 'assess/preparation') ?>" class="btn btn-default">
                            <i class="fa fa-long-arrow-left"></i>
                            Back
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</section>