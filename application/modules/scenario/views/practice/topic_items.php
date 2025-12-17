<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>  <?php echo $exam_name; ?> - Practice Scenario Topics <small>Items</small>
        <a href="<?php echo site_url(Backend_URL . 'scenario/practice/view/' . $exam_id); ?>"
           class="btn btn-default">Back</a></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'scenario/practice') ?>">Practice Scenarios</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'scenario/practice/view/' . $exam_id) ?>">Topics</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Manage / <?php echo $exam_name; ?> / <?php echo $subject_name; ?> / <?php echo $topic_name; ?> / Scenarios</div>
                <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>
                <div class="panel-body">
                    <div class="form-group">
                        <?php if ($items): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" id="ordering">
                                    <thead>
                                    <tr>
                                        <th width="50" class="text-center">S/L</th>
                                        <th>Topic</th>
                                        <th width="100" class="text-center">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php $start = 0;
                                    foreach ($items as $item) { ?>
                                        <tr id="item-<?= $item->id; ?>" class="item_id_<?php echo $item->id; ?>">
                                            <td>
                                                <i class="fa fa-arrows-v"></i> <?php echo sprintf('%03d', ++$start); ?>
                                            </td>
                                            <td>
                                                <p class="no-margin"><?php echo $item->presentation; ?></p>
                                                <p class="no-margin">
                                                    <em><?php echo "#{$item->ref_no} - {$item->name}"; ?></em></p>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    onClick="delete_topic_item(<?php echo $item->id; ?>)"
                                                    class="btn btn-danger btn-xs" title="Delete Group Item"><i
                                                        class="fa fa-trash"></i> Remove</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="ajax_notice">No scenario item added</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus"></i> Add Scenarios</div>
                <form id="add_scenario_form"
                      action="<?php echo site_url(Backend_URL . 'scenario/practice/topic_item_add_action'); ?>"
                      method="post">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="scenario_id">Scenario <sup>*</sup></label>
                            <select name="scenario_id" class="form-control select2" id="scenario_id" required>
                                <option value="">Select Scenario</option>
                                <?php foreach ($scenarios as $scenario) { ?>
                                    <option value="<?php echo $scenario->id; ?>">
                                        <?php echo "#{$scenario->ref_no} : {$scenario->name}"; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>"/>
                            <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>"/>
                            <button type="submit" class="btn btn-primary" id="add_scenario">
                                <i class="fa fa-plus"></i> Add Item to <?php echo $topic_name; ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

<script type="application/javascript">
    function delete_topic_item(id) {
        if (confirm('Are you sure to delete this item?')) {
            $.ajax({
                url       : 'admin/scenario/practice/topic_item_delete',
                type      : 'POST',
                dataType  : "json",
                data      : {id: id},
                beforeSend: function () {
                    $('.item_id_' + id).css('background-color', '#FF0000');
                },
                success   : function (jsonData) {
                    if (jsonData.Status === 'OK') {
                        $('.item_id_' + id).slideUp('slow');
                        toastr.success(jsonData.Msg);
                    } else {
                        toastr.error(jsonData.Msg);
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        $("#ordering tbody").sortable({
            axis  : "y",
            update: function (event, ui) {
                const data = $(this).sortable('serialize');
                $.ajax({
                    data   : data,
                    type   : 'POST',
                    url    : 'admin/scenario/practice/topic_items_save_order',
                    success: function (response) {
                        toastr.success("Order Saved Successfully!");
                    }
                });
            }
        });
    });
</script>
