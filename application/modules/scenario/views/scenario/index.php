<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1><?php echo getExamName($id); ?> Scenarios <small></small> 
        <?php echo  anchor(
                    site_url(Backend_URL . "scenario/create?id={$id}"), 
                    '<i class="fa fa-plus"></i> Add New Scenario', 
                    'class="btn btn-primary"'
                );
            ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Scenario</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'scenario'); ?>" class="form-inline" method="get">
                    <input type="hidden" name="id" value="<?= $id; ?>"/>
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit">Search</button>
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . "scenario?id={$id}"); ?>"
                                   class="btn btn-primary">Reset</a>
                            <?php } ?>                            
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <?php if($scenarios) { ?>
        <form method="post" id="scenarios" onsubmit="return multiDelete(event);">
        <div class="box-body">            
            <div class="table-responsive">
                
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="40" class="text-center">
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">                                            
                                <i class="fa fa-square-o"></i>
                            </button>
                        </th>
                        <th width="100">Scenario No</th>
                        <th>Presentation</th>
                        <th>Diagnosis</th>
                        <th width="150">Created on</th>
                        <th width="150">Updated on</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($scenarios as $scenario) {
                        
                        //$disabled = (($scenario->used + $scenario->examed)) ? 'disabled' : 'class="scen_id"';
                        $disabled = 'disabled';
                        ?>
                        <tr id="row_<?= $scenario->id; ?>">
                            <td class="text-center">
                                <input name="id[<?= $scenario->id; ?>]" 
                                       <?= $disabled; ?>
                                       value="<?= $scenario->id; ?>" 
                                       type="checkbox" />
                            </td>
                            <td><?php echo sprintf('%03d',$scenario->reference_number); ?></td>                            
                            <td><?php echo $scenario->presentation; ?></td>
                            <td><?php echo $scenario->name; ?></td>
                            <td><?php echo globalDateTimeFormat($scenario->created_at); ?></td>
                            <td><?php echo globalDateTimeFormat($scenario->updated_at); ?></td>
                            <td class="text-center">
                                <?php
                                echo anchor(site_url(Backend_URL . 'scenario/read/' . $scenario->id), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-primary"');
                                echo anchor(site_url(Backend_URL . 'scenario/update/' . $scenario->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                echo '&nbsp;';
                                echo scenarioDelBtn($scenario->id, 1);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
               
            </div>

        </div>
        <div class="box-footer">
            <div id="respond"></div>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-danger">
                        Delete Multi Scenario
                    </button>
                    <span class="btn btn-primary">Total <?php echo $total_rows ?> Scenarios</span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
             </form>
        <?php } else { ?>
            <div class="box-body">
                <p class="ajax_notice"> No Scenario Found</p>
            </div>
        <?php } ?>
    </div>
</section>
<?php load_module_asset('scenario','js'); ?>