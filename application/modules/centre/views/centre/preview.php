<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users','css');?>
<section class="content-header">
    <h1><?php echo getExamCentreName($id); ?> Exam List <small>/ Exam List</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>centre">Centre</a></li>
        <li class="active">Exam Schedule</li>
    </ol>
</section>

<section class="content personaldevelopment">
    
    
    <?php echo centreTabs( $id, 'preview'); ?>
    <br>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Centre Exam Schedule</h3>
        </div>

        <div class="box-body">

            <?php if(!$exams){ ?>
                <p class="ajax_notice"> No Exam Schedule Found</p>
            <?php } else { ?>
            
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th width="200">Exam</th>
                        <th>Centre</th>
                        <th width="75" class="text-center">Scenario</th>
                        <th width="75" class="text-center">Student</th>
                        <th width="140">Exam Date & Time</th> 
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($exams as $exam) { ?>
                        <tr>
                            <td><?php echo ++$start; ?></td>
                            <td><?php echo $exam->exam_name; ?></td>
                            <td><?php echo $exam->centre; ?></td>
                            <td class="text-center">
                                <a href="admin/exam/scenario/<?php echo $exam->id; ?>" title="See All Scenario">
                                    <b><?php echo countExamScenario($exam->id); ?>&nbsp;</b>
                                    <i class="fa fa-external-link-square"></i>
                                </a></td>
                            <td class="text-center">
                                <a href="admin/exam/student/<?php echo $exam->id; ?>" title="See All Scenario">
                                    <b><?php echo countExamStudent($exam->id); ?>&nbsp;</b>
                                    <i class="fa fa-external-link-square"></i>
                                </a>                                
                            </td>
                            <td><?php echo globalDateTimeFormat($exam->datetime); ?></td>                           
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <?php } ?>
        </div>
    </div>
</section>