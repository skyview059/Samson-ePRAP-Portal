<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1><?php echo getExamName($id); ?> Scenarios <small></small> 
        <?php echo  anchor(
                    site_url(Backend_URL . "scenario/create?id={$id}"), 
                    '<i class="fa fa-plus"></i> Add New Scenario', 
                    'class="btn btn-primary"'
                );
            ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Scenario</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-8">
                <a href="<?= site_url(Backend_URL . "scenario/set_display_mode?exam_id={$id}&mode=Presentation") ?>" class="btn btn-info" onclick="return confirm('Are you sure you want to set ALL scenarios to Presentation mode?')"><i class="fa fa-list"></i> Set All to Presentation</a>
                <a href="<?= site_url(Backend_URL . "scenario/set_display_mode?exam_id={$id}&mode=Diagnosis") ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to set ALL scenarios to Diagnosis mode?')"><i class="fa fa-stethoscope"></i> Set All to Diagnosis</a>
            </div>
            <div class="col-md-4 text-right">
                <form action="<?php echo site_url(Backend_URL . 'scenario'); ?>" class="form-inline" method="get">
                    <input type="hidden" name="id" value="<?= $id; ?>"/>
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit">Search</button>
                            <?php if ($q <> '') { ?>
                                <a href="<?php echo site_url(Backend_URL . "scenario?id={$id}"); ?>"
                                   class="btn btn-primary">Reset</a>
                            <?php } ?>                            
                        </span>
                    </div>
                </form>
            </div>

            <?php pp( $_sql_ ); ?>
        </div>

        <?php if($scenarios) { ?>
        <form method="post" id="scenarios" onsubmit="return multiDelete(event);">
        <div class="box-body">            
            <div class="table-responsive">
                
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="40" class="text-center">
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">                                            
                                <i class="fa fa-square-o"></i>
                            </button>
                        </th>
                        <th width="60" class="text-center">S.No</th>
                        <th>Presentation</th>
                        <th>Diagnosis</th>
                        <th>DisplayMode</th>
                        <th>Assignment of Practice</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($scenarios as $scenario) {
                        
                        //$disabled = (($scenario->used + $scenario->examed)) ? 'disabled' : 'class="scen_id"';
                        $disabled = 'disabled';
                        ?>
                        <tr id="row_<?= $scenario->id; ?>">
                            <td class="text-center">
                                <input name="id[<?= $scenario->id; ?>]" 
                                       <?= $disabled; ?>
                                       value="<?= $scenario->id; ?>" 
                                       type="checkbox" />
                            </td>
                            <td class="text-center"><?= sprintf('%03d',$scenario->reference_number); ?></td>                            
                            <td><?php echo $scenario->presentation; ?></td>
                            <td><?php echo $scenario->name; ?></td>
                            <td>
                                <select class="form-control input-sm display-mode-toggle" data-id="<?= $scenario->id ?>">
                                    <option value="Presentation" <?= ($scenario->display_mode == 'Presentation') ? 'selected' : '' ?>>Presentation</option>
                                    <option value="Diagnosis" <?= ($scenario->display_mode == 'Diagnosis') ? 'selected' : '' ?>>Diagnosis</option>
                                </select>
                            </td>
                            <td id="linking_<?= $scenario->id; ?>">
                                <?php $asg = isset($assignments[$scenario->id]) ? $assignments[$scenario->id] : null; ?>
                                <div class="assign-label" style="margin-bottom: 5px;">
                                    <?php if ($asg) { ?>
                                        <span class="label label-success"><?= html_escape($asg->subject_name); ?></span>
                                        <span class="label label-info"><?= html_escape($asg->topic_name); ?></span>
                                    <?php } else { ?>
                                        <span class="label label-default">Unassigned</span>
                                    <?php } ?>
                                </div>
                                <button type="button" class="btn btn-xs btn-default assign_btn"
                                        data-id="<?= $scenario->id; ?>"
                                        data-name="<?= html_escape($scenario->name); ?>"
                                        data-subject-id="<?= $asg ? $asg->subject_id : ''; ?>"
                                        data-topic-id="<?= $asg ? $asg->scenario_topic_id : ''; ?>">
                                    <i class="fa fa-link"></i> Assign
                                </button>
                            </td>

                            <td class="text-center">
                                <?php
                                    echo anchor(site_url(Backend_URL . 'scenario/read/' . $scenario->id), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-primary"');
                                    echo anchor(site_url(Backend_URL . 'scenario/update/' . $scenario->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-warning"');
                                    echo '&nbsp;';
                                    echo scenarioDelBtn($scenario->id, 1);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
               
            </div>

        </div>
        <div class="box-footer">
            <div id="respond"></div>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-danger">
                        Delete Multi Scenario
                    </button>
                    <span class="btn btn-primary">Total <?php echo $total_rows ?> Scenarios</span>
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
             </form>
        <?php } else { ?>
            <div class="box-body">
                <p class="ajax_notice"> No Scenario Found</p>
            </div>
        <?php } ?>
    </div>
</section>

<div class="modal fade" id="assign_topic_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="assign_topic_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Assign Scenario to Topic</h4>
                </div>
                <div class="modal-body">
                    <p>Scenario: <strong id="assign_scenario_name"></strong></p>
                    <div class="form-group">
                        <label for="assign_subject_id">Subject <sup>*</sup></label>
                        <select class="form-control" id="assign_subject_id" name="subject_id">
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects_tree as $subject) { ?>
                                <option value="<?= $subject->id; ?>"><?= html_escape($subject->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assign_topic_id">Topic <sup>*</sup></label>
                        <select class="form-control" id="assign_topic_id" name="scenario_topic_id">
                            <option value="">Select Subject First</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="assign_scenario_id" name="scenario_id" value="">
                    <input type="hidden" name="exam_id" value="<?= $id; ?>">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="scenario_rel_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Scenario Usage</h4>
            </div>
            <div class="modal-body">
                <p>Scenario: <strong id="rel_scenario_name"></strong></p>
                <table class="table table-bordered" style="margin-bottom: 0;">
                    <tbody>
                    <tr>
                        <td>Exam Results</td>
                        <td width="70" class="text-center"><span class="label label-primary" id="rel_result">...</span></td>
                    </tr>
                    <tr>
                        <td>Scenario Relations</td>
                        <td class="text-center"><span class="label label-primary" id="rel_sce_rel">...</span></td>
                    </tr>
                    <tr>
                        <td>Practice Topic Items</td>
                        <td class="text-center"><span class="label label-primary" id="rel_topis">...</span></td>
                    </tr>
                    </tbody>
                </table>
                <p class="text-danger" id="rel_error" style="display: none; margin-top: 10px;">Could not load usage data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php load_module_asset('scenario','js'); ?>
<script>
    var subjectsTree = <?php echo json_encode($subjects_tree); ?>;

    function viewRel(ScenarioId) {
        var $row = $('#row_' + ScenarioId);
        $('#rel_scenario_name').text($row.length ? $.trim($row.find('td').eq(3).text()) : ('#' + ScenarioId));
        $('#rel_result, #rel_sce_rel, #rel_topis').text('...');
        $('#rel_error').hide();
        $('#scenario_rel_modal').modal('show');

        $.ajax({
            url     : '<?= site_url(Backend_URL . 'scenario/get_rel/') ?>' + ScenarioId,
            type    : 'GET',
            dataType: 'json',
            success : function (res) {
                $('#rel_result').text(res.result);
                $('#rel_sce_rel').text(res.sce_rel);
                $('#rel_topis').text(res.topis);
            },
            error   : function () {
                $('#rel_result, #rel_sce_rel, #rel_topis').text('-');
                $('#rel_error').show();
            }
        });
        return false;
    }

    function populateAssignTopics(subjectId, selectedTopicId) {
        var $topic = $('#assign_topic_id');
        $topic.empty();
        if (!subjectId) {
            $topic.append('<option value="">Select Subject First</option>');
            return;
        }
        var subject = subjectsTree.find(function (s) {
            return String(s.id) === String(subjectId);
        });
        $topic.append('<option value="">Select Topic</option>');
        if (subject && subject.topics) {
            subject.topics.forEach(function (t) {
                var selected = (String(t.id) === String(selectedTopicId)) ? ' selected' : '';
                $topic.append('<option value="' + t.id + '"' + selected + '>' + t.name + '</option>');
            });
        }
    }

    $(document).on('click', '.assign_btn', function () {
        var id        = $(this).data('id');
        var name      = $(this).data('name');
        var subjectId = $(this).data('subject-id');
        var topicId   = $(this).data('topic-id');

        $('#assign_scenario_id').val(id);
        $('#assign_scenario_name').text(name);
        $('#assign_subject_id').val(subjectId ? String(subjectId) : '');
        populateAssignTopics(subjectId, topicId);
        $('#assign_topic_modal').modal('show');
    });

    $(document).on('change', '#assign_subject_id', function () {
        populateAssignTopics($(this).val(), null);
    });

    $(document).on('submit', '#assign_topic_form', function (e) {
        e.preventDefault();
        var scenarioId = $('#assign_scenario_id').val();
        var subjectId  = $('#assign_subject_id').val();
        var topicId    = $('#assign_topic_id').val();

        if (subjectId === '') {
            toastr.error('Please select a subject');
            return false;
        }
        if (topicId === '') {
            toastr.error('Please select a topic');
            return false;
        }

        $.ajax({
            url       : '<?= site_url(Backend_URL . 'scenario/ajax_assign_topic') ?>',
            type      : 'POST',
            dataType  : 'json',
            data      : {
                scenario_id      : scenarioId,
                subject_id       : subjectId,
                scenario_topic_id: topicId,
                exam_id          : '<?= $id; ?>'
            },
            beforeSend: function () {
                toastr.warning('Saving...');
            },
            success   : function (res) {
                toastr.clear();
                if (res.Status === 'OK') {
                    toastr.success(res.Msg);
                    var $cell  = $('#linking_' + scenarioId);
                    var labels = res.subject_name
                        ? '<span class="label label-success">' + res.subject_name + '</span> ' +
                          '<span class="label label-info">' + res.topic_name + '</span>'
                        : '<span class="label label-default">Unassigned</span>';
                    $cell.find('.assign-label').html(labels);
                    $cell.find('.assign_btn')
                        .data('subject-id', res.subject_id)
                        .data('topic-id', res.topic_id);
                    $('#assign_topic_modal').modal('hide');
                } else {
                    toastr.error(res.Msg || 'Could not save assignment');
                }
            },
            error     : function () {
                toastr.clear();
                toastr.error('Request failed');
            }
        });
        return false;
    });

    $(document).ready(function() {
        $('.display-mode-toggle').change(function() {
            var id = $(this).data('id');
            var mode = $(this).val();
            $.ajax({
                url: '<?= site_url(Backend_URL . 'scenario/ajax_set_display_mode') ?>',
                type: 'POST',
                data: {id: id, mode: mode},
                success: function(response) {
                    var res = JSON.parse(response);
                    if(res.Status == 'OK') {
                        toastr.success('Display mode updated successfully');
                    } else {
                        toastr.error('Failed to update display mode');
                    }
                }
            });
        });
    });
</script>