<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<style type="text/css">
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
<section class="content-header">
    <h1>Examine<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>examine">Examine</a></li>
        <li class="active">Quantitative Feedback</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Qualitative Feedback / <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
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
                                    <td><?= $result_details->scenario_name; ?> </td>
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
                    <div class="col-md-12 text-center" style="padding-top:20px;">
                        <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save &amp; Continue <i class="fa fa-long-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
