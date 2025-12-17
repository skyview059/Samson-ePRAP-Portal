<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Course  <small>Delete</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content">
    <?php echo courseTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr><td width="150">Category</td><td width="5">:</td><td><?php echo getCategoryName($category_id); ?></td></tr>
                <tr><td>Name</td><td>:</td><td><?php echo $name; ?></td></tr>
                <tr><td>Description</td><td>:</td><td><?php echo $description; ?></td></tr>
                <tr><td>Price</td><td>:</td><td><?php echo $price; ?></td></tr>
                <tr><td>Duration</td><td>:</td><td><?php echo $duration; ?> Days</td></tr>
                <tr><td>Booking Limit</td><td>:</td><td><?php echo $booking_limit; ?></td></tr>
                <tr><td>Status</td><td>:</td><td><?php echo $status; ?></td></tr>                
            </table>
        </div>        
        <div class="box-header">
            <?php echo anchor(site_url(Backend_URL . 'course/delete_action/' . $id), '<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); ?>
        </div>
    </div>
</section>