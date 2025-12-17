<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Dashboard <small>as <?php echo $role_name; ?></small> </h1>    
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<?php load_module_asset('exam','css');?>
<?php load_module_asset('dashboard', 'css'); ?>
<section class="content">    
    <?php echo $admin_block; ?>
    <?php echo $teacher_block; ?>
    <?php echo $assessor_block; ?>    
</section>
<?php load_module_asset('dashboard', 'js'); ?>