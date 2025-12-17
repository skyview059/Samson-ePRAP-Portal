<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('message', 'css'); ?>
<section class="content-header">
    <h1> 
        Messaging Portal <small>Panel</small>
        <a href="admin/message/open" class="btn btn-primary">
            <i class="fa fa-edit"></i>
            Open New Message
        </a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Message</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">List of Messages  </div>
        <div class="panel-body">
            <div class="box-header with-border">
                <div class="col-md-3 col-md-offset-9 text-right">
                    <form action="<?php echo site_url(Backend_URL . 'message'); ?>" class="form-inline" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit">Search</button>
                                <?php if ($q <> '') { ?>
                                    <a href="<?php echo site_url(Backend_URL . 'message'); ?>" class="btn btn-default">Reset</a>
                                <?php } ?>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <form method="post" action="admin/message/multi_delete" name="delete">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" name="all" class="checkbox-toggle"></th>
                                <th>Student</th>
                                <th>Subject</th>
                                <!--<th class="text-center hidden">Qty</th>-->     
                                <th class="text-right">Sent At</th>
                                <th class="text-center" width='90'>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($messages as $m) {
                                $link = site_url(Backend_URL . 'message/view/' . $m->id);
                                ?>
                            <tr class="<?php echo $m->status .' '. lastReplyStatus($m->id); ?>">
                                    <td><input type="checkbox" name="id[]" value="<?= $m->id; ?>"  class="singleMail"></td>
                                    <td>
                                        <a href="<?= $link; ?>" class="read-more">
                                            <?php echo $m->student; ?>
                                        </a>
                                    </td>
                                    <td><a href="<?= $link; ?>" class="read-more">
                                        <?php echo getShortContent($m->subject, 30); ?>
                                        </a>
                                    </td>
                                    <!--<td class="text-center"><?php // echo countConversation($m->id); ?></td>-->                                    
                                    <td class="text-right"><?php echo dateTimeDifference($m->open_at); ?></td>
                                    <td class="text-center">
                                        <a href="<?= $link; ?>" class="btn btn-primary btn-sm">
                                            Read &nbsp;                                           
                                           <i class="fa fa-external-link"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>


                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Record: <?php echo $total_rows ?></span>
                            <button type="submit" id="multiDelete" class="btn btn-danger disabled">
                                Multi Delete Conversation
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</div>

</section>

<script>
    $(function () {

        var tblChkBox = $(".singleMail");
        $('input:checkbox[name="all"]').change(function () {
            if ($(this).is(':checked')) {
                $(tblChkBox).prop("checked", true);
                $('#multiDelete').removeClass('disabled');
            }
            if (!$(this).is(':checked')) {
                $(tblChkBox).prop("checked", false);
                $('#multiDelete').addClass('disabled');
            }
        });

        $('.singleMail').change(function () {
            var len = $(".singleMail:checked").length;
            if (len > 0) {
                $('#multiDelete').removeClass('disabled');
            } else {
                $('#multiDelete').addClass('disabled');
            }
        });

    });
</script>