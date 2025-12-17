<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('student', 'css'); ?>
    <section class="content-header">
        <h1>Student <small>Profile of</small> <?= $full_name; ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="<?php echo site_url(Backend_URL . 'student') ?>">student</a></li>
            <li class="active">Details</li>
        </ol>
    </section>

    <section class="content personaldevelopment studenttab">
        <?php echo studentTabs($id, 'read'); ?>
        <div class="panel panel-default">
            <div class="panel-body"><div class="row">
                    <div class="col-md-8">
                            <legend>Exam Details</legend>
                            <div style="border: 1px solid #198bcc;
    background-color: #f8fcff;
    padding: 25px;
    text-align: center;">
                                <p style="font-size: 15pt;">
                                    Exam: <b class="text-bold"><?= $exam_name; ?></b>,
                                    Centre: <b class="text-bold"><?= $exam_centre; ?></b> &
                                    Date: <b class="text-bold"><?= $exam_date; ?></b>
                                </p>
                            </div>


                            <legend>View Note</legend>
                            <?php echo nl2br_fk($note); ?>


                            <legend>Basic Information</legend>
                            <table class="table table-striped">
                                <tr>
                                    <td width="200"><?php echo $number_type . ' Number' ?></td>
                                    <td width="5">:</td>
                                    <td><?php echo $gmc_number; ?></td>
                                </tr>
                                <tr>
                                    <td>Full Name</td>
                                    <td>:</td>
                                    <td><?php echo $title . ' ' . $fname . ' ' . $mname . ' ' . $lname; ?></td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>:</td>
                                    <td><?php echo $gender; ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td><?php echo $email; ?></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>:</td>
                                    <td><?= $phone; ?></td>
                                </tr>
                                <tr>
                                    <td>WhatsApp</td>
                                    <td>:</td>
                                    <td><?= $whatsapp; ?></td>
                                </tr>
                                <tr>
                                    <td>Ethnicity</td>
                                    <td>:</td>
                                    <td><?php echo $ethnicity; ?></td>
                                </tr>
                                <tr>
                                    <td>Occupation</td>
                                    <td>:</td>
                                    <td><?php echo $occupation; ?></td>
                                </tr>
                                <tr>
                                    <td>Purpose of Registration</td>
                                    <td>:</td>
                                    <td><?php echo $purpose_of_registration; ?></td>
                                </tr>
                                <tr>
                                    <td>Registered At</td>
                                    <td>:</td>
                                    <td><?= globalDateTimeFormat($created_at); ?></td>
                                </tr>
                                <tr>
                                    <td>Updated on</td>
                                    <td>:</td>
                                    <td><?= globalDateTimeFormat($updated_at); ?></td>
                                </tr>

                            </table>

                            <legend>Address</legend>
                            <table class="table table-striped">
                                <tr>
                                    <td width="200">Address Line1</td>
                                    <td width="5">:</td>
                                    <td><?php echo $address_line1; ?></td>
                                </tr>
                                <tr>
                                    <td>Address Line2</td>
                                    <td>:</td>
                                    <td><?php echo $address_line2; ?></td>
                                </tr>
                                <tr>
                                    <td>Postcode</td>
                                    <td>:</td>
                                    <td><?php echo $postcode; ?></td>
                                </tr>
                                <tr>
                                    <td>Country of Origin</td>
                                    <td>:</td>
                                    <td><?php echo $country_of_origin; ?></td>
                                </tr>
                                <tr>
                                    <td>Current Location</td>
                                    <td>:</td>
                                    <td><?php echo $current_location; ?></td>
                                </tr>
                            </table>
                    </div>


                    <div class="col-md-4 text-center">
                        <div class="studemtadminright" style="background-color: #f3f4f6; border-radius: 4px; margin-top: 30px;padding: 15px;">
                            <h3><?= $full_name; ?></h3>
                        <?php echo getPhoto_v3($photo, $gender, $lname, 250, 250); ?>

                        <p>&nbsp;</p>

                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-compose-message">
                            <i class="fa fa-envelope"></i> Send Message
                        </button>
                        <?php /*
                    <img class="img-responsive img-bordered"
                         src="<?php echo getPhoto($photo, $fname, 250, 350); ?>"
                         width="250" alt="">
                     *
                     */ ?>
                    </div>
                    </div>
                </div></div>
        </div>
    </section>


<?php $this->load->view('message/message/new_message_modal', ['id' => $id, 'subject' => 'subject']); ?>