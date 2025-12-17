<div class="container-fluid">
    <div class="text-center">
        <h1><?= $exam_schedule->label; ?></h1>
        <h4><?= globalDateTimeFormat($exam_schedule->datetime); ?></h4>
        <p>Instructions here</p>

        <a class="btn btn-primary" href="<?= site_url('mock/exam-room/' . $exam_schedule->id.'/practice'); ?>">Start <i class="fa fa-arrow-right"></i> </a>
    </div>
</div>