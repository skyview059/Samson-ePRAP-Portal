<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Job <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'job') ?>">Job</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo jobTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="box-title">Details View</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <table class="table table-striped">
            <tr>
                <td width="150">User</td>
                <td width="5">:</td>
                <td><?php echo $user_id; ?></td>
            </tr>
            <tr>
                <td width="150">Post Title</td>
                <td width="5">:</td>
                <td><?php echo $post_title; ?></td>
            </tr>
            <tr>
                <td width="150">Job Type</td>
                <td width="5">:</td>
                <td><?php echo $job_type; ?></td>
            </tr>
            <tr>
                <td width="150">Description</td>
                <td width="5">:</td>
                <td><?php echo $description; ?></td>
            </tr>
            <tr>
                <td width="150">Salary(Hourly Rate)</td>
                <td width="5">:</td>
                <td><?php echo (!empty($hourly_rate)) ? '<i class="fa fa-gbp"></i>' . $hourly_rate : null; ?></td>
            </tr>
            <tr>
                <td width="150">Location</td>
                <td width="5">:</td>
                <td><?php echo $location; ?></td>
            </tr>
            <tr>
                <td width="150">Vacancy</td>
                <td width="5">:</td>
                <td><?php echo $vacancy; ?></td>
            </tr>
            <tr>
                <td width="150">Deadline</td>
                <td width="5">:</td>
                <td><?php echo $deadline; ?></td>
            </tr>
            <tr>
                <td width="150">Skills</td>
                <td width="5">:</td>
                <td><?php echo $skills; ?></td>
            </tr>
            <tr>
                <td width="150">Benefit</td>
                <td width="5">:</td>
                <td><?php echo $benefit; ?></td>
            </tr>
            <tr>
                <td width="150">Hit</td>
                <td width="5">:</td>
                <td><?php echo $hit; ?></td>
            </tr>
            <tr>
                <td width="150">Status</td>
                <td width="5">:</td>
                <td><?php echo $status; ?></td>
            </tr>
            <tr>
                <td width="150">Service Hour</td>
                <td width="5">:</td>
                <td><?php echo $service_hour; ?></td>
            </tr>
            <tr>
                <td width="150">Featured</td>
                <td width="5">:</td>
                <td><?php echo $featured; ?></td>
            </tr>
            <tr>
                <td width="150">Created At</td>
                <td width="5">:</td>
                <td><?php echo $created_at; ?></td>
            </tr>
            <tr>
                <td width="150">Updated At</td>
                <td width="5">:</td>
                <td><?php echo $updated_at; ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><a href="<?php echo site_url(Backend_URL . 'job') ?>" class="btn btn-default"><i
                                class="fa fa-long-arrow-left"></i> Back</a><a
                            href="<?php echo site_url(Backend_URL . 'job/update/' . $id) ?>" class="btn btn-primary"> <i
                                class="fa fa-edit"></i> Edit</a></td>
            </tr>
        </table>
    </div>
</section>