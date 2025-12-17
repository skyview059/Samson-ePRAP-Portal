<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Personal Development Plan  <small>Read</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'personal_dev_plan') ?>">Personal Development Plan</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo personal_dev_planTabs($id, 'details'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="text-center"><b class="text-red"><?php echo $student_name; ?></b></h3>
            <h3 class="no-margin text-center ">Personal Development Plan</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="15%">Domain</th>
                        <th width="17.5%">What specific development needs do I have?</th>
                        <th width="17.5%">How will theses development needs be achieved?</th>
                        <th width="17.5%">How will make sure I have achieved and became competent?</th>
                        <th width="15%">Timescale</th>
                        <th width="17.5%">Evaluation and outcome?</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($plans as $p) { ?>
                    <tr>
                        <th><?php echo $p->domain; ?></th>
                        <td><?php echo $p->specific_development; ?></td>
                        <td><?php echo $p->theses_development; ?></td>
                        <td><?php echo $p->i_have_achieved; ?></td>
                        <td><?php echo $p->timescale; ?></td>
                        <td><?php echo $p->evaluation_and_outcome; ?></td>
                    </tr>
                <?php } ?>
                    
                </tbody>
            </table>
        </div>                    
    </div>
</section>