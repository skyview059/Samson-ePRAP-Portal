<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
        <h3 class="modal-title no-margin">Promo Code Details</h3>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-condensed no-margin">
            <tbody>
            <tr>
                <th width="150">Code</th>
                <td><?php echo $code; ?></td>
            </tr>
            <tr>
                <th>Courses</th>
                <td><?php echo $courses ?: '—'; ?></td>
            </tr>
            <tr>
                <th>Promoter Name</th>
                <td><?php echo $promoter_name; ?></td>
            </tr>
            <tr>
                <th>Discount Type</th>
                <td><?php echo $discount_type; ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo $amount; ?></td>
            </tr>
            <tr>
                <th>Validity</th>
                <td><?php echo date('d M Y', strtotime($start_date)) ?> ~ <?php echo date('d M Y', strtotime($end_date)) ?></td>
            </tr>
            <tr>
                <th>Uses Limit</th>
                <td><?php echo $uses_limit; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge" style="background: <?= $status == 'Public' ? 'green' : 'red' ?>">
                        <?= $status == 'Public' ? 'Public' : 'Draft' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th>Created By</th>
                <td><?php echo $full_name; ?></td>
            </tr>
            <tr>
                <th>Created On</th>
                <td><?php echo date('d M Y', strtotime($created_on)) ?></td>
            </tr>
            <tr>
                <th>Updated On</th>
                <td><?php echo $updated_on ? date('d M Y', strtotime($updated_on)) : '' ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a href="<?php echo site_url(Backend_URL . 'promocodes/update/' . $id) ?>" class="btn btn-warning">
            <i class="fa fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
