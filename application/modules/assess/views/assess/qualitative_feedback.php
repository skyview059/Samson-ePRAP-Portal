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

/*** SCA feedback statements ***/
.fb-domain { margin-bottom: 25px; }
.fb-domain > h2 { font-size: 20px; margin-bottom: 10px; }
.fb-standard { margin-bottom: 15px; }
.fb-standard ul { padding-left: 18px; margin-bottom: 0; }
.fb-statement-row { padding: 7px 0; border-bottom: 1px solid #f4f4f4; }
.fb-statement-row:last-child { border-bottom: 0; }
.fb-statement { font-weight: normal; margin: 0; cursor: pointer; }
.fb-statement input[type="checkbox"] { margin-right: 8px; }
.fb-statement .fb-no { color: #999; margin-right: 4px; }
.fb-notes-toggle { margin-left: 6px; font-size: 12px; white-space: nowrap; }
.fb-notes { display: none; margin: 8px 0 5px 24px; padding: 10px 15px; background: #f9f9f9; border-left: 3px solid #d2d6de; }
.fb-notes h3 { font-size: 14px; font-weight: bold; margin: 12px 0 5px; }
.fb-notes h3:first-child { margin-top: 0; }
.fb-notes p, .fb-notes li { font-size: 13px; }
.fb-notes ul { padding-left: 18px; }
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
            <h3 class="box-title">Feedback statements / <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="row">
                    <div class="col-md-12">

<!--                     
                    show this part if exam_type != 'SCA'
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
 -->


<?php if (empty($feedback_domains)): ?>
                        <div class="callout callout-warning">
                            <h4>No feedback statements available</h4>
                            <p>Import <code>DB/sca_feedback_statements.sql</code> to populate the SCA feedback domains and statements.</p>
                        </div>
<?php else: ?>
    <?php foreach ($feedback_domains as $domain): ?>
                        <div class="fb-domain">
                            <h2><?php echo html_escape($domain->name); ?></h2>

                        <?php if ( ! empty($domain->standard)): ?>
                            <div class="callout callout-info fb-standard">
                                <strong>Standard for marking</strong>
                                <?php echo $domain->standard; ?>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($domain->statements as $statement): ?>
                            <div class="fb-statement-row">
                                <label class="fb-statement" for="statement_<?php echo $statement->id; ?>">
                                    <input type="checkbox" name="feedback_statements[]" id="statement_<?php echo $statement->id; ?>" value="<?php echo $statement->id; ?>" <?php echo in_array($statement->id, $selected_statements) ? 'checked="checked"' : ''; ?>>
                                    <span class="fb-no"><?php echo $statement->sl_no; ?>.</span>
                                    <?php echo html_escape($statement->subject); ?>
                                </label>
                                <?php if ( ! empty($statement->description)): ?>
                                    <a class="fb-notes-toggle" href="javascript:void(0);" data-target="#statement_notes_<?php echo $statement->id; ?>"><i class="fa fa-info-circle"></i> notes</a>
                                    <div class="fb-notes" id="statement_notes_<?php echo $statement->id; ?>">
                                        <?php echo $statement->description; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        </div>
    <?php endforeach; ?>
<?php endif; ?>
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
<script>
    $(document).ready(function () {
        // Educator notes of a feedback statement (sca_feedback_statements.description)
        $(document).on('click', '.fb-notes-toggle', function () {
            $($(this).data('target')).slideToggle(150);
        });
    });
</script>



