<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Student <small>Name <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>student">student</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'delete'); ?>
    <div class="panel panel-default">
        <div class="panel-body"> <table class="table table-striped">
                <tr>
                    <td width="150"><?php echo $number_type . ' Number' ?></td>
                    <td width="5">:</td>
                    <td><?php echo $gmc_number; ?></td>
                </tr>
                <tr>
                    <td>Full Name</td>
                    <td>:</td>
                    <td><?php echo $fname . ' ' . $mname . ' ' . $lname; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td>Registered At</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($created_at); ?></td>
                </tr>
                <tr>
                    <td>Updated on</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($updated_at); ?></td>
                </tr>
            </table>

            <div class="box-footer text-center">

                <?php
//            if (!$t_rels) {
                echo anchor(
                        site_url(Backend_URL . 'student/delete_action/' . $id), '<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                );
//            }
                // echo implode(', ', $message );
                ?>


<!--            <span style="color: red; font-style: italic;">Need to implement relational conditions</span>-->
            </div></div>
    </div>



</section>