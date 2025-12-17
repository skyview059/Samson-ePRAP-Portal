<?php echo form_open(Backend_URL . 'scenario/practice/topic_rename_action', array('class' => 'form-horizontal', 'method' => 'post', 'id' => 'rename_topic_from')); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Update Topic</h4>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="subject_id" class="col-sm-3 control-label">Subject<sup>*</sup></label>
            <div class="col-sm-9">
                <select class="form-control" id="subject_id" name="subject_id" required>
                    <?php echo getScenarioSubjectsDropDown($exam_id, $subject_id); ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="topic_name" class="col-sm-3 control-label">Topic Name<sup>*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="topic_name" name="topic_name" value="<?= $topic_name; ?>" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="topic_id" value="<?= $topic_id; ?>">
        <input type="hidden" name="exam_id" value="<?= $exam_id; ?>">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
<?php echo form_close(); ?>