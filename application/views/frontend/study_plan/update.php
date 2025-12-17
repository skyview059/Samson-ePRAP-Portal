<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
        font-size: 15px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 15px;
    }
</style>

<div class="row" style="padding-bottom: 15px">
    <div class="col-md-6">
        <h3 style="font-weight: 700; color: #6C00A1">Study Plan<small> / <?= $button; ?></small></h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default"><i class="fa fa-backward"></i>  Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><?= $button; ?> Study Plan</div>
            <div class="panel-body">
                <form action="<?= $action; ?>" method="post" id="study_plan_form" class="form-horizontal">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="exam_id" class="col-sm-2 control-label">Exam <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <select name="exam_id" id="exam_id" class="form-control" required>
                                        <?= getExamNameDropDown($exam_id); ?>
                                    </select>
                                    <?php echo form_error('exam_id'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject_id" class="col-sm-2 control-label">Subject <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <select name="subject_id" id="subject_id" class="form-control select2" required>
                                        <?= getScenarioSubjectsDropDown($exam_id, $subject_id); ?>
                                    </select>
                                    <?php echo form_error('subject_id'); ?>
                                </div>
                            </div>

                            <?php if ($subject_id): ?>
                            <div class="form-group">
                                <label for="topic_id" class="col-sm-2 control-label">Topic</label>
                                <div class="col-sm-10">
                                    <select name="topic_id" id="topic_id" class="form-control select2">
                                        <?= getScenarioTopicsDropDown($exam_id, $subject_id, $topic_id); ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($topic_id): ?>
                            <div class="form-group">
                                <label for="topic_item_id" class="col-sm-2 control-label">Scenario</label>
                                <div class="col-sm-10">
                                    <select name="topic_item_ids[]" class="form-control select2" multiple>
                                        <?= getScenarioTopicItemsDropDownMultiple($exam_id, $subject_id, $topic_id, $topic_item_ids, null); ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="start_date_time" class="col-sm-2 control-label">Start Time <sup>*</sup></label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $start_date; ?>" required>
                                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                        <input type="time" name="start_time" id="start_time" class="form-control" value="<?= $start_time; ?>" required>
                                    </div>
                                    <?php echo form_error('start_date'); ?>
                                    <?php echo form_error('start_time'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="end_date_time" class="col-sm-2 control-label">End Time <sup>*</sup></label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $end_date; ?>" required>
                                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                        <input type="time" name="end_time" id="end_time" class="form-control" value="<?= $end_time; ?>" required>
                                    </div>
                                    <?php echo form_error('end_date'); ?>
                                    <?php echo form_error('end_time'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="duration" class="col-sm-2 control-label">Duration</label>
                                <div class="col-sm-2">
                                    <input type="text" name="duration" id="duration" class="form-control" value="<?= $duration; ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="zoom_link" class="col-sm-2 control-label">Zoom Link</label>
                                <div class="col-sm-10">
                                    <input type="url" name="zoom_link" id="zoom_link" class="form-control" value="<?= $zoom_link; ?>">
                                    <?php echo form_error('zoom_link'); ?>
                                </div>
                            </div>

                            <div class="form-group" style="padding-top: 30px">
                                <div class="col-sm-2 col-md-offset-2">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?= $button; ?></button>
                                    <a href="<?php echo site_url('study-plan'); ?>" class="btn btn-default">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // $('#add_new_scenario_btn').on('click', function () {
    //     const select2 = $('#topic_item_id').select2();
    //     const data    = select2.data();
    //     const length  = data.length;
    //     const newId   = length + 1;
    //     const newOption = new Option('Scenario ' + newId, newId, true, true);
    //     select2.append(newOption).trigger('change');
    // });

    $('#exam_id').on('change', function () {
        const valueSelected  = this.value;
        location.href        = '<?= site_url('study-plan/update'); ?>/<?= $id; ?>?exam_id=' + valueSelected;
    });

    $('#subject_id').on('change', function () {
        const valueSelected  = this.value;
        location.href        = '<?= site_url('study-plan/update'); ?>/<?= $id; ?>?exam_id=<?= $exam_id; ?>&subject_id=' + valueSelected;
    });

    $('#topic_id').on('change', function () {
        const valueSelected  = this.value;
        location.href        = '<?= site_url('study-plan/update'); ?>/<?= $id; ?>?exam_id=<?= $exam_id; ?>&subject_id=<?= $subject_id; ?>&topic_id=' + valueSelected;
    });

    // Calculate Duration
    $('#end_date, #end_time').on('change', function () {
        const start_date = $('#start_date').val();
        const start_time = $('#start_time').val();
        const end_date   = $('#end_date').val();
        const end_time   = $('#end_time').val();
        const start      = new Date(start_date + ' ' + start_time);
        const end        = new Date(end_date + ' ' + end_time);
        const diff       = end - start;
        const hours      = Math.floor(diff / 1000 / 60 / 60);
        const minutes    = Math.floor(diff / 1000 / 60) - (hours * 60);

        if(hours < 0 || minutes < 0) {
            $('#duration').val('00:00:00');
        } else {
            $('#duration').val(hours + ' hours ' + minutes + ' minutes');
        }
    });
</script>