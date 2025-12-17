<?php
defined('BASEPATH') OR exit('No direct script access allowed');
load_module_asset('users', 'css');
?>

<section class="content-header">
    <h1>User Details <small>of</small> <?php echo $full_name; ?> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin </a></li>
        <li><a href="<?php echo Backend_URL . '/users' ?>"> Users</a></li>
        <li class="active">User</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">

    <?php echo Users_helper::makeTab($id, 'profile'); ?>

    <div class="box box-primary no-border">
        <div class="box-body">

            <table class="table table-striped">
                <tr>
                    <td width="120">Full Name</td>
                    <td width="5">:</td>
                    <td><?php echo $full_name; ?></td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td>:</td>                 
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td>Mobile Number</td>
                    <td>:</td>
                    <td><?php echo $mobile_number;   ?></td>
                </tr>
                <tr>
                    <td>Registration Date</td>
                    <td>:</td>
                    <td><?php echo globalDateFormat($created_at); ?></td>
                </tr>                
                <tr>
                    <td>Address Line 1</td>
                    <td>:</td>                 
                    <td><?php echo $add_line1; ?></td>
                </tr>
                <tr>
                    <td>Address Line 2</td>
                    <td>:</td>                 
                    <td><?php echo $add_line2; ?></td>
                </tr>
                <tr>
                    <td>City</td><td>:</td>                 
                    <td><?php echo $city; ?></td>
                </tr>
                <tr>
                    <td>County</td>
                    <td>:</td>
                    <td><?php echo $state; ?></td>
                </tr>
                <tr>
                    <td>Postcode</td>
                    <td>:</td>
                    <td><?php echo $postcode; ?></td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>:</td>
                    <td><?php echo $country_id; ?></td>
                </tr>
            </table>
        </div>
    </div>
</section>