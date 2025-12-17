<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('student', 'css'); ?>
<section class="content-header">
    <h1>Job Profile <small>of <b><?php echo $student_name; ?></b></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">student</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo studentTabs($id, 'job_profile'); ?>
    <div class="panel panel-default">
  <div class="panel-body"><h2 class="text-red">This candidate has not set up the job profile</h2>  </div>
</div>

</section>