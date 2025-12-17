<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Global Site Setting <small>Control panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Settings</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading"><b>Note:</b> <u>Outgoing Email</u> address must be this domain email address.
            E.g. if your domain name is "www.example.com" email should "xxx@example.com".
        </div>
        <div class="panel-body">
            <div id="ajaxRespond"></div>
            <form method="post" id="settings" action="<?php echo Backend_URL; ?>settings/update" name="settings">
                <table class="table  table-bordered table-striped" style="margin-bottom: 10px">
                    <?php foreach ($settings as $setting) { ?>
                        <tr>
                            <td width="220"><?php echo Setting_helper::splitSettings($setting->label); ?></td>
                            <td><?php echo Setting_helper::switchFormFiled($setting->field_type, $setting->label, $setting->value); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td>
                            <button class="btn btn-primary" id="submit" type="button" name="save"><i
                                        class="fa fa-save"></i> Update Setting
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</section>

<?php loadCKEditor5ClassicBasic(['#EmailFooterSignature']); ?>

<script>
    $('#submit').on('click', function (e) {
        e.preventDefault();

        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        const settings = $('#settings').serialize();
        $.ajax({
            url       : 'admin/settings/update',
            type      : 'POST',
            dataType  : "json",
            data      : settings,
            beforeSend: function () {
                $('#ajaxRespond')
                    .html('<p class="ajax_processing">Loading...</p>')
                    .css('display', 'block');
            },
            success   : function (respond) {
                $('#ajaxRespond').html(respond.Msg);
                $('html, body').animate({
                    scrollTop: $("#ajaxRespond").offset().top
                }, 1500);
                if (respond.Status === 'OK') {
                    setTimeout(function () {
                        $('#ajaxRespond').slideUp();
                    }, 3000);
                }
            }
        });
    });
</script>      