<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Promo Codes <small>Control
            panel</small> <?php echo anchor(site_url(Backend_URL . 'promocodes/create'), ' + Add New', 'class="btn btn-default"'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Promocodes</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'promocodes'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'promocodes'); ?>" class="btn btn-default">Reset</a>
                            <?php } ?>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="box-body">
            <?php echo $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-hover table-condensed text-center">
                    <thead>
                    <tr>
                        <th style="width: 2%">S/L</th>
                        <th>Courses</th>
                        <th style="width: 5%">Code</th>
                        <th style="width: 10%">Promoter Name</th>
                        <th style="width: 2%">Type</th>
                        <th style="width: 2%">Amount</th>
                        <th style="width: 10%">Validity</th>
                        <th style="width: 5%">Uses Limit</th>
                        <th style="width: 3%">Status</th>
                        <th style="width: 10%">Created By</th>
                        <th style="width: 8%">Updated</th>
                        <th style="width: 15%">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($promocodess as $promocodes) { ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $promocodes->courses; ?></td>
                            <td><?php echo $promocodes->code; ?></td>
                            <td><?php echo $promocodes->promoter_name; ?></td>
                            <td><?php echo $promocodes->discount_type; ?></td>
                            <td><?php echo $promocodes->amount; ?></td>
                            <td><?php echo date('d M y', strtotime($promocodes->start_date)) ?> ~ <?php echo date('d M y', strtotime($promocodes->end_date)) ?></td>
                            <td>
                                <span class="badge badge-primary"> <?php echo $promocodes->uses; ?>/<?php echo $promocodes->uses_limit; ?> </span>
                            </td>
                            <td>
                                <span class="badge" style="background: <?= $promocodes->status == 'Public' ? 'green' : 'red' ?>">
                                    <?= $promocodes->status == 'Public' ? 'Public' : 'Draft' ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $promocodes->full_name; ?>
                                <br>
                                <span class="d-block"><?php echo date('d M Y', strtotime($promocodes->created_on)) ?></span>
                            </td>
                            <td><?php echo $promocodes->updated_on ? date('d M Y', strtotime($promocodes->updated_on)) : '' ?></td>
                            <td>
                                <?php
                                echo anchor(site_url(Backend_URL . 'promocodes/read/' . $promocodes->id), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-primary"');
                                echo anchor(site_url(Backend_URL . 'promocodes/update/' . $promocodes->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                echo anchor(site_url(Backend_URL . 'promocodes/delete/' . $promocodes->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Promo Codes: <?php echo $total_rows ?></span>

                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
    </div>
</section>