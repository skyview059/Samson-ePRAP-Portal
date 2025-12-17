<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1> WhatsApp & Telegram <small>Control panel</small>
        <a href="admin/whatsapp/create" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Add New Group
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">WhatsApp</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?= waCountryTabs(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="col-md-4">
                <h3 class="box-title"> What's App Group Links </h3>
            </div>
            <div class="col-md-5 col-md-offset-3 text-right">
                <form action="<?= site_url(Backend_URL . 'whatsapp'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?= $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?= site_url(Backend_URL . 'whatsapp'); ?>" class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?= $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th width="40">S/L</th>
                        <th>Title</th>
                        <th>Link Type</th>
                        <th>Link</th>
                        <th>Link For</th>
                        <th class="text-center">Sent</th>
                        <th class="text-center">Status</th>
                        <th width="120">Created On</th>
                        <th width="150">Created By</th>
                        <th class="text-center" width="120">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($whatsapps as $whatsapp) { ?>
                        <tr>
                            <td><?= ++$start; ?></td>
                            <td><?= $whatsapp->title; ?></td>
                            <td><?= $whatsapp->link_type; ?></td>
                            <td><?= wlinks($whatsapp->link); ?></td>
                            <td><?= $whatsapp->link_for; ?></td>
                            <td class="text-center">
                                <a href="<?= (Backend_URL . "whatsapp/log/{$whatsapp->id}"); ?>">
                                    <?= $whatsapp->sent_qty; ?>
                                </a>
                            </td>
                            <td class="text-center"><?= statusLevel($whatsapp->status); ?></td>
                            <td><?= globalDateFormat($whatsapp->created_on); ?></td>
                            <td><?= $whatsapp->full_name; ?></td>
                            <td class="text-center">
                                <?php
                                echo anchor(site_url(Backend_URL . 'whatsapp/log/' . $whatsapp->id), '<i class="fa fa-fw fa-bars"></i>', 'class="btn btn-xs btn-success" title="Log"');
                                echo anchor(site_url(Backend_URL . 'whatsapp/update/' . $whatsapp->id), '<i class="fa fa-fw fa-edit"></i>', 'class="btn btn-xs btn-default" title="Edit"');
                                echo anchor(site_url(Backend_URL . 'whatsapp/delete/' . $whatsapp->id), '<i class="fa fa-fw fa-times"></i>', 'onclick="return confirm(\'Confirm Delete\')" class="btn btn-xs btn-danger" title="Delete"');
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
                    <span class="btn btn-primary">Total Link: <?= $total_rows; ?></span>
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination ?>
                </div>
            </div>
        </div>
    </div>


</section>