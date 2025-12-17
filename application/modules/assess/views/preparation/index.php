<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Exam Preparation  <small>Control panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Exam Preparation</li>
    </ol>
</section>
<style type="text/css">
    tr.hide-table-padding td {padding: 0;}
    .expand-button {position: relative;}
    .accordion-toggle .expand-button:after{
        position: absolute;
        left:.75rem;
        top: 50%;
        transform: translate(0, -50%);
        content: '[-]';
        text-align: center;
    }
    .accordion-toggle.collapsed .expand-button:after{
        content: '[+]';
        text-align: center;
    }
</style>

<section class="content">
    <div class="panel panel-default">
      <div class="panel-heading">Exam Preparation</div>
      <div class="panel-body">
          <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th scope="col" width="40">S/L</th>
                            <th scope="col">Exam Name</th>
                            <th scope="col">Center</th>
                            <th scope="col">Center Address</th>
                            <th scope="col">Mock Exam Date & Time</th>
                            <th scope="col" class="text-center">Total Questions</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $row = 1;
                        if($exams){
                            foreach ($exams as $exam) { ?>
                            <tr>
                                
                                <td><?php echo $row; ?></td>
                                <td><?php echo $exam->exam_name; ?></td>
                                <td><?php echo $exam->center_name; ?></td>
                                <td><?php echo $exam->center_address; ?></td>
                                <td><?php echo globalDateTimeFormat($exam->datetime); ?></td>
                                <td class="text-center"><?php echo $exam->total_questions; ?></td>                                
                            </tr>
                            <tr class="hide-table-padding">
                                <td></td>
                                <td colspan="5" class="no-padding">                          
                                        <table class="table table-striped table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                    <th width="100">Scenario No</th>
                                                    <th>Name</th>
                                                    <th width="280" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($exam->scenarios as $scenario) { ?>
                                                <tr>
                                                    <td><?php echo $scenario->reference_number;?></td>
                                                    <td><?php echo $scenario->name;?></td>
                                                    <td  class="text-center">
                                                        <?php
                                                        echo anchor(
                                                                site_url(Backend_URL . 'assess/preparation/details/' . $scenario->id),
                                                                '<i class="fa fa-fw fa-bars"></i> Full Scenario', 
                                                                'class="btn btn-xs btn-primary"'
                                                        );
                                                        
                                                        
                                                        ?>
                                                        <?php /* site_url(Backend_URL . 'scenario/print/' . $scenario->id . '?is=instructions') ?>" */ ?>
                                                        <a href="<?php echo site_url(Backend_URL . 'scenario/print/' . $scenario->id . '?is=candidate') ?>" target="_blank" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-print"></i> 
                                                            Candidate Instructions
                                                        </a>                                                        
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>                                    
                                </td>
                            </tr>
                            <?php 
                            $row++;
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No Exam Available!</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
          <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Exam: <?php echo count($exams) ?></span>
                </div>
            </div>
      </div>
    </div>

</section>