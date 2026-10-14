<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1> Promo Codes <small>Control
            panel</small> <?php echo anchor(site_url(Backend_URL . 'promocodes/create'), ' + Add New', 'class="btn btn-default"'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Promocodes</li>
    </ol>
</section>

<section class="content personaldevelopment">

    <?php echo promocodesTabAsStatus($status); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <div class="col-md-3 col-md-offset-9 text-right">
                <form action="<?php echo site_url(Backend_URL . 'promocodes'); ?>" class="form-inline" method="get">
                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . 'promocodes?status=' . $status); ?>" class="btn btn-default">Reset</a>
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
                        <th style="width: 2%">Amount</th>
                        <th style="width: 10%">Validity</th>
                        <th style="width: 5%">Uses(Limit)</th>
                        <th style="width: 3%">Status</th>
                        <th style="width: 10%">Created By</th>
                        <th style="width: 8%">Updated</th>
                        <th style="width: 210px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($promo_codes as $promo_code) { ?>
                        <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $promo_code->courses; ?></td>
                            <td><?php echo $promo_code->code; ?></td>
                            <td><?php echo $promo_code->promoter_name; ?></td>
                            <td><?php echo promoAmount($promo_code->amount, $promo_code->discount_type); ?></td>
                            <td><?php echo date('d M y', strtotime($promo_code->start_date)) ?> ~ <?php echo date('d M y', strtotime($promo_code->end_date)) ?></td>
                            <td>
                                <span class="badge badge-primary"> <?php echo $promo_code->uses; ?> (<?php echo $promo_code->uses_limit; ?>) </span>
                            </td>
                            <td>
                                <span class="badge" style="background: <?= $promo_code->status == 'Public' ? 'green' : 'red' ?>">
                                    <?= $promo_code->status == 'Public' ? 'Public' : 'Draft' ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $promo_code->full_name; ?>
                                <br>
                                <span class="d-block"><?php echo date('d M Y', strtotime($promo_code->created_on)) ?></span>
                            </td>
                            <td><?php echo $promo_code->updated_on ? date('d M Y', strtotime($promo_code->updated_on)) : '' ?></td>
                            <td style="white-space: nowrap">
                                <?php
                                echo '<button type="button" class="btn btn-xs btn-primary promo-view" data-id="' . $promo_code->id . '"><i class="fa fa-fw fa-external-link"></i> View</button> ';
                                echo anchor(site_url(Backend_URL . 'promocodes/update/' . $promo_code->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                echo anchor(site_url(Backend_URL . 'promocodes/delete/' . $promo_code->id), '<i class="fa fa-fw fa-trash"></i> Delete ', 'class="btn btn-xs btn-danger"');
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (!$promo_codes) { ?>
                        <tr>
                            <td colspan="11">No <?php echo $status; ?> promo codes found.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <span class="btn btn-primary">Total <?php echo ucfirst($status); ?> Promo Codes: <?php echo $total_rows ?></span>

                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="promoViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" id="promo_view_content"></div>
</div>

<script type="text/javascript">
    $('body').on('click', '.promo-view', function () {
        var id = $(this).data('id');
        $('#promo_view_content').html('<div class="modal-content"><div class="modal-body"><p class="ajax_processing">Please Wait...</p></div></div>');
        $('#promoViewModal').modal('show');

        $.ajax({
            url     : "<?php echo site_url(Backend_URL . 'promocodes/read/'); ?>" + id,
            type    : 'get',
            dataType: 'html',
            success : function (respond) {
                $('#promo_view_content').html(respond);
            },
            error   : function () {
                $('#promo_view_content').html('<div class="modal-content"><div class="modal-body"><p class="ajax_error">Could not load details.</p></div></div>');
            }
        });
    });
</script>