<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Student Database</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">student</li>
    </ol>
</section>
<?php load_module_asset('student', 'css'); ?>
<?php load_module_asset('users', 'css'); ?>

<section class="content">
    <?php echo studentBlockedTab( $status );?>
    <div class="box no-border">
        <div class="box-header text-center">
            <h3 class="no-margin">Blocked/Cancelled Student List</h3>
        </div>
        <div class="box-body">

            <?php if (!$students) {
                echo '<p class="ajax_notice">No Student Found</p>';
            } ?>           
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th width="80">Photo</th>
                            <th>Full Name, Email & ID</th> 
                            <th>Booked on</th> 
                            <th class="text-center" width="80">Status</th>
                            <th width="135">Phone</th>                            
                            <th width="100">Mock Exam</th>
                            <th width="155">Mock Date-Time</th>                            
                            <th class="text-center" width="100">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($students as $std) {
                            $link = site_url( Backend_URL . '/student/read/'. $std->stu_id );
                            ?>
                            <tr id="row_<?= $std->enroll_id; ?>">
                                <td><?= ++$start; ?></td>
                                <td class="text-center">
                                    <?php echo getPhoto_v3($std->photo, $std->gender, $std->full_name, 60, 60); ?>                                    
                                </td>
                                <td><a href="<?= $link; ?>">
                                        <?php echo $std->full_name; ?><br/>                                    
                                        <?php echo $std->email; ?><br/>
                                        <?php echo "{$std->number_type}-{$std->gmc_number}"; ?>
                                    </a>
                                </td>                                                                        
                                <td>
                                    <?php echo globalDateTimeFormat($std->enrolled_at); ?><br/>
                                    <em class="text-red"><?php echo ($std->remarks); ?></em>
                                    
                                    <p class='small-padding'>
                                        <span onclick="return student_enroll_action(<?= $std->enroll_id; ?>, 'Enrolled');" class='btn btn-success btn-xs'>
                                              <i class='fa fa-check-square-o'></i> 
                                            Approve</span>                                            
                                        <span onclick="return student_enroll_action(<?= $std->enroll_id; ?>, 'Delete');" class='btn btn-warning btn-xs'>
                                              <i class='fa fa-trash'></i> 
                                            Delete/Clean
                                        </span>                                        
                                    </p>
                                </td>
                                <td class="text-center"><?php echo $std->status; ?></td>    
                                <td>                                    
                                    &nbsp;&nbsp;<i class="fa fa-mobile-phone"></i> <?php echo "+{$std->phone_code}{$std->phone}"; ?><br/>
                                    <i class="fa fa-whatsapp" ></i> <?php echo "+{$std->whatsapp_code}{$std->whatsapp}"; ?>                                
                                </td>                                
                                <td><?= $std->exam_name .'<br/>'. $std->label; ?></td>
                                <td>
                                    <?= $std->centre_name; ?><br/>
                                    <?= globalDateFormat($std->datetime); ?><br/>
                                    <?= globalTimeOnly($std->datetime) . ' ['. dayLeftOfExam($std->datetime) . "]"; ?>
                                </td>                                
                                <td class="text-center">
                                    <?php                                    
                                        echo anchor(
                                            site_url(Backend_URL . 'student/login/' . $std->stu_id), 
                                            '<i class="fa fa-fw fa-gear"></i> Login', 
                                            'class="btn btn-xs btn-info" target="_blank"'
                                        );
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>                
            

            <div class="box-footer text-center">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</section>
<?php load_module_asset('dashboard','js'); ?>