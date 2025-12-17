<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('practice','css'); ?>
<?php load_module_asset('users','css'); ?>
<section class="content-header">
    <h1> <?php echo getPracticeName($id); ?> Mock Practice Dates 
        <small>Control panel</small> 
        <?php 

            echo anchor(
                site_url(Backend_URL . 'practice/create?id='. $id ), 
                '<i class="fa fa-plus"></i> Add New Practice', 
                'class="btn btn-primary"'
            ); 
        ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Practice</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo practiceListTab( $tab, $coming, $past ); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Mock Practice List</h3>            
        </div>

        <div class="box-body">

            <?php if(!$id){ echo '<p class="ajax_error"> <i class="fa fa-arrow-left"></i> &nbsp; Please Click Practice Name for Left sidebar.</p>'; } ?>
            
            <?php if(!$practices){ ?>
                <p class="ajax_notice"> No Practice Schedule Found</p>                                
                
            <?php } else { ?>
            
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>                        
                        <th>Label</th>  
                        <th width="40" class="text-center">Seat</th>
                        <th width="300">Centre</th>
                        
                        <th width="60" class="text-center">Student</th>
                        <th width="200">Practice Date & Time</th>                        
                        <th width="80">Duration</th>                        
                        <th class="text-center" width="75">Days Left</th>                        
                        <th width="80">WhatsApp </th>                        
                        <th width="100">Status </th>                        
                        <th width="120" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>                       
                    <?php foreach ($practices as $practice) { ?>
                        <tr class="mc_<?= strtolower_fk($practice->status); ?>">
                            <td><?php echo sprintf('%02d', ++$start); ?></td>                            
                            <td><?php echo $practice->category_name; ?> (<b><?php echo $practice->label; ?></b>)</td>
                            <td class="text-center"><?php echo $practice->seat; ?></td>
                            
                            <td><?php echo $practice->centre; ?></td>
                            
                            <td class="text-center">
                                <a href="admin/practice/student/<?php echo $practice->id; ?>" title="See All Scenario">
                                    <b><?php echo countPracticeStudent($practice->id); ?>&nbsp;</b>
                                    <i class="fa fa-external-link-square"></i>
                                </a>                                                                
                            </td>
                            <td><?php echo globalDateTimeFormat($practice->datetime); ?></td>
                            <td class="text-center"><?php echo $practice->duration; ?> hour</td>                            
                            <td class="text-center"><?php echo dayLeftOfExam($practice->datetime); ?></td>                                                       
                            <td><?= Wa::hasWhatsApp($practice->id, 'Practice'); ?></td>
                            <td><?php echo isConfirmed($practice->status); ?></td>
                            <td class="text-center">
                                <?php
                                echo anchor(site_url(Backend_URL . 'practice/update/' . $practice->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');                                
                                echo anchor(site_url(Backend_URL . 'practice/delete/' . $practice->id), '<i class="fa fa-fw fa-times"></i>', 'class="btn btn-xs btn-danger"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span>Total Practice: <?php echo $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
