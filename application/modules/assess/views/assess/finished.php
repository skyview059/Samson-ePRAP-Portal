<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Examine Finished</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Examine Finished</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Examine Finished</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">
                        <a href="<?= $next_candidate_action; ?>" class="btn btn-lg bg-olive btn-flat margin"><i class="fa fa-chevron-right"></i> Next Candidate</a>
                        <a href="<?= $end_exam_action; ?>" class="btn btn-lg bg-purple btn-flat margin"><i class="fa fa-arrow-circle-o-up"></i> End Exam</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
