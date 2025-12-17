<?php echo getStudentProcessBar(); ?>


<div class="row">
    <div class="col-md-8">
        <div class="page-title">
            <h3>My Job Profile</h3>
        </div>
    </div>
    <div class="col-md-4 text-right">
        <a class="btn btn-primary" href="<?php echo site_url('job-profile/update'); ?>"><i class="fa fa-edit"></i>
            Update Job Profile</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-hover">
            <tr>
                <td width="350">Internship</td>
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
                <td>Which specialty are you interested in?</td>
                <td><?php echo getSpecialtyNameByIds($interest_ids); ?></td>
            </tr>
            <tr>
                <td>UK Status</td>
                <td><?php echo ($uk_status == 'Other') ? $uk_status_other : $uk_status; ?></td>
            </tr>
            <tr>
                <td>Do you have a right to work in the UK?</td>
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
                <td><?php echo ($training_courses == 'Other') ? $training_courses_other : $training_courses; ?></td>
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