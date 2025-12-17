<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('whatsapp', 'css'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1> Country  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>whatsapp">Whatsapp</a></li>
        <li class="active">Country</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?= waCountryTabs('country');?>
    <div class="box box-primary">            
        <div class="box-header with-border">                                   
            <h3 class="box-title">List of Country with Student</h3>
        </div>

        <div class="box-body">            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>                            
                            <th>Name</th>
                            <th>WhatsApp </th>
                            <th class="text-center">As Current Location </th>                            
                            <th class="text-center">Country of Origin </th>
                            
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($countrys as $country) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>                                
                                <td><?php echo $country->name ?></td>
                                <td><?= Wa::hasWhatsApp($country->id, 'Country'); ?></td>
                                <td class="text-center"><?php echo qty($country->CurrentQty); ?></td>                                
                                <td class="text-center"><?php echo qty($country->OriginQty); ?></td>                                
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>        
</section>
<!--
http://localhost/SamsonPlabExam/admin/student?ocid=218&pcid=218
-->