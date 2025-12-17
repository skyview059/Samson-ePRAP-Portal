<div class="panel panel-default">
    <div class="panel-heading">Upcoming Mock Exams<div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div></div>
    <div class="panel-body"><div class="table-responsive">
            <table class="table table-striped table-bordered no-margin">
                <thead>
                    <tr>                                    
                        <th width="40">S/L</th>
                        <th>Name</th>
                        <th>Centre</th>                            
                        <th>Exam Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //dd( $exams );

                    foreach ($exams as $exam) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $sl++; ?></td>                               
                            <td><a href="<?= base_url('admin/exam/scenario/' . $exam->id); ?>">
                                    <?= "{$exam->name}, {$exam->label}"; ?>
                                    &nbsp;<i class="fa fa-external-link"></i>
                                </a>
                                <br/><em><?php echo multiDateFormat($exam->gmc_exam_dates); ?></em>
                            </td>
                            <td><?= $exam->centre; ?></td>                               
                            <td><?= globalDateTimeFormat($exam->datetime) . ' [' . dayLeftOfExam($exam->datetime) . "]"; ?></td>
                        </tr>                                                                
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <a href="<?php echo base_url('admin/exam?id=1'); ?>" class="btn btn-sm btn-default btn-flat pull-right">
                View All Exam
            </a>
        </div></div>
</div>