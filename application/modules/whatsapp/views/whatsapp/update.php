<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>WhatsApp & Telegram <small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>whatsapp">Whatsapp</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?= waTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update WhatsApp/Telegram Group Link</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="form-group">
                    <label for="link_type" class="col-sm-2 control-label">Link Type <sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('link_type', $link_type, [
                            'Whatsapp' => 'WhatsApp',
                            'Telegram' => 'Telegram'
                        ], 'class="link_type"');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="link_for" class="col-sm-2 control-label">Link For :</label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('link_for', $link_for, [
                            'Mock'     => 'Mock',
                            'Course'   => 'Course',
                            'Practice' => 'Practice',
                            'Country'  => 'Country'
                        ], 'class="link_for"');
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rel_id" class="col-sm-2 control-label">Link For :</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="rel_id" id='rel_id'>
                            <?php echo wa::getMocks($rel_id); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Title :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                               value="<?php echo $title; ?>"/>
                        <?php echo form_error('title') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="link" class="col-sm-2 control-label">Link :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="link" id="link" placeholder="Link"
                               value="<?php echo $link; ?>"/>
                        <?php echo form_error('link') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Status :</label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('status', $status, array(
                                'Draft' => 'Draft', 'Publish' => 'Publish'
                            )
                        );
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                        <a href="<?php echo site_url(Backend_URL . 'whatsapp') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php load_module_asset('whatsapp', 'js'); ?>
<script>
    $(document).ready(function () {
        var link_for = $('input[name="link_for"]:checked').val();
        $.ajax({
            type      : 'POST',
            data      : {link_for: link_for, rel_id: <?=  $rel_id; ?>},
            url       : 'admin/whatsapp/get_rel_id',
            dataType  : 'html',
            beforeSend: function () {
                $('#rel_id').html('<option value="0">--Loading...--</option>');
            },
            success   : function (respond) {
                $('#rel_id').html(respond);
            }
        });
    });
</script>    