<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Exam <small>Delete</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo examTabs($id, 'cancel'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Cancel</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="150">Exam Name</td>
                    <td width="5">:</td>
                    <td><?php echo $course_name; ?></td>
                </tr>
                <tr>
                    <td>Exam Date & Time</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($datetime); ?></td>
                </tr>
                <tr>
                    <td>GMC Exam Dates</td>
                    <td>:</td>
                    <td><?php echo multiDateFormat($gmc_exam_dates); ?></td>
                </tr>
                <tr>
                    <td>Centre</td>
                    <td>:</td>
                    <td><?php echo $centre_name; ?></td>
                </tr>
                <tr>
                    <td>Centre Address</td>
                    <td>:</td>
                    <td><?php echo $centre_address; ?></td>
                </tr>
                
            </table>
        </div>
        <div class="box-footer">
            
            <?php if($datetime > date("Y-m-d H:i:s") && $exam_status=='Canceled'){?>
                <p class="btn btn-danger disabled text-bold">
                    <i class="fa fa-comment-o"></i> 
                    This Mock Exam Already Canceled.
                </p>   
                <?php 
                echo anchor(
                    site_url(Backend_URL . 'exam/rollback_action/' . $id), 
                    '<i class="fa fa-undo"></i> Rollback / Re-Pubhslih', 
                    'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                );                
              
            } elseif( $datetime > date("Y-m-d H:i:s") && $exam_status=='Active' ){
                echo anchor(
                    site_url(Backend_URL . 'exam/cancel_action/' . $id), 
                    '<i class="fa fa-times"></i> Click Here to Confrim Cancellation', 
                    'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                ); 
            } else {
               echo '<p class="btn btn-danger disabled text-bold">
                    <i class="fa fa-lock"></i>
                    Cancel Option is Deisabled. <br/>Due to The Mock Exam date already pass.
                </p>';
            }
                
            ?>
        </div>
    </div>
</section>