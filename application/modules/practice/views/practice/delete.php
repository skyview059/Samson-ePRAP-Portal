<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Practice <small>Delete</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>practice">Practice</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo practiceTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="150">Practice Name</td>
                    <td width="5">:</td>
                    <td><?php echo $practice_name; ?></td>
                </tr>
                <tr>
                    <td>Practice Date & Time</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($datetime); ?></td>
                </tr>
                <tr>
                    <td>Centre</td>
                    <td>:</td>
                    <td><?php echo $centre_name; ?></td>
                </tr>
                <tr>
                    <td>Centre Address</td>
                    <td>:</td>
                    <td><?php echo $centre_address; ?></td>
                </tr>
                
            </table>
        </div>
        <div class="box-footer">
            
            <?php 
            
            if($warning == false ){ 
                 
                echo anchor(
                        site_url(Backend_URL . 'practice/delete_action/' . $id), 
                        '<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 
                        'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                    );
                
            } else { ?>
            
            <p class="btn btn-danger disabled text-bold">
                <i class="fa fa-warning"> </i>
                <?php echo $students; ?> 
                Student(s) enrolled for this practiceination 
                and delete option is now Disabled.
            </p>
            <?php }  ?>
        </div>
    </div>
</section>