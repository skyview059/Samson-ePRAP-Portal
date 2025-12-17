<div class="row" style="padding-bottom: 15px">
    <div class="col-md-12">
        <h3 style="font-weight: 700; color: #6C00A1">Online Mock</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">List</div>
            <div class="panel-body">
                <?php if ($online_mocks): ?>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th width="50">S/L</th>
                            <th>Label</th>
                            <th>Exam Date & Time</th>
                            <th class="text-center">Days Left</th>
                            <th width="150">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sl = 1;
                        foreach ($online_mocks as $t) : ?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td><?= $t->exam_name; ?> - <small><b><?= $t->label; ?></b></small></td>
                                <td><?= globalDateTimeFormat($t->datetime); ?></td>
                                <td class="text-center"><?= dayLeftOfExam($t->datetime); ?></td>
                                <td>
                                    <?php /* ?>
                                    <a href="<?= site_url('mock/exam-room/' . $t->exam_schedule_id); ?>"
                                       class="btn btn-xs btn-success" <?= ($t->datetime < date('Y-m-d H:i:s')) ? 'disabled' : '' ?>>
                                        <i class="fa fa-play"></i> Enter exam room
                                    </a>
                                    <?php */ ?>
                                    <a href="<?= $t->zoom_link; ?>"
                                       target="_blank"
                                       class="btn btn-xs btn-success" <?= ($t->datetime < date('Y-m-d H:i:s')) ? 'disabled' : '' ?>>
                                        <i class="fa fa-play"></i> Enter exam room
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="ajax_notice">No Mock Found!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($total_rows > 0): ?>
    <div class="row">
        <div class="col-md-6">
            <span class="label label-primary">Total: <?php echo $total_rows ?></span>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination; ?>
        </div>
    </div>
<?php endif; ?>