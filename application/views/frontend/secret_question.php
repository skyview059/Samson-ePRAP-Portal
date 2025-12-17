<div class="container" >
    <div class="col-md-5 col-md-offset-4">
        <div class="login-box-custom" style="margin-top: 50px;"> 
            <div class="login-box">               
                <div class="login-box-body">
                    <form id="secret_question_verify" action="<?php echo $action;?>" method="post" class="form-horizontal">

                        <h3 class="text-center">Account recovery</h3>
                        <input type="hidden" name="verify_token" value="<?php echo $verify_token; ?>" >
                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                                <?php echo form_error('email') ?>
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
                                <input type="text" class="form-control" name="answer_1" id="answer_1"  value="<?php echo $answer_1; ?>"/>
                                <?php echo form_error('answer_1') ?>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="secret_question_2" class="col-sm-4 control-label">Question 2 <sup>*</sup></label>
                            <div class="col-sm-8">
                                <select name="secret_question_2" class="form-control" id="secret_question_2">
                                    <?php echo getSecretQuestions($secret_question_2); ?>
                                </select>
                                <?php echo form_error('secret_question_2') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="answer_2" class="col-sm-4 control-label">Answer <sup>*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="answer_2" id="answer_2"   value="<?php echo $answer_2; ?>"/>
                                <?php echo form_error('answer_2') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4"></label>
                            <div class="col-sm-8">
                                <button type="button" id="secret_question"  class="btn btn-primary btn-block btn-flat">Next</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <!-- /.login-box-body -->
            </div>
        </div>
    </div>
</div>


<script>


    var $ = jQuery;
    $('#secret_question').on('click', function () {

        $('.validation_error').html('');

        var error = 0;
        
        var secret_question_1 = $('#secret_question_1').val();
        if (!secret_question_1) {
            $('#secret_question_1').addClass('required');
            $('#secret_question_1').closest('.col-sm-8').append('<em class="validation_error">Please, question 1 must be selected</em>');
            error = 1;
        } else {
            $('#secret_question_1').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        
        var answer_1 = $('#answer_1').val();
        if (!answer_1) {
            $('#answer_1').addClass('required');
            $('#answer_1').closest('.col-sm-8').append('<em class="validation_error">Please enter verify answer 1</em>');
            error = 1;
        } else {
            $('#answer_1').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        
        var secret_question_2 = $('#secret_question_2').val();
        if (!secret_question_2) {
            $('#secret_question_2').addClass('required');
            $('#secret_question_2').closest('.col-sm-8').append('<em class="validation_error">Please, question 2 must be selected</em>');
            error = 1;
        } else {
            $('#secret_question_2').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        
        var answer_2 = $('#answer_2').val();
        if (!answer_2) {
            $('#answer_2').addClass('required');
            $('#answer_2').closest('.col-sm-8').append('<em class="validation_error">Please enter verify answer 2</em>');
            error = 1;
        } else {
            $('#answer_2').css('border', '1px solid #999').css('background-color', '#FFF');
        }
        
        if (error) {
            return false;
        } else {
            $("#secret_question_verify").submit();
        }
    });
</script>

