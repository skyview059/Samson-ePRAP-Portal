<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>WhatsApp<small> Link Sent Log</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>whatsapp">Whatsapp</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?= waTabs($id, 'log'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">WhatsApp Group Link Sent Log</h3>            
        </div>

        <div class="box-body">
            
            <?php if($logs) {?>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Sent at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $s ) { ?>
                    <tr>
                        <td><?= ++$sl; ?></td>
                        <td><?= $s->student; ?></td>
                        <td><?= $s->email; ?></td>
                        <td><?= $s->sent_at; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <h1 class="alert alert-warning">
                Link did not send to anyone
                <i class="fa fa-unlink"></i>
            </h1>
            <?php } ?>
        </div>
    </div>
</section>