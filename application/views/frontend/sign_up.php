<style type="text/css">
    /*old  day*/
    .datepicker table tr td.disabled,
    .datepicker table tr td.disabled:hover {
        background-color: #EEE;
        color: #CCC;
    }

    .error {
        font-size: 11pt;
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="row" style="background: linear-gradient(90deg, rgba(108,0,161,0.7) 0%, rgba(239,92,40,0.7) 100%);">
        <div class="col-md-12">
            <h1 class="text-center" style="text-transform: uppercase; padding: 40px 0; color: white; word-spacing: 3px">Sign Up For a Free Account</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!--<div class="col-md-2"><?php //echo validation_errors();  ?></div>-->
        <div class="col-md-10 col-md-offset-1" style="margin-top: 50px">
            <form action="<?= site_url('sign_up_action'); ?>" method="post" id="sign_up" class="form-horizontal"
                  enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">Basic Information</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="first_name" class="col-sm-4 control-label">Full Name <sup>*</sup></label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="title" class="form-control">
                                            <?php echo getNameTitle($title); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="fname" id="fname"
                                               placeholder="First Name" value="<?php echo $fname; ?>"/>
                                        <?php echo form_error('fname') ?>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="mname" id="mname"
                                               placeholder="Middle (Optional)" value="<?php echo $mname; ?>"/>
                                        <?php echo form_error('mname') ?>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="lname" id="lname"
                                               placeholder="Last Name" value="<?php echo $lname; ?>"/>
                                        <?php echo form_error('lname') ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-4 control-label">Gender <sup>*</sup></label>
                            <div class="col-sm-8" style="padding-top:8px;">
                                <?php echo htmlRadio('gender', $gender); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="occupation" class="col-sm-4 control-label">Occupation <sup>*</sup></label>
                            <div class="col-sm-8">
                                <select name="occupation" class="form-control" id="occupation">
                                    <option value="">--Select Occupation--</option>
                                    <?php echo getDropDownOccuptions($occupation); ?>
                                </select>
                                <?php echo form_error('occupation'); ?>
                            </div>
                        </div>

                        <div class="form-group" id="show_gmc_number">
                            <label for="gmc_number" class="col-sm-4 control-label">GMC/GDC/NMC Number<sup>*</sup></label>
                            <div class="col-sm-8">

                                <div class="input-group">
                            <span class="input-group-btn">
                                <select name="number_type" id="number_type" class="form-control" style="width: 150px">
                                    <option value="0">--Select--</option>
                                    <?php echo getNumberType($number_type); ?>
                                </select>
                            </span>

                                    <input type="text" class="form-control" name="gmc_number" id="gmc_number"
                                           placeholder="Enter Number (Optional)" minlength="7" maxlength="7"
                                           value="<?php echo $gmc_number; ?>"/>
                                </div>
                                <p id="help-block" class="help-block" style="font-size:11pt;"><em>If you put number, then it
                                        must be valid & 7 digit number</em></p>
                                <?php echo form_error('number_type') ?>
                                <?php echo form_error('gmc_number') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="your_email" class="col-sm-4 control-label">Email <sup>*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="email" id="your_email"
                                       placeholder="Email Address"
                                       value="<?php echo $email; ?>"/>
                                <?php echo form_error('email') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-sm-4 control-label">Phone Number<sup>*</sup></label>
                            <div class="col-sm-8">

                                <div class="input-group">
                            <span class="input-group-btn">
                                <select name="phone[code]" class="form-control" style="width: 190px">
                                    <?php echo getPhoneCode($phone_code); ?>
                                </select>
                            </span>

                                    <input type="text" class="form-control" name="phone[number]"
                                           id="phone" placeholder="77xxxxxx"
                                           minlength="11" maxlength="11"
                                           onkeypress="return DigitOnly(event);"
                                           value="<?php echo $phone; ?>"/>
                                </div>
                                <?php echo form_error('phone[code]') ?>
                                <?php echo form_error('phone[number]') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="whatsapp" class="col-sm-4 control-label">WhatsApp <sup>*</sup></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                            <span class="input-group-btn">
                                <select name="whatsapp[code]" class="form-control" style="width: 190px">
                                    <?php echo getPhoneCode($whatsapp_code); ?>
                                </select>
                            </span>
                                    <input type="tel" onKeyPress="return DigitOnly(event);"
                                           name="whatsapp[number]" id="whatsapp" placeholder="77xxxxxx"
                                           minlength="11" maxlength="11"
                                           class="form-control" value="<?php echo $whatsapp; ?>">
                                </div>
                                <?php echo form_error('whatsapp[code]'); ?>
                                <?php echo form_error('whatsapp[number]'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exam_id" class="col-sm-4 control-label">Purpose of Registration <sup>*</sup></label>
                            <div class="col-sm-8">
                                <select name="exam_id" class="form-control" id="exam_id">
                                    <?php echo getExamNameDropDownForFrontend($exam_id, 'Select'); ?>
                                </select>
                                <?php echo form_error('exam_id') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exam_date" class="col-sm-4 control-label">Exam Date <sup>*</sup></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                    <input type="text" class="form-control js_datepicker" name="exam_date"
                                           autocomplete="off" id="exam_date" placeholder="Exam Date"
                                           value="<?php echo $exam_date; ?>"/>
                                </div>
                                <?php echo form_error('exam_date'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Security Details</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="password" class="col-sm-4 control-label">Password <sup>*</sup></label>
                            <div class="col-sm-8">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="password" autocomplete="new-password" class="form-control"
                                               name="password" id="password" placeholder="Password"
                                               value="<?php echo $password; ?>"/>
                                        <div id="sign_up_respond_length"></div>

                                        <?php echo form_error('password') ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" autocomplete="new-password" class="form-control"
                                               name="passconf" id="passconf" placeholder="Confirm Password"/>
                                        <?php echo form_error('passconf') ?>
                                        <div id="sign_up_respond2"></div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <label for="secret_question_1" class="col-sm-4 control-label">Question 1 <sup>*</sup></label>
                            <div class="col-sm-8">
                                <select name="secret_question_1" class="form-control" id="secret_question_1">
                                    <?php echo getSecretQuestions($secret_question_1); ?>
                                </select>
                                <?php echo form_error('secret_question_1') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="answer_1" class="col-sm-4 control-label">Answer <sup>*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="answer_1"
                                       id="answer_1" value="<?php echo $answer_1; ?>"/>
                                <?php echo form_error('answer_1') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="answer" class="col-sm-4 control-label"><?php echo $math; ?> = ?</label>
                            <div class="col-sm-8">

                                <input type="text" class="form-control" name="answer"
                                       id="answer" placeholder="Enter Answer"
                                       onkeypress="return DigitOnly(event);"/>
                                <?php echo form_error('answer') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <label>
                                    <input type="checkbox" checked="checked" name="contact_by_rm" id="contact_by_rm"/>
                                    &nbsp;&nbsp;I want to be discovered and contacted by employers/recruitment managers
                                </label>
                                <p id="RM_Warning" style="display:none;" class="ajax_error">By un-check this option you will
                                    may miss opportunities for NHS jobs or any relevant information that might be helpful
                                    for your career.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <p>All personal data collected by <?= getSettingItem('SiteTitle'); ?>
                                    is governed by our privacy policy.
                                    By using this site you consent to
                                    use of personal data in accordance
                                    with the privacy policy.</p>


                                <label>
                                    <input type="checkbox" name="agree" value="Yes" id="agree"/>
                                    &nbsp;&nbsp;I have read and agreed to the
                                    <a target="_blank" href="https://www.samsoncourses.com/terms-and-conditions">Terms &
                                        Conditions</a> and
                                    <a target="_blank" href="https://www.samsoncourses.com/privacy-policy">Privacy
                                        Policy</a>.
                                </label>
                                <br>
                                <div id="agree_respond"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center" style="margin-bottom: 50px">
                    <a href="<?php echo site_url(); ?>" class="btn btn-lg btn-default">Back to Sign in</a>
                    <button type="button" class="btn btn-lg btn-primary" onclick="sign_up();">Sign Up</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">

    $('#contact_by_rm').on('change', function () {
        $('#RM_Warning').toggle();
    });

    function sign_up() {
        let error = 0;

        const fname = $('#fname').val();
        if (!fname) {
            $('#fname').addClass('required');
            error = 1;
        } else {
            $('#fname').removeClass('required');
        }

        const lname = $('#lname').val();
        if (!lname) {
            $('#lname').addClass('required');
            error = 1;
        } else {
            $('#lname').removeClass('required');
        }

        const your_email = $('#your_email').val();
        if (!your_email) {
            $('#your_email').addClass('required');
            $("#your_email_error").remove();
            $("#your_email").after("<span id='your_email_error' class=\"error\">Please enter a valid E-mail address.</span>");
            error = 1;
        } else {
            $('#your_email').removeClass('required');
            $("#your_email_error").empty();
        }

        const number_type = $('#number_type').val();
        if (number_type === '0') {
            $('#number_type').addClass('required');
            error = 1;
        } else {
            $('#number_type').removeClass('required');
        }

        const gmc_number = $('#gmc_number').val();
        if (gmc_number && gmc_number.length !== 7) {
            $('#gmc_number').addClass('required');
            $('.help-block').addClass('error');
            error = 1;
        } else {
            $('#gmc_number').removeClass('required').addClass('require_pass');
            $('#help-block').removeClass('error');
        }

        const exam_id = $('#exam_id').val();
        if (!exam_id) {
            $('#exam_id').addClass('required');
            error = 1;
        } else {
            $('#exam_id').removeClass('required');
        }

        const exam_date = $('#exam_date').val();
        if (!exam_date) {
            $('#exam_date').addClass('required');
            error = 1;
        } else {
            $('#exam_date').removeClass('required');
        }

        const phone = $('#phone').val();
        if (!phone) {
            $('#phone').addClass('required');
            error = 1;
        } else {
            $('#phone').removeClass('required');
        }

        const occupation = $('#occupation').val();
        if (!occupation) {
            $('#occupation').addClass('required');
            error = 1;
        } else {
            $('#occupation').removeClass('required');
        }

        const whatsapp = $('#whatsapp').val();
        if (!whatsapp) {
            $('#whatsapp').addClass('required');
            error = 1;
        } else {
            $('#whatsapp').removeClass('required');
        }

        const password = $('#password').val();
        if (!password) {
            $('#password').addClass('required');
            error = 1;
        } else {
            $('#password').removeClass('required');
        }

        const passconf = $('#passconf').val();
        if (!passconf) {
            $('#passconf').addClass('required');
            error = 1;
        } else {
            $('#passconf').removeClass('required');
        }

        if (password.length <= 5 || password.length >= 13) {
            $('#sign_up_respond_length').html('<span class="error">Password must be between 6 ~ 12 characters.</span>');
            error = 1;
        } else {
            $('#sign_up_respond_length').empty();
        }

        if (password !== passconf) {
            $('#sign_up_respond2').html('<span class="error">Confirm password not match...</span>');
            error = 1;
        }

        const secret_question_1 = $('#secret_question_1').val();
        if (!secret_question_1) {
            $('#secret_question_1').addClass('required');
            error = 1;
        } else {
            $('#secret_question_1').removeClass('required');
        }

        const answer_1 = $('#answer_1').val();
        if (!answer_1) {
            $('#answer_1').addClass('required');
            error = 1;
        } else {
            $('#answer_1').removeClass('required');
        }

        const answer = $('#answer').val();
        if (!answer) {
            $('#answer').addClass('required');
            error = 1;
        } else {
            $('#answer').removeClass('required');
        }

        const agree = $('input[name=agree]:checked').val();
        if (!agree) {
            $('#agree_respond').html('<p class="ajax_error">You must agree to the terms and conditions</p>');
            error = 1;
        } else {
            $('#agree_respond').html('');
        }

        if (error) {
            return false;
        } else {
            $("#sign_up").submit();
        }
    }
</script>