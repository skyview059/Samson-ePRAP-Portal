<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> <?php echo getExamName($id); ?> Online Mock Dates
        <small>Control panel</small> 
        <?php
            echo anchor(
                site_url(Backend_URL . 'online_mock/create?id='. $id ),
                '<i class="fa fa-plus"></i> Add New Online Mock',
                'class="btn btn-primary"'
            ); 
        ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Exam</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo onlineMockListTab( $tab, $coming, $past, $canceled ); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Online Mock List</h3>
            <div class="col-md-3 col-md-offset-9 text-right hidden">
                <form action="<?php echo site_url(Backend_URL . 'online_mock'); ?>" class="form-inline" method="get">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'exam'); ?>"
                                   class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">

            <?php if(!$id){ echo '<p class="ajax_error"> <i class="fa fa-arrow-left"></i> &nbsp; Please Click Exam Name for Left sidebar.</p>'; } ?>
            
            <?php if(!$online_mocks){ ?>
                <p class="ajax_notice"> No Online Mock Exam Dates Found</p>
            <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>                        
                        <th>Label</th>
                        <th width="40" class="text-center">Seat</th>
                        <th width="60" class="text-center">Req2PassS</th>
                        <th>Centre</th>
                        <th class="text-center">Scenario</th>
                        <th class="text-center">Student</th>
                        <th>Exam Date & Time</th>
                        <th class="text-center">Days Left</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Preview</th>
                        <th>Status & Date </th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>                       
                    <?php foreach ($online_mocks as $exam) { ?>
                        <tr class="mc_<?= strtolower_fk($exam->exam_status); ?>">
                            <td><?php echo sprintf('%02d', ++$start); ?></td>                            
                            <td><?php echo $exam->category_name; ?> (<b><?php echo $exam->label; ?></b>)
                                <br/><em><?php echo multiDateFormat($exam->gmc_exam_dates); ?></em>
                            </td>
                            <td class="text-center"><?php echo $exam->student_limit; ?></td>
                            <td class="text-center"><?php echo $exam->pass_station; ?></td>
                            <td><?php echo $exam->centre; ?></td>
                            <td class="text-center">
                                <a href="admin/online_mock/scenario/<?php echo $exam->id; ?>" title="See All Scenario">
                                    <b><?php echo countExamScenario($exam->id); ?>&nbsp;</b>
                                    <i class="fa fa-external-link-square"></i>
                                </a></td>
                            <td class="text-center">
                                <a href="admin/online_mock/student/<?php echo $exam->id; ?>" title="See All Scenario">
                                    <b><?php echo countExamStudent($exam->id); ?>&nbsp;</b>
                                    <i class="fa fa-external-link-square"></i>
                                </a>                                                                
                            </td>
                            
                            <td><?php echo globalDateTimeFormat($exam->datetime); ?></td>                            
                            <td class="text-center"><?php echo dayLeftOfExam($exam->datetime); ?></td>                            
                            <td class="text-center">
                                <?php 
                                //$exam_time = strtotime($exam->datetime, strtotime('+3 hours'));
                                $exam_time          = strtotime($exam->datetime);
                                $current_datetime   = time();                                                                
                                if($current_datetime > $exam_time){
                                    if($exam->status == 'Published'){
                                        echo anchor(site_url(Backend_URL . 'online_mock/publish/' . $exam->id),
                                                '<i class="fa fa-fw fa-close"></i> Withdrawal', 
                                                'class="btn btn-xs btn-success confirmation" title="Withdrawal Result" onclick="return confirm(\'Confirm Withdrawal Result\')"');
                                    }else{
                                        echo anchor(site_url(Backend_URL . 'online_mock/publish/' . $exam->id),
                                                '<i class="fa fa-check-square-o"></i> Release ', 
                                                'class="btn btn-xs btn-warning confirmation" title="Relese Result" onclick="return confirm(\'Confrim Relese Result\')"');
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>                                  
                            </td>
                            <td class="text-center">
                                <a class="btn btn-default btn-xs" href="admin/assess/result?id=<?php echo $exam->id; ?>" target="_blank" title="Result Summery">
                                    <i class="fa fa-question-circle"></i>
                                    <i class="fa fa-external-link-square"></i>
                                </a>
                            </td>
                            <td><?php 
                            
                            if($exam->status == 'Published'){  
                                echo $exam->status . ' | ';
                                echo globalDateFormat($exam->published_at);                                
                            }
                            
                            if($exam->exam_status == 'Canceled'){  
                                echo 'Canceled | ';
                                echo globalDateFormat($exam->cancel_at);                                 
                            }
                            ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('mock/exam-room/' . $exam->id.'/practice'); ?>"
                                   target="_blank"
                                   class="btn btn-xs btn-success" <?= ($exam->datetime < date('Y-m-d H:i:s')) ? 'disabled' : '' ?>>
                                    <i class="fa fa-play"></i> Enter exam room
                                </a>
                                <?php
                                echo anchor(site_url(Backend_URL . 'online_mock/update/' . $exam->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                echo anchor(site_url(Backend_URL . 'online_mock/scenario/' . $exam->id), '<i class="fa fa-exchange"></i> Scenario', 'class="btn btn-xs btn-warning"');
                                echo anchor(site_url(Backend_URL . 'online_mock/delete/' . $exam->id), '<i class="fa fa-fw fa-times"></i>', 'class="btn btn-xs btn-danger"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span>Total Online Mock: <?php echo $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
