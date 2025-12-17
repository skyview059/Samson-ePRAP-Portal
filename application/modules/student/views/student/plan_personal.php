<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Personal Development Plan <small>for <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'development_plan') ?>">PDP</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'plan_personal'); ?>
    <div class="panel panel-default">
  <div class="panel-body">
      <h2><?php echo $student_name; ?></h2>
      <h3 class="no-margin">Personal Development Plan</h3>
      <?php echo $this->session->flashdata('message'); ?>
      <br>
    <?php if(!$plans){ ?>
                    <h3 class="box-title">No Plan Found!</h3>
                    <a href="admin/personal_dev_plan/create?id=<?php echo $id; ?>" class="btn btn-primary">
                        Setup Plan
                    </a>
      <?php } else { ?>            
            
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
            <?php } ?>
      <div class="box-footer text-center">
            <a href="admin/personal_dev_plan/update/<?= $id; ?>" class="btn btn-primary" target="_blank">
                <i class="fa fa-edit"></i>
                Update Plan in New Tab
            </a>
        </div>
  </div>
</div>

</section>