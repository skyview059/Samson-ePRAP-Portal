<?php
defined('BASEPATH') OR exit('No direct script access allowed');

load_module_asset('users', 'css');
load_module_asset('users', 'js'); 

?>

<section class="content-header">
    <h1> User <small> Assigned Student </small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li><a href="<?php echo Backend_URL . 'users'; ?>">User</a></li>        
        <li class="active">Assigned Student</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab">
    <?php echo Users_helper::makeTab($id, 'student'); ?>
    <div class="box no-border">

        
        <div class="box-body">

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                        
                            <th width="50">S/L</th>
                            <th width="80">Photo</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th width="50">Type</th>
                            <th width="100">Number</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th width="140">Register At</th>
                            <th class="text-center" width="80">Status</th>
                            <th class="text-center" width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student) {  ?>
                        <tr>                            
                            <td><?php echo ++$start; ?></td>
                            <td><img src="<?php echo getPhoto($student->photo, $student->fname, 60, 60); ?>" width="60" alt="Photo"></td>                            
                            <td><?php echo $student->fname; ?></td>
                            <td><?php echo $student->lname; ?></td>
                            <td><?php echo $student->number_type; ?></td>
                            <td><?php echo $student->gmc_number; ?></td>
                            <td><?php echo $student->email; ?></td>
                            <td><?php echo $student->phone; ?></td>
                            <td><?php echo globalDateTimeFormat($student->created_at); ?></td>
                            <td class="text-center" ><?php echo getstudentStatus($student->status,$student->id, false); ?></td>
                            <td class="text-center">
                                <?php
                                    echo anchor(
                                        site_url(Backend_URL . 'student/read/' . $student->id), 
                                        '<i class="fa fa-fw fa-external-link"></i> Preview', 
                                        'class="btn btn-xs btn-primary"'
                                    );                                
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="padding-top: 10px; padding-bottom: 10px; margin: 0;">
            <div class="col-md-6">
                <span class="btn btn-primary">Total Record : <?php echo $total ?></span>
            </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</section>    