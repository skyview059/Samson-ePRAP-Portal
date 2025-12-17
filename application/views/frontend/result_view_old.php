<?php load_module_asset('message', 'css'); ?>
<style type="text/css">
    .bs-example {
        margin: 20px;
    }
    .rotate {
        -webkit-transform: rotate(-180deg); /* Chrome, Safari, Opera */
        -moz-transform: rotate(-180deg); /* Firefox */
        -ms-transform: rotate(-180deg); /* IE 9 */
        transform: rotate(-180deg); /* Standard syntax */
    }

    /******/
    .cc-selector input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
    }

    .smiley{background-image:url(assets/admin/icons/smiley.png);}
    .sad{background-image:url(assets/admin/icons/sad.png);}
    .very_sad{background-image:url(assets/admin/icons/very_sad.png);}

    .drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
    .drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
        -webkit-filter: none;
        -moz-filter: none;
        filter: none;
    }
    .drinkcard-cc{
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:200px;
        height:140px;
        -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
        transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(.7) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(.7) opacity(.7);
        filter: brightness(1.8) grayscale(.7) opacity(.7);
    }
    .drinkcard-cc:hover{
        -webkit-filter: brightness(1.2) grayscale(.3) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.3) opacity(.9);
        filter: brightness(1.2) grayscale(.3) opacity(.9);
    }
</style>
<div class="box">


    <div class="page-title">
        <h3 class="box-title">View Result</h3>
    </div>

    <div class="box-body">

        <div class="row">
            <div class="col-md-12 text-center">
                <address>
                    <strong>PLAB Part 2</strong><br> 
                    Center: Manchester<br>
                    Exam Date & Time : 06/06/2020 02:00PM<br>
                    Examiner's Overall Judgement : <button type="button" class="btn btn-success">Pass</button><br>
                </address>
            </div>

            <div class="col-md-12">
                <div class="bs-example">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-chevron-down"></span> Initial Approach</a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-2">
                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">Name of the patient :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('patient_name', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">Greet the patient :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('greet_the_patient', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">Introduces himself :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('introduces_himself', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">State the role :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('state_the_role', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">Checks patient’s name preference :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('name_preference', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 control-label">Starts station well :</label>
                                                <div class="col-sm-7">
                                                    <?php echo htmlRadio('starts_station_well', null, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-chevron-down"></span> Face</a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="cc-selector">
                                                <input id="smiley" type="radio" name="credit-card" value="smiley" />
                                                <label class="drinkcard-cc smiley" for="smiley" title="Smiley"></label>
<!--                                                <input id="sad" type="radio" name="credit-card" value="sad" />
                                                <label class="drinkcard-cc sad" for="sad" title="Sad"></label>

                                                <input id="very_sad" type="radio" name="credit-card" value="very_sad" />
                                                <label class="drinkcard-cc very_sad" for="very_sad" title="Very Sad"></label>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-chevron-down"></span> Quantitative Feedback 01</a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th> Station </th>
                                                        <th class="col-md-4">Data-gathering, technical and assessment skills</th>
                                                        <th class="col-md-2">Clinical management Skills</th>
                                                        <th class="col-md-2">Interpersonal Skills</th>
                                                        <th class="col-md-2">Total Mark </th>
                                                    </tr>

                                                    <tr>
                                                        <td>Head injury in an adult</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>4</td>
                                                        <td class="text-center">11</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-chevron-down"></span> Quantitative Feedback 02</a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="col-md-2"> Station </th>
                                                        <th class="text-center">Consultation</th>
                                                        <th class="text-center">Issues</th>
                                                        <th class="text-center">Diagnosis</th>
                                                        <th class="text-center">Examination</th>
                                                        <th class="text-center">Findings</th>
                                                        <th class="text-center">Management</th>
                                                        <th class="text-center">Rapport</th>
                                                        <th class="text-center">Listening</th>
                                                        <th class="text-center">Language</th>
                                                        <th class="text-center">Time</th>
                                                    </tr>

                                                    <tr>
                                                        <td>Head injury in an adult</td>
                                                        <td class="text-center">

                                                            <?php
                                                            $data = array(
                                                                'name' => 'consultation',
                                                                'id' => 'consultation',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'issues',
                                                                'id' => 'issues',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'diagnosis',
                                                                'id' => 'diagnosis',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'examination',
                                                                'id' => 'examination',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'findings',
                                                                'id' => 'findings',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'management',
                                                                'id' => 'management',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'rapport',
                                                                'id' => 'rapport',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'listening',
                                                                'id' => 'listening',
                                                                'value' => '1',
                                                                'checked' => TRUE,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'language',
                                                                'id' => 'language',
                                                                'value' => '1',
                                                                'checked' => false,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $data = array(
                                                                'name' => 'time',
                                                                'id' => 'time',
                                                                'value' => '1',
                                                                'checked' => false,
                                                                'class' => 'icheckbox form-control',
                                                                'style' => 'margin:10px',
                                                            );

                                                            echo form_checkbox($data);
                                                            ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p><strong>Note:</strong> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
        </div>

    </div>
</div> 
<script>
    $(document).ready(function () {
        // Add minus icon for collapse element which is open by default
        $(".collapse.in").each(function () {
            $(this)
                    .siblings(".panel-heading")
                    .find(".glyphicon")
                    .addClass("rotate");
        });

        // Toggle plus minus icon on show hide of collapse element
        $(".collapse")
                .on("show.bs.collapse", function () {
                    $(this)
                            .parent()
                            .find(".glyphicon")
                            .addClass("rotate");
                })
                .on("hide.bs.collapse", function () {
                    $(this)
                            .parent()
                            .find(".glyphicon")
                            .removeClass("rotate");
                });
    });

</script>