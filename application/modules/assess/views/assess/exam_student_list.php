<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    table.table thead tr th,
    table.table tbody tr td{
        vertical-align: middle;
    }
</style>
<section class="content-header">
    <h1> Exam Student List </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Student List</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
    <div class="panel-heading"><?php            
            echo ($admin) 
                ? 'Your are accessing as Admin'
                : 'Exam Students';            
            ?></div>
        <div class="panel-body">
            <div class="col-md-5 col-md-offset-3 text-center">
                <form action="<?php echo site_url( Backend_URL . 'assess/student_list'); ?>" class="form-inline" method="GET">
                    <div class="input-group">
                        <span class="input-group-addon">Mock Exam</span>
                        <select name="exam_schedule_id" class="form-control select2">
                            <?php echo getTodayExamScheduleDropDownByTeacher($exam_schedule_id, $admin ); ?>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
            
            <?php if ($exam_schedule_id) { ?>

        <div class="row">
            <div class="col-md-12">
                <br>
                <div class="box">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                            
                            <h3 class="box-title">Student List</h3>
                        </div>
                    </div>

                    <div class="box-body">
                       <?php if ($students) { ?> 
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th width="40">Sl</th>
                                            <th width="100" class="text-center">Photo</th>
                                            <th>Name</th>
                                            <th>Number Type</th>                                            
                                            <th>Number</th>                                            
                                            <th>Exam Name</th>
                                            <th>Centre Name</th>
                                            <?php if($admin){ echo '<th class="text-center text-red">Admin Tool</th>'; } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $s){ 
                                            
                                            $link = "admin/assess/search_student?exam_schedule_id={$exam_schedule_id}&number_type={$s->number_type}&gmc={$s->gmc_number}";
                                            $modify = "admin/assess/review_assement?es_id={$exam_schedule_id}&sid={$s->id}";
                                            ?>
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td class="text-center"><?php echo getPhoto_v2($s->photo, $s->full_name); ?></td>                            
                                            <td><?php echo $s->full_name; ?></td>
                                            <td><?php echo $s->number_type; ?></td>
                                            <td><?php echo $s->gmc_number; ?></td>
                                            <td><?php echo $s->exam_name; ?></td>
                                            <td><?php echo $s->centre_name; ?></td>
                                            
                                            <td class="text-center"> 
                                                
                                                <?php if($s->date == $today ){  ?>
                                                <a href="<?= $link; ?>" class="btn btn-primary btn-xs" target="_blank">
                                                    <i class="fa fa-play"></i>
                                                    Start Assess
                                                    <i class="fa fa-external-link"></i>
                                                </a>
                                                <?php } else { ?>
                                                <span class="btn btn-danger btn-xs disabled">
                                                    <i class="fa fa-ban"></i>
                                                    Start Assess                                                    
                                                </span>
                                                <?php } ?>
                                                
                                                <?php if($admin){ ?>
                                                <a href="<?= $modify; ?>" class="btn btn-warning btn-xs" target="_blank">
                                                    <i class="fa fa-random"></i>
                                                    Review Assessment 
                                                    <i class="fa fa-external-link"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">                
                                <div class="col-md-6">
                                    <span class="btn btn-primary">Total Results: <?php echo count($students); ?></span>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-info">
                                        <h4>Not Found!</h4>
                                        <p>No Student Found for This Exam.</p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
        </div>
  </div>


    
</section>