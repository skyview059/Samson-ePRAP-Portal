<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Assess <small>Review</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Review</li>
    </ol>
</section>
<style>
    .cc-selector input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
    }

    .smiley{background-image:url(assets/admin/icons/smiley.png);}
    .neutral{background-image:url(assets/admin/icons/neutral.png);}
    .sad{background-image:url(assets/admin/icons/sad.png);}
    .smiley, .neutral, .sad{ background-position: center center; }

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
    
    .panel h4{
        width: 100%;
    }
   
    a.accordion-toggle{
        width: 100%;
        display: block;
    }
    
    /***************Qualitative Feedback*******************/
    .checkbox label:after {
        content: '';
        display: table;
        clear: both;
      }

      .checkbox .cr {
        position: relative;
        display: inline-block;
        border: 1px solid #a9a9a9;
        border-radius: .25em;
        width: 1.3em;
        height: 1.3em;
        float: left;
        margin-right: .5em;
      }

      .checkbox .cr .cr-icon {
        position: absolute;
        font-size: .8em;
        line-height: 0;
        top: 50%;
        left: 15%;
      }

      .checkbox label input[type="checkbox"] {
        display: none;
      }

      .checkbox label input[type="checkbox"]+.cr>.cr-icon {
        opacity: 0;
      }

      .checkbox label input[type="checkbox"]:checked+.cr>.cr-icon {
        opacity: 1;
      }

      .checkbox label input[type="checkbox"]:disabled+.cr {
        opacity: .5;
      }
</style>

<section class="content">
    <div class="panel panel-default">
  <div class="panel-heading">Review Before Complete / <?= $summery_std_scen; ?></div>
  <div class="panel-body">
      <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-group panel-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">
                                <div class="box-header with-border panel-heading" role="tab" id="headingOne">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="more-less glyphicon glyphicon-minus"></i>
                                            Initial Approach
					</a>
                                    </h4>
                                </div>
                                <div id="collapseOne"  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-8 col-md-offset-3">
                                                <div class="form-group">
                                                    <label for="patient_name" class="col-sm-3 control-label">Identifies Patient :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('patient_name', $patient_name, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('patient_name') ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="greet_the_patient" class="col-sm-3 control-label">Greet the patient :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('greet_the_patient', $greet_the_patient, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('greet_the_patient') ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="introduces_himself" class="col-sm-3 control-label">Introduces himself :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('introduces_himself', $introduces_himself, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('introduces_himself') ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="state_the_role" class="col-sm-3 control-label">State the role :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('state_the_role', $state_the_role, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('state_the_role') ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="name_preference" class="col-sm-3 control-label">Checks patient’s name preference :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('name_preference', $name_preference, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('name_preference') ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="starts_station_well" class="col-sm-3 control-label">Starts station well :</label>
                                                    <div class="col-sm-9" style="padding-top:8px;">
                                                        <?php echo htmlRadio('starts_station_well', $starts_station_well, array('Yes' => 'Yes', 'No' => 'No'), 'class="icheck-radio"'); ?>
                                                        <div class="clearfix"></div>
                                                        <?php echo form_error('starts_station_well') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Face
					</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            
                                            <div class="text-center">
                                                <div class="cc-selector">
                                                    <table class="table no-border" style="width:450px;margin: 0 auto;">
                                                        <tr>
                                                            <td class="no-padding">
                                                                <input id="smiley" type="radio" name="face" value="Smiley" <?php echo $face=='Smiley' ? 'checked="checked"' : '';?>/>
                                                                <label class="drinkcard-cc smiley" for="smiley" title="Smiley"></label>
                                                            </td>
                                                            <td class="no-padding">
                                                                <input id="neutral" type="radio" name="face" value="No Emotions" <?php echo $face=='No Emotions' ? 'checked="checked"' : '';?> />
                                                                <label class="drinkcard-cc neutral" for="neutral" title="No Emotions"></label>
                                                            </td>
                                                            <td class="no-padding">
                                                                <input id="sad" type="radio" name="face" value="Very Sad" <?php echo $face=='Very Sad' ? 'checked="checked"' : '';?>/>
                                                                <label class="drinkcard-cc sad" for="sad" title="Very Sad"></label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-bold no-padding">Smiley</td>
                                                            <td class="text-bold no-padding">No Emotions</td>
                                                            <td class="text-bold no-padding">Very Sad</td>
                                                        </tr>
                                                    </table>
                                            
                                                    
                                                    <div class="clearfix"></div>
                                                    <?php echo form_error('face') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Quantitative Feedback
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th> Station </th>
                                                            <th class="col-md-4">Data-gathering, technical and assessment skills</th>
                                                            <th class="col-md-2">Clinical management Skills</th>
                                                            <th class="col-md-2">Interpersonal Skills</th>
                                                            <th class="col-md-1">Total Mark </th>
                                                        </tr>

                                                        <tr>
                                                            <td><?= $result_details->scenario_name; ?></td>
                                                            <td>
                                                                <?php echo htmlRadio('technical_skills', $technical_skills, array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4), 'class="icheck-radio sumTotal"'); ?>
                                                                <br/><?php echo form_error('technical_skills') ?>
                                                            </td>
                                                            <td>
                                                                <?php echo htmlRadio('clinical_skills', $clinical_skills, array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4), 'class="icheck-radio sumTotal"'); ?>
                                                                <?php echo form_error('clinical_skills') ?>
                                                            </td>
                                                            <td>
                                                                <?php echo htmlRadio('interpersonal_skills', $interpersonal_skills, array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4), 'class="icheck-radio sumTotal"'); ?>
                                                                <?php echo form_error('interpersonal_skills') ?>
                                                            </td>
                                                            <td class="text-center"><span class="badge bg-yellow" id="total_mark"><?= $technical_skills+$clinical_skills+$interpersonal_skills ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Qualitative Feedback
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse">
                                    <div class="box-body">
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
                                                            <td> <?= $result_details->scenario_name?> </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="consultation" id="consultation" class="form-control" value="1" <?php echo ($consultation) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="issues" id="issues" class="form-control" value="1" <?php echo ($issues) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="diagnosis" id="diagnosis" class="form-control" value="1" <?php echo ($diagnosis) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="examination" id="examination" class="form-control" value="1" <?php echo ($examination) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="findings" id="findings" class="form-control" value="1" <?php echo ($findings) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="management" id="management" class="form-control" value="1" <?php echo ($management) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="rapport" id="rapport" class="form-control" value="1" <?php echo ($rapport) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="listening" id="listening" class="form-control" value="1" <?php echo ($listening) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="language" id="language" class="form-control" value="1" <?php echo ($language) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>

                                                            </td>
                                                            <td class="text-center">
                                                                <div class="checkbox">
                                                                  <label>
                                                                      <input type="checkbox" name="time" id="time" class="form-control" value="1" <?php echo ($time) ? 'checked="checked"' : '';?>>
                                                                      <span class="cr"><i class="cr-icon glyphicon glyphicon-remove"></i></span>
                                                                   </label>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Examiner's Overall Judgement
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="overall_judgment" class="col-sm-4 control-label">Overall Judgment: </label>
                                                    <div class="col-sm-8" style="padding-top:8px;">
                                                        
                                                        <?php 
                                                            echo htmlRadio('overall_judgment', $overall_judgment, array(
                                                            'Fail' => 'Fail',
                                                            'Borderline' => 'Borderline',
                                                            'Pass' => 'Pass',
                                                            'Very Good' => 'Very Good',
                                                            'Excellent' => 'Excellent'
                                                            ), 'class="icheck-radio"');?>
                                                        
                                                        
                                                        <?php echo form_error('overall_judgment') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border panel-heading">
                                    <h4 class="box-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Examiner’s comments
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <?php
                                        $options = array(
                                            'name' => 'comments',
                                            'id' => 'comments',
                                            'class' => 'form-control',
                                            // 'readonly' => 'readonly',
                                            'rows' => '10',
                                            'cols' => '50',
                                            'value' => $comments
                                        );
                                        echo form_textarea($options);
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit & Close Review</button>
                    </div>
                </div>
            </form>
  </div>
</div>
</section>
<script>
    $(document).ready(function (){
        
        $('input[type=radio][name=technical_skills], input[type=radio][name=clinical_skills], input[type=radio][name=interpersonal_skills]').on('ifChecked', function(event){
            var total_mark = 0;
            var t_skill_mark = $("input[name=technical_skills]:checked").val();
            var c_skill_mark = $("input[name=clinical_skills]:checked").val();
            var i_skill_mark = $("input[name=interpersonal_skills]:checked").val();
            
            if(t_skill_mark!==undefined){
                total_mark+=Number(t_skill_mark);
            }
            if(c_skill_mark!==undefined){
                total_mark+=Number(c_skill_mark);
            }
            if(i_skill_mark!==undefined){
                total_mark+=Number(i_skill_mark);
            }
            $('#total_mark').html(total_mark);            
        });        
    });
    
    /*******************************
    * ACCORDION WITH TOGGLE ICONS
    ******************************
    */
   
    function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
    
</script>
