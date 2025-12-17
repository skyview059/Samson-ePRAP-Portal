<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users','css'); ?>
<section class="content-header">
    <h1>Promo Codes  <small>Delete</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url( Backend_URL )?>"><i class="fa fa-dashboard"></i> Admin</a></li>
	<li><a href="<?php echo Backend_URL ?>promocodes">Promocodes</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content">
    <?php echo promocodesTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <table class="table table-striped">
	    <tr><td width="150">Amount</td><td width="5">:</td><td><?php echo $amount; ?></td></tr>
	    <tr><td width="150">Code</td><td width="5">:</td><td><?php echo $code; ?></td></tr>
	    <tr><td width="150">Course Id</td><td width="5">:</td><td><?php echo $course_id; ?></td></tr>
	    <tr><td width="150">Created On</td><td width="5">:</td><td><?php echo $created_on; ?></td></tr>
	    <tr><td width="150">Distcount Type</td><td width="5">:</td><td><?php echo $discount_type; ?></td></tr>
	    <tr><td width="150">End Date</td><td width="5">:</td><td><?php echo $end_date; ?></td></tr>
	    <tr><td width="150">Start Date</td><td width="5">:</td><td><?php echo $start_date; ?></td></tr>
	    <tr><td width="150">Status</td><td width="5">:</td><td><?php echo $status; ?></td></tr>
	    <tr><td width="150">Updated On</td><td width="5">:</td><td><?php echo $updated_on; ?></td></tr>
	    <tr><td width="150">User Id</td><td width="5">:</td><td><?php echo $user_id; ?></td></tr>
	    <tr><td width="150">Uses Limit</td><td width="5">:</td><td><?php echo $uses_limit; ?></td></tr>
	</table>
	<div class="box-header">
			 <?php echo anchor(site_url(Backend_URL .'promocodes/delete_action/'.$id),'<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); ?>
	</div>
	</div></section>