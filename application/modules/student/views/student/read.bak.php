<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Student <small>Read</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">student</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<section class="content">
    <?php echo studentTabs($id, 'read'); ?>
    <div class="box no-border">

        <div class="box-header with-border">
            <h3 class="box-title">Details View</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <td width="150">GMC Number</td>
                            <td width="5">:</td>
                            <td><?php echo $gmc_number; ?></td>
                        </tr>                        
                        <tr>
                            <td>First Name</td>
                            <td>:</td>
                            <td><?php echo $fname; ?></td>
                        </tr>
                        <tr>
                            <td>Middle Name</td>
                            <td>:</td>
                            <td><?php echo $mname; ?></td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td>:</td>
                            <td><?php echo $lname; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>:</td>
                            <td><?php echo '+'.$phone_code.'-'.$phone; ?></td>
                        </tr>
                        <tr>
                            <td>WhatsApp</td>
                            <td>:</td>
                            <td><?php echo '+'.$whatsapp_code.'-'.$whatsapp; ?></td>
                        </tr>
                        <tr>
                            <td>Ethnicity</td>
                            <td>:</td>
                            <td><?php echo $ethnicity; ?></td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>:</td>
                            <td><?php echo $country; ?></td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>:</td>
                            <td><?php echo $gender; ?></td>
                        </tr>                
                        <tr>
                            <td>Registered At</td>
                            <td>:</td>
                            <td><?php echo globalDateTimeFormat($created_at); ?></td>
                        </tr>
                        <tr>
                            <td>Updated on</td>
                            <td>:</td>
                            <td><?php echo globalDateTimeFormat($updated_at); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><a href="<?php echo site_url(Backend_URL . 'student') ?>" class="btn btn-default"><i
                                            class="fa fa-long-arrow-left"></i> Back</a><a
                                        href="<?php echo site_url(Backend_URL . 'student/update/' . $id) ?>"
                                        class="btn btn-primary"> <i class="fa fa-edit"></i> Edit</a></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-3">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th width="120">Exam Name</th>
                            <th width="120">Centre</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <td>PLAB 1</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>PLAB 2</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>ORE 1</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>ORE 2</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>NMC</td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                        
                    </table>
                </div>
                <div class="col-md-3">
                    <img class="img-responsive img-bordered" src="<?php echo getPhoto($photo, $fname, 250, 350); ?>" width="250" alt="">
                </div>
            </div>
            
        </div>
    </div>
</section>