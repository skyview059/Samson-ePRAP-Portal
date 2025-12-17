<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php load_module_asset('course', 'css'); ?>
<section class="content-header">
    <h1> Course  <small><?php echo $button ?></small> <a href="<?php echo site_url(Backend_URL . 'course') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<section class="content">       
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Course</h3>
        </div>

        <div class="box-body">
            <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
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
                    <div class="row">
                        <div class="col-md-12" id="dates_row">
                            
                            <table id="course_dates" class="table table-striped">                                                                
                            <thead>
                                <tr>
                                    <th>S.L.</th>
                                    <th>Start</th>
                                    <th>End</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dates as $k => $date) { ?>
                                    <tr id="row_<?= $k; ?>">
                                        <td><?= ($k+1); ?></td>
                                        <td><input name="dates[<?= $k; ?>][start][date]" value="<?= $date['start']['date']; ?>" type="text" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                                        <td>
                                        <div class="input-group">
                                            <span class="input-group-addon adjust-dp">
                                                <select name="dates[<?= $k; ?>][start][hh]" class="form-control" >
                                                    <?= numericDropDown2(1, 12, 1, $date['start']['hh'] ); ?>
                                                </select>
                                            </span>
                                            
                                            <span class="input-group-addon adjust-dp">
                                            <select name="dates[<?= $k; ?>][start][mm]" class="form-control" >
                                                <?= numericDropDown2(0, 45, 15, $date['start']['mm'] ); ?>
                                            </select>  
                                            </span>
                                            
                                            <span class="input-group-addon">
                                                <?php echo htmlRadio("dates[{$k}][start][slot]", $date['start']['slot'], ['AM' => 'AM', 'PM' => 'PM'] ); ?>
                                            </span>
                                        </div>
                                    </td>
                                        <td><input name="dates[<?= $k; ?>][end][date]" value="<?= $date['end']['date']; ?>" type="text" placeholder="YYYY-MM-DD" class="form-control js_datepicker" /></td>
                                        
                                        <td>
                                        <div class="input-group">
                                            <span class="input-group-addon adjust-dp">
                                                <select name="dates[<?= $k; ?>][end][hh]" class="form-control" >
                                                    <?= numericDropDown2(1, 12, 1,  $date['end']['hh'] ); ?>
                                                </select>
                                            </span>
                                            
                                            <span class="input-group-addon adjust-dp">
                                            <select name="dates[<?= $k; ?>][end][mm]" class="form-control" >
                                                <?= numericDropDown2(0, 45, 15, $date['end']['mm'] ); ?>
                                            </select>  
                                            </span>
                                            
                                            <span class="input-group-addon">
                                                <?php echo htmlRadio("dates[{$k}][end][slot]", $date['end']['slot'], ['AM' => 'AM', 'PM' => 'PM'] ); ?>
                                            </span>
                                        </div>
                                    
                                    </td>
                                        <td><span class="btn btn-xs btn-danger" onclick="removeRow('#row_<?= $k; ?>'); "><i class="fa fa-times"></i></span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <span class="btn btn-success btn-xs btn-block" id="add_new_box">
                                            + Add New Course Date
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>                    
                </div>                
            </div>
            
            <?= loadWhatsAppWidget('Course'); ?>
            
            <div class="form-group">
                <div class="col-md-10 col-md-offset-2" style="padding-left:5px;">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                    <a href="<?php echo site_url(Backend_URL . 'course') ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>

<?php load_module_asset('course', 'js'); ?>