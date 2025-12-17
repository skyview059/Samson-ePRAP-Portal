<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <section class="content-header">
        <h1> WhatsApp & Telegram <small><?php echo $button ?></small> <a
                    href="<?php echo site_url(Backend_URL . 'whatsapp') ?>" class="btn btn-default">Back</a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="<?php echo Backend_URL ?>whatsapp">Whatsapp</a></li>
            <li class="active">Add New</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Add WhatsApp/Telegram Group Link</h3>
            </div>

            <div class="box-body">
                <?php echo form_open($action, array('class' => 'form-horizontal', 'method' => 'post')); ?>

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
                    <label for="link_for" class="col-sm-2 control-label">Link For <sup>*</sup></label>
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
                    <label for="link_for" class="col-sm-2 control-label">Link To <sup>*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="rel_id" id='rel_id' required>
                            <?php echo wa::getMocks($rel_id); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Title <sup>*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                               value="<?php echo $title; ?>" required/>
                        <?php echo form_error('title') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="link" class="col-sm-2 control-label">Link <sup>*</sup></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="link" id="link" placeholder="Link"
                               value="<?php echo $link; ?>" required/>
                        <?php echo form_error('link') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Status <sup>*</sup></label>
                    <div class="col-sm-10" style="padding-top:8px;">
                        <?php
                        echo htmlRadio('status', $status, ['Draft' => 'Draft', 'Publish' => 'Publish']);
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
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
<?php load_module_asset('whatsapp', 'js'); ?>