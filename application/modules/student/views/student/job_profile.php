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
  <div class="panel-body">
      <div class="row">
                <div class="col-md-12">

                    <table class="table table-striped">
                        <tr>
                            <td width="320">Internship</td>
                            <td><?php echo $internship; ?></td>
                        </tr>
                        <?php if($internship == 'Yes'): ?>
                            <tr>
                                <td>Internship Description</td>
                                <td><?php echo $internship_txt; ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Do you have any Specialty?</td>
                            <td><?php echo $specialty; ?></td>
                        </tr>
                        <tr>
                            <td>Specialty</td>
                            <td><?php echo getSpecialtyNameByStudentID($student_id); ?></td>
                        </tr>

                        <tr>
                            <td>Interests</td>
                            <td><?php echo getSpecialtyNameByIds($interest_ids); ?></td>
                        </tr>
                        <tr>
                            <td>UK Status</td>
                            <td><?php echo ($uk_status == 'Other')?$uk_status_other:$uk_status; ?></td>
                        </tr>
                        <tr>
                            <td>Do you have a right to work to work?</td>
                            <td><?php echo $right_to_work; ?></td>
                        </tr>
                        <tr>
                            <td>Which job are you interested in?</td>
                            <td><?php echo $job_interested; ?></td>
                        </tr>
                        <tr>
                            <td>Postgraduate professional qualifications</td>
                            <td><?php echo $professional_qualifications; ?></td>
                        </tr>
                        <tr>
                            <td>Training courses</td>
                            <td><?php echo ($training_courses == 'Other')?$training_courses_other:$training_courses; ?></td>
                        </tr>
                        <tr>
                            <td>Audit</td>
                            <td><?php echo $audit; ?></td>
                        </tr>
                        <tr>
                            <td>Research</td>
                            <td><?php echo $research; ?></td>
                        </tr>

                    </table>

                </div>

            </div>
  </div>
</div>
</section>