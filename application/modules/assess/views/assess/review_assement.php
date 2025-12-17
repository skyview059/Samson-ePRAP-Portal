<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Review Exam as Admin </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Student List</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">List of Scenarios</div>
  <div class="panel-body">
      <h3 style="margin-top: 0;" class="box-title"><?php echo Tools::getStudentNameByID($sid);?></h3>
      <div class="table-responsive">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Ref. No</th>
                            <th>Name</th>
                            <th width="100">Status</th>                            
                            <th width="200">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($scenarios as $s){
                            
                            $scen = Tools::getStudentSingleScenarioExamStatus($es_id, $sid, $s->id);                                                        
                            $modify = "admin/assess/review/{$scen->id}";
                            
                            ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>                                
                                <td><?php echo $s->reference_number; ?></td>
                                <td><?php echo $s->name; ?></td>
                                <td><?php echo $scen->status; ?></td>
                                <td>
                                    <?php if($scen->id){ ?>
                                    <a href="<?= $modify; ?>" class="btn btn-xs btn-warning" target="_blank">
                                        Review as Admin
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> 
  </div>
</div>

</section>