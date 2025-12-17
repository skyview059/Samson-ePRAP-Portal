<?php echo getStudentProcessBar(); ?>
<h3>My Profile</h3>

<div class="row">
    <div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">Exam Details</div>
            <div class="panel-body">
                <div style="border: 1px solid #198bcc;
                     background-color: #f8fcff;
                     padding: 25px;
                     text-align: center;">
                    <p style="font-size: 15pt;">
                        Exam: <b class="text-bold"><?= $exam_name; ?></b>,
                        Centre: <b class="text-bold"><?= $exam_centre; ?></b> &
                        Date: <b class="text-bold"><?= globalDateFormat($exam_date); ?></b>
                    </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Basic Information</div>
            <div class="panel-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td class="text-left" width="250"><b>Full Name:</b></td>
                        <td><?php echo $title . ' ' . $fname . ' ' . $mname . ' ' . $lname; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Gender:</b></td>
                        <td><?php echo $gender; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b><?php echo $number_type . ' Number'; ?>:</b></td>
                        <td><?php echo $gmc_number; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Email:</b></td>
                        <td><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Phone:</b></td>
                        <td><?php echo "+{$phone_code}{$phone}"; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>WhatsApp:</b></td>
                        <td><?php echo "+{$whatsapp_code}{$whatsapp}"; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Ethnicity:</b></td>
                        <td><?php echo $ethnicity_name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Occupation:</b></td>
                        <td><?php echo $occupation; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Purpose of Registration:</b></td>
                        <td><?php echo $purpose_of_registration; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>
                                <?php if($contact_by_rm == 'Yes'){ ?>
                                    I want to be discovered and contacted by employers/recruitment managers.
                                <?php } elseif($contact_by_rm == 'Yes') {?>
                                    I want to be discovered and contacted by employers/recruitment managers.
                                <?php } ?>
                            </b>
                        </td>                        
                    </tr>
                </table></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Address</div>
            <div class="panel-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td class="text-left"  width="250"><b>Address Line 1:</b></td>
                        <td><?php echo $address_line1; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Address Line2:</b></td>
                        <td><?php echo $address_line2; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Postcode:</b></td>
                        <td><?php echo $postcode; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Country of Origin:</b></td>
                        <td><?php echo $country_name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Current Location:</b></td>
                        <td><?php echo $present_country_name; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Security Question</div>
            <div class="panel-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td class="text-left"  width="250"><b>Question 1:</b></td>
                        <td><?php echo $secret_question_1_name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Answer 1:</b></td>
                        <td><?php echo $answer_1; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Question 2:</b></td>
                        <td><?php echo $secret_question_2_name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"><b>Answer 2:</b></td>
                        <td><?php echo $answer_2; ?></td>
                    </tr>
                    
                </table>
            </div>
        </div> 

    </div>
    <div class="col-sm-4 text-center">
        <div class="panel panel-default">
            <div class="panel-body"><div class="box-header text-center">
                    <?php echo getPhoto_v3($photo, $gender, $fname, 250, 250); ?>
                </div>

                <p><br/>
                    <a class="btn btn-primary" href="<?php echo site_url('profile/update'); ?>">
                        <i class="fa fa-edit"></i> 
                        Update Profile
                    </a>
                </p>
            </div>


        </div>
    </div>
</div>