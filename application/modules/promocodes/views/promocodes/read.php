<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Promo Codes <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'promocodes') ?>">Promocodes</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo promocodesTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="box-title">Details View</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <table class="table table-striped">
            <tr>
                <td width="150">Amount</td>
                <td width="5">:</td>
                <td><?php echo $amount; ?></td>
            </tr>
            <tr>
                <td width="150">Code</td>
                <td width="5">:</td>
                <td><?php echo $code; ?></td>
            </tr>
            <tr>
                <td width="150"> Promoter Name </td>
                <td width="5">:</td>
                <td><?php echo $promoter_name; ?></td>
            </tr>
            <tr>
                <td width="150">Created On</td>
                <td width="5">:</td>
                <td><?php echo date('d M Y', strtotime($created_on)) ?></td>
            </tr>
            <tr>
                <td width="150">Distcount Type</td>
                <td width="5">:</td>
                <td><?php echo $discount_type; ?></td>
            </tr>
            <tr>
                <td width="150">End Date</td>
                <td width="5">:</td>
                <td><?php echo date('d M Y', strtotime($end_date)) ?></td>
            </tr>
            <tr>
                <td width="150">Start Date</td>
                <td width="5">:</td>
                <td><?php echo date('d M Y', strtotime($start_date)) ?></td>
            </tr>
            <tr>
                <td width="150">Status</td>
                <td width="5">:</td>
                <td>
                    <span class="badge" style="background: <?= $status ? 'green' : 'red' ?>">
                        <?= $status ? 'Active' : 'Deactive' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="150">Updated On</td>
                <td width="5">:</td>
                <td><?php echo $updated_on ? date('d M Y', strtotime($updated_on)) : '' ?></td>
            </tr>
            <tr>
                <td width="150">User Id</td>
                <td width="5">:</td>
                <td><?php echo $full_name; ?></td>
            </tr>
            <tr>
                <td width="150">Uses Limit</td>
                <td width="5">:</td>
                <td><?php echo $uses_limit; ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><a href="<?php echo site_url(Backend_URL . 'promocodes') ?>" class="btn btn-default"><i
                                class="fa fa-long-arrow-left"></i> Back</a><a
                            href="<?php echo site_url(Backend_URL . 'promocodes/update/' . $id) ?>"
                            class="btn btn-primary"> <i class="fa fa-edit"></i> Edit</a></td>
            </tr>
        </table>
    </div>
</section>