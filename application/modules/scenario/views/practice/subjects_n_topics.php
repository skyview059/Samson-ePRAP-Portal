<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>
        <?php echo $exam_name; ?> - Practice Scenario Topics <small>Control panel</small>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_new_subject_modal">
            <i class="fa fa-plus"></i> Add New Subject
        </button>
        <a class="btn btn-default" href="<?php echo site_url('admin/scenario/practice') ?>">Back</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'scenario/practice') ?>">Practice Scenarios</a></li>
        <li class="active">Topic</li>
    </ol>
</section>

<style>
    #accordion {
        margin: auto;
        max-width: 100%;
        padding-top: 15px;
    }

    #accordion.panel-group .panel {
        margin-bottom: 15px;
    }

    #accordion .panel-heading a {
        display: block;
        position: relative;
        font-weight: bold;
        color: black;

        &::after {
            content: "";
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 5px;
            position: absolute;
            right: 0;
            top: 0;
            transform: rotate(45deg);
        }

        &[aria-expanded="true"]::after {
            transform: rotate(-135deg);
            top: 5px;
        }
    }

    .rename_subject_btn, .delete_subject_btn {
        float: right;
        margin-top: -54px;
        margin-right: 35px;
        position: relative;
        cursor: pointer;
        color: red;
    }

    .delete_subject_btn {
        margin-right: 120px;
    }
</style>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo $exam_name; ?> - Subjects & Topics</div>

        <div class="panel-body">
            <?php if ($topics) { ?>
                <div class="panel-group" id="accordion">
                    <?php $start = 0;
                    foreach ($topics as $subject) {
                        $type = ($subject['type'] == 'New') ? '<span class="label label-success pull-right" style="margin-right: 30px">New</span>' : '';
                        ?>
                        <div class="panel panel-info" id="item-<?= $subject['id']; ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion"
                                       href="#collapseItem<?= $subject['id']; ?>"
                                       class="collapsed" aria-expanded="false">
                                        <i class="fa fa-arrows-v" style="color: #e5b4fd;"></i> <?= $subject['name'] . $type; ?>
                                        <?php if ($subject['topics']): ?>
                                            <span class="label label-default" style="margin-left: 20px">Topic: <?= count($subject['topics']); ?></span>
                                        <?php endif; ?>
                                        <span class="label label-default" style="margin-left: 20px">Scenario: <b id="scenarios-count-<?= $subject['id']; ?>"></b></span>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseItem<?= $subject['id']; ?>" class="panel-collapse collapse"
                                 aria-expanded="false" style="height: 0;">
                                <div class="panel-body">

                                    <button type="button" class="btn btn-primary pull-right btn-xs add_new_topic_btn"
                                            style="margin-bottom: 5px" data-subject-id="<?= $subject['id']; ?>">
                                        <i class="fa fa-plus"></i> Add New Topic
                                    </button>
                                    <?php if (count($subject['topics']) === 0): ?>
                                        <span class="delete_subject_btn"
                                              data-subject-id="<?= $subject['id']; ?>"
                                              data-subject-name="<?= htmlentities($subject['name']); ?>"
                                        ><i class="fa fa-trash"></i> Delete</span>
                                    <?php endif; ?>

                                    <span class="rename_subject_btn"
                                          data-subject-id="<?= $subject['id']; ?>"
                                          data-subject-type="<?= $subject['type']; ?>"
                                          data-subject-name="<?= htmlentities($subject['name']); ?>"
                                    ><i class="fa fa-edit"></i> Edit</span>
                                    <?php if ($subject['topics']): ?>
                                        <table class="table table-hover table-condensed no-margin"
                                               id="subject-ordering">
                                            <thead>
                                            <tr>
                                                <th width="60">S/L</th>
                                                <th>Topic Name</th>
                                                <th class="text-center">Scenarios</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sl = 0;
                                            foreach ($subject['topics'] as $topic) {
                                                ?>
                                                <tr id="subject-item-<?= $topic->id; ?>">
                                                    <td>
                                                        <i class="fa fa-arrows-v"></i> <?php echo sprintf('%03d', ++$sl); ?>
                                                    </td>
                                                    <td id="topic-item-<?= $topic->id; ?>"><?= $topic->name; ?></td>
                                                    <td width="100" class="text-center">
                                                        <span class="label label-default scenarios-count-<?= $subject['id']; ?>">
                                                            <?= $topic->scenarios; ?>
                                                        </span>
                                                    </td>
                                                    <td width="270" class="text-center">
                                                        <button type="button"
                                                                class="btn btn-xs btn-default rename_topic_btn"
                                                                data-topic-id="<?= $topic->id; ?>">
                                                            <i class="fa fa-edit"></i> Rename
                                                        </button>

                                                        <?php echo anchor(site_url(
                                                            Backend_URL . "scenario/practice/topic/edit?exam_id={$exam_id}&subject_id={$subject['id']}&topic_id={$topic->id}"),
                                                            '<i class="fa fa-random"></i> Assign Scenarios',
                                                            'class="btn btn-xs btn-default" title="Edit"'
                                                        );
                                                        ?>

                                                        <button type="button"
                                                                onClick="delete_topic(<?php echo $topic->id; ?>)"
                                                                class="btn btn-danger btn-xs" title="Delete Topic">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p class="ajax_notice">No topic found under <?= $subject['name']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="box-body">
                    <p class="ajax_notice"> No Topic found at this Centre.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<div class="modal fade" id="update_subject_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(Backend_URL . 'scenario/practice/update_subject_action', array('class' => 'form-horizontal', 'method' => 'post', 'id' => 'update_subject_from')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Subject</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="subject_name" class="col-sm-3 control-label">Subject Name<sup>*</sup></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subject_type" class="col-sm-3 control-label">Type<sup>*</sup></label>
                    <div class="col-sm-9">
                        <div style="margin-top: 8px;">
                            <?php echo htmlRadio('subject_type', 'New', array('New' => 'New', 'Old' => 'Old')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="subject_id" id="subject_id" value=""/>
                <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $exam_id; ?>"/>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new_subject_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(Backend_URL . 'scenario/practice/create_subject_action', array('class' => 'form-horizontal', 'method' => 'post', 'id' => 'create_subject_from')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add New Subject</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="subject_name" class="col-sm-3 control-label">Subject Name<sup>*</sup></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-sm-3 control-label">Type<sup>*</sup></label>
                    <div class="col-sm-9">
                        <div style="margin-top: 8px;">
                            <?php echo htmlRadio('type', 'New', array('New' => 'New', 'Old' => 'Old')); ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $exam_id; ?>"/>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new_topic_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open(Backend_URL . 'scenario/practice/create_topic_action', array('class' => 'form-horizontal', 'method' => 'post', 'id' => 'create_topic_from')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add New Topic</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="subject_id" class="col-sm-3 control-label">Subject<sup>*</sup></label>
                    <div class="col-sm-9">
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <?php echo getScenarioSubjectsDropDown($exam_id); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="topic_name" class="col-sm-3 control-label">Topic Name<sup>*</sup></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="topic_name" name="topic_name" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $exam_id; ?>"/>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="rename_topic_modal">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<script type="application/javascript">
<?php foreach ($topics as $subject) { ?>
    $(function () {
        let qty = 0;
        $('.scenarios-count-' + '<?php echo $subject['id']; ?>').each(function (index, val) {
            qty += parseInt($(val).text().replace(/\D/g, "")) || 0;
        });
        $('#scenarios-count-' + '<?php echo $subject['id']; ?>').text(qty);
    });
<?php } ?>

    $(document).on('click', '.add_new_topic_btn', function () {
        const subject_id = $(this).data('subject-id');
        $('#add_new_topic_modal #subject_id').val(subject_id);
        $('#add_new_topic_modal').modal('show');
    });

    $(document).on('click', '.rename_topic_btn', function () {
        const topic_id = $(this).data('topic-id');
        $.ajax({
            url       : 'admin/scenario/practice/get_topic_rename_modal_data',
            type      : 'POST',
            dataType  : 'html',
            data      : {topic_id: topic_id},
            beforeSend: function () {
                toastr.warning("Loading...");
            },
            success   : function (htmlRespond) {
                toastr.clear();
                $('#rename_topic_modal .modal-content').html(htmlRespond);
                $('#rename_topic_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.rename_subject_btn', function () {
        const subject_id   = $(this).data('subject-id');
        const subject_name = $(this).data('subject-name');
        const subject_type = $(this).data('subject-type');
        $('#subject_id').val(subject_id);
        $('#subject_name').val(subject_name);
        const $radios = $('input:radio[name=subject_type]');
        $radios.filter('[value="' + subject_type + '"]').prop('checked', true);
        $('#update_subject_modal').modal('show');
    });

    $(document).on('click', '.delete_subject_btn', function () {
        const subject_id   = $(this).data('subject-id');
        const subject_name = $(this).data('subject-name');
        if (confirm('Are you sure to delete ' + subject_name + ' subject?')) {
            $.ajax({
                url       : 'admin/scenario/practice/scenario_subject_delete',
                type      : 'POST',
                data      : {id: subject_id},
                dataType  : 'json',
                beforeSend: function () {
                    toastr.warning("Processing...");
                },
                success   : function (response) {
                    toastr.clear();
                    if (response.Status === 'OK') {
                        toastr.success(response.Msg);
                        location.reload();
                    } else {
                        toastr.error('Subject Could not Deleted!');
                    }
                }
            });
        }
    });

    function delete_topic(id) {
        if (confirm('Are you sure to delete this topic?')) {
            $.ajax({
                url     : 'admin/scenario/practice/topic_delete',
                type    : 'POST',
                dataType: 'json',
                data    : {id: id},
                success : function (response) {
                    if (response.Status === 'OK') {
                        toastr.success('Topic Deleted Successfully!');
                        location.reload();
                    } else {
                        toastr.error('Topic Could not Deleted!');
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        $("#accordion").sortable({
            axis  : "y",
            update: function (event, ui) {
                const data = $(this).sortable('serialize');
                $.ajax({
                    data   : data,
                    type   : 'POST',
                    url    : 'admin/scenario/practice/scenario_subjects_save_order',
                    success: function (response) {
                        console.log(response);
                        toastr.success("Order Saved Successfully!");
                    }
                });
            }
        });

        $("#subject-ordering tbody").sortable({
            axis  : "y",
            update: function (event, ui) {
                const data = $(this).sortable('serialize');
                $.ajax({
                    data   : data,
                    type   : 'POST',
                    url    : 'admin/scenario/practice/topic_save_order',
                    success: function (response) {
                        console.log(response);
                        toastr.success("Order Saved Successfully!");
                    }
                });
            }
        });
    });

    $(document).on('submit', '#create_topic_from', function () {
        const topic_name = $('#create_topic_from #topic_name').val();
        const exam_id    = $('#create_topic_from #exam_id').val();
        const subject_id = $('#create_topic_from #subject_id').val();

        if (subject_id === '') {
            toastr.error("Please select subject!");
            return false;
        }

        if (topic_name === '') {
            toastr.error("Please enter topic name!");
            return false;
        }

        $.ajax({
            url       : 'admin/scenario/practice/topic_create_action',
            type      : 'POST',
            dataType  : 'json',
            data      : {topic_name: topic_name, exam_id: exam_id, subject_id: subject_id},
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success   : function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#add_new_topic_modal').modal('hide');
                    toastr.success("Topic Created Successfully!");
                    location.reload();
                } else {
                    toastr.error("Topic Could not Created");
                }
            }
        });
        return false;
    });

    $(document).on('submit', '#create_subject_from', function () {
        const subject_name = $('#create_subject_from #subject_name').val();
        const exam_id      = $('#create_subject_from #exam_id').val();
        const type         = $('#create_subject_from input[name="type"]:checked').val();

        if (subject_name === '') {
            toastr.error("Please enter subject name!");
            return false;
        }

        $.ajax({
            url       : 'admin/scenario/practice/topic_subject_action',
            type      : 'POST',
            dataType  : 'json',
            data      : {subject_name: subject_name, exam_id: exam_id, type: type},
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success   : function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#add_new_subject_modal').modal('hide');
                    toastr.success("Subject Created Successfully!");
                    location.reload();
                } else {
                    toastr.error("Topic Could not Created");
                }
            }
        });
        return false;
    });

    $(document).on('submit', '#update_subject_from', function () {
        const subject_name = $('#update_subject_from #subject_name').val();
        const subject_id   = $('#update_subject_from #subject_id').val();
        const subject_type = $('#update_subject_from input[name="subject_type"]:checked').val();
        const exam_id      = $('#update_subject_from #exam_id').val();

        if (subject_name === '') {
            toastr.error("Please enter subject name!");
            return false;
        }

        $.ajax({
            url       : 'admin/scenario/practice/topic_subject_update_action',
            type      : 'POST',
            dataType  : 'json',
            data      : {
                subject_name: subject_name,
                subject_id  : subject_id,
                exam_id     : exam_id,
                subject_type: subject_type
            },
            beforeSend: function () {
                toastr.warning("Please Loading...");
            },
            success   : function (jsonRespond) {
                if (jsonRespond.Status === 'OK') {
                    $('#update_subject_modal').modal('hide');
                    toastr.success("Subject Updated Successfully!");
                    location.reload();
                } else {
                    toastr.error("Subject Could not Updated");
                }
            }
        });
        return false;
    });
</script>