<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Course  <small>Read</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'course') ?>">Course</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo courseTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="box-title">Details View</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr><td width="150">Category</td><td width="5">:</td><td><?php echo getCategoryName($category_id); ?></td></tr>
                        <tr><td>Name</td><td>:</td><td><?php echo $name; ?></td></tr>
                        <tr><td>Description</td><td>:</td><td><?php echo $description; ?></td></tr>
                        <tr><td>Price</td><td>:</td><td><?php echo $price; ?></td></tr>
                        <tr><td>Duration</td><td>:</td><td><?php echo $duration; ?> Days</td></tr>
                        <tr><td>Booking Limit</td><td>:</td><td><?php echo $booking_limit; ?></td></tr>
                        <tr><td>Status</td><td>:</td><td><?php echo $status; ?></td></tr>                
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <?php  foreach ($dates as $key => $date) { ?>
                            <tr>
                                <td><?php echo ($key+1); ?></td>
                                <td><?php echo globalDateTimeFormat($date->start_date); ?></td>
                                <td><?php echo globalDateTimeFormat($date->end_date); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>            
        </div>
        
        <div class="box-footer">
            <a href="<?php echo site_url(Backend_URL . 'course') ?>" class="btn btn-default">
                <i class="fa fa-long-arrow-left"></i>
                Back
            </a>

            <a href="<?php echo site_url(Backend_URL . 'course/update/' . $id) ?>" class="btn btn-primary">
                <i class="fa fa-edit"></i> 
                Edit
            </a>
        </div>
    </div>
</section>