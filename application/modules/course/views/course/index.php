<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Course  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Course</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">
            <form action="<?php echo site_url(Backend_URL . 'course'); ?>" class="form-inline" method="get">
                
                <div class="col-md-3 text-right">
                    <div class="input-group">
                        <select class="form-control" name="category_id" id="category_id">
                            <?php echo getDropDownCategory($category_id); ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">                        
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    <a href="<?php echo site_url(Backend_URL . 'course'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="box-body">            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Category</th>
                            <th>Name</th>                            
                            <th class="text-center">Schedule</th>                            
                            <th class="text-center">Booked</th>                            
                            <th class="text-right">Price</th>
                            <th class="text-right">Duration</th>
                            <th class="text-center">Seat</th>
                            <th class="text-center">WhatsApp</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($courses as $course) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $course->category; ?></td>
                                <td><?php echo $course->name; ?></td>                                
                                <td class="text-center"><?php echo intOnly($course->schedule); ?></td>                                
                                <td class="text-center"><?php echo intOnly($course->booked); ?></td>                                
                                <td class="text-right"><?php echo GBP($course->price); ?></td>
                                <td class="text-right"><?php echo $course->duration; ?> days</td>
                                <td class="text-center"><?php echo $course->booking_limit; ?></td>
                                <td class="text-center"><?php echo Wa::hasWhatsApp( $course->id ); ?></td>
                                <td class="text-center"><?php echo isActive($course->status); ?></td>                                
                                <td class="text-center">
                                    <?php
                                    echo anchor(site_url(Backend_URL . 'course/read/' . $course->id), '<i class="fa fa-fw fa-bars"></i> Preview', 'class="btn btn-xs btn-success" title="Details"');
                                    echo anchor(site_url(Backend_URL . 'course/update/' . $course->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-default" title="Edit"');                                    
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="box-footer">
            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Course: <?= $total_rows; ?></span>                    
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
    </div>
</section>