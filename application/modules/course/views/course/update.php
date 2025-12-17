<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('course', 'css'); ?>
<section class="content-header">
    <h1>Course<small><?php echo $button ?></small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <?php echo courseTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update Course</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="category_id" class="col-sm-2 control-label">Select Category :</label>
                    <div class="col-sm-10">                    
                        <select class="form-control" name="category_id" id="category_id">
                            <?php echo getDropDownCategory($category_id); ?>
                        </select>
                        <?php echo form_error('category_id') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name :</label>
                    <div class="col-sm-10">                    
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
                        <?php echo form_error('name') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Description :</label>
                    <div class="col-sm-10"> 
                    <textarea class="form-control" name="description" rows="3"><?php echo $description; ?></textarea>
                        <?php echo form_error('description') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-sm-2 control-label">Price :</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?php echo $price; ?>" />
                            <span class="input-group-addon">GBP</span>
                        </div>                        
                        <?php echo form_error('price') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="duration" class="col-sm-2 control-label">Duration :</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="duration" id="duration" placeholder="Duration" value="<?php echo $duration; ?>" />
                            <span class="input-group-addon">Days</span>
                        </div>                        
                        <?php echo form_error('duration') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="booking_limit" class="col-sm-2 control-label">Booking Limit :</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="booking_limit" id="booking_limit" placeholder="Booking Limit" value="<?php echo $booking_limit; ?>" />
                            <span class="input-group-addon">Seat</span>
                        </div>                        
                        <?php echo form_error('booking_limit') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status"  class="col-sm-2 control-label">Status :</label>
                    <div class="col-sm-10"  style="padding-top:8px;"><?php echo htmlRadio('status', $status, array('Active' => 'Active', 'Inactive' => 'Inactive')); ?></div>
                </div>
                
                
                <div class="form-group">
                    <label for="start_date" class="col-sm-2 control-label">Start Date :</label>
                    <div class="col-sm-10">                    
                        <table id="course_dates" class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="40">ID</th>
                                    <th width="120">Start-Date</th>
                                    <th>Start-Time</th>
                                    <th width="120">End-Date</th>
                                    <th>End-Time</th>
                                    <th>Seat Booked</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $sl = 0;
                            foreach ($dates as $date) {
                            ++$sl;                            
                            ?>
                                <tr id="row_<?= $date->id; ?>">
                                    <td><input name="dates[<?= $sl; ?>][id]" style="width:50px;" readonly="readonly" value="<?= $date->id; ?>" type="text" class="form-control" /></td>
                                    <td><input name="dates[<?= $sl; ?>][start][date]" value="<?= $date->start_date; ?>" type="text"  placeholder="YYYY-MM-DD" class="form-control js_datepicker" id="js_datepicker" /></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon adjust-dp">
                                                <select name="dates[<?= $sl; ?>][start][hh]" class="form-control" >
                                                    <?= numericDropDown2(1, 12, 1, $date->start_hh ); ?>
                                                </select>
                                            </span>
                                            
                                            <span class="input-group-addon adjust-dp">
                                            <select name="dates[<?= $sl; ?>][start][mm]" class="form-control" >
                                                <?= numericDropDown2(0, 45, 15, $date->start_mm ); ?>
                                            </select>  
                                            </span>
                                            
                                            <span class="input-group-addon">
                                                <?php echo htmlRadio("dates[{$sl}][start][slot]", $date->start_apm, ['AM' => 'AM', 'PM' => 'PM'] ); ?>
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td><input name="dates[<?= $sl; ?>][end][date]" value="<?= $date->end_date; ?>" type="text"  placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon adjust-dp">
                                                <select name="dates[<?= $sl; ?>][end][hh]" class="form-control" >
                                                    <?= numericDropDown2(1, 12, 1, $date->end_hh ); ?>
                                                </select>
                                            </span>
                                            
                                            <span class="input-group-addon adjust-dp">
                                            <select name="dates[<?= $sl; ?>][end][mm]" class="form-control" >
                                                <?= numericDropDown2(0, 45, 15, $date->end_mm ); ?>
                                            </select>  
                                            </span>
                                            
                                            <span class="input-group-addon">
                                                <?php echo htmlRadio("dates[{$sl}][end][slot]", $date->end_apm, ['AM' => 'AM', 'PM' => 'PM'] ); ?>
                                            </span>
                                        </div>
                                    
                                    </td>
                                    
                                    <td><input style="width:75px;" readonly="readonly" value="<?= $date->seat_booked; ?>" type="text" class="form-control" /></td>
                                    <td>
                                        <?php if(!$date->seat_booked){ ?>
                                        <span class="btn btn-xs btn-danger" onclick="deleteRowAndDB(<?= $date->id; ?>);">
                                            <i class="fa fa-times"></i>
                                        </span>
                                        <?php } else { ?>
                                        <span class="btn btn-xs btn-danger disabled">
                                            <i class="fa fa-ban"></i>
                                        </span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <span class="btn btn-success btn-xs btn-block" id="add_row_for_update">
                                            + Add New Course Date
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                        <a href="<?php echo site_url(Backend_URL . 'course') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
    var index2 = <?= $sl+1; ?>;
</script>
<?php load_module_asset('course', 'js'); ?>
