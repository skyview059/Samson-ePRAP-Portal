
<h3>Admin Messages</h3>
<div class="panel panel-default">
    <div class="panel-heading">List of Messages <a class="pull-right" style="color: #fff;" href="messages/open" class="btn btn-primary pull-right">
        <i class="fa fa-edit"></i>
        Open New Message
    </a></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>                            
                        <th width="300">Sent By</th>
                        <th width="300">Sent To</th>
                        <th>Subject</th>
                        <!--<th class="text-center">Qty</th>-->     
                        <th width='200'>Sent At</th>
                        <th class="text-center" width='90'>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($messages as $m) {                                                
                        $link = site_url('message_view/' . $m->id);
                        $mail = openBySwitch($m);
                        ?>
                        <tr>                                
                            <td>
                                <a href="<?= $link; ?>" class="read-more">
                                    <?php echo $mail['sender']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= $link; ?>" class="read-more">
                                    <?php echo $mail['receiver']; ?>
                                </a>
                            </td>
                            <td><a href="<?= $link; ?>" class="read-more">
                                    <?php echo '<b>' . getShortContent($m->subject, 30) . '</b>'; ?>
                                    <?php // echo getShortContent($m->body, 80); ?>
                                </a>
                            </td>
                            <!--<td class="text-center"><?php //echo countConversation($m->id); ?></td>-->                                    
                            <td><?php echo globalDateTimeFormat($m->open_at); ?></td>
                            <td class="text-center">
                                <a style="color: #196da4;font-weight: bold;" href="<?= $link; ?>" class="">
                                    Read
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
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</div>
