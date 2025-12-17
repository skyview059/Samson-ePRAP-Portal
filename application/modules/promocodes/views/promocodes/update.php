<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>

<style>
    .content-show{
        visibility: hidden;
    }
</style>

<section class="content-header">
    <h1>Promo Codes<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>promocodes">Promocodes</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content"><?php echo promocodesTabs($id, 'update'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Update Promo Codes</h3>
            <?php echo $this->session->flashdata('message'); ?>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?= $action; ?>" method="post">
                <div class="form-group">
                    <label for="code" class="col-sm-2 control-label">Code :</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Code"
                               value="<?= $code; ?>"/>
                        <?php echo form_error('code') ?>
                    </div>

                    <label for="uses_limit" class="col-sm-2 control-label">Uses Limit :</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="uses_limit" id="uses_limit"
                               placeholder="Uses Limit" value="<?= $uses_limit; ?>"/>
                        <?php echo form_error('uses_limit') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="courseIds" class="col-sm-2 control-label">Course :</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="courseIds[]" id="courseIds" multiple data-placeholder="--Select Any Course--">
                            <?php foreach ($courses as $course) { ?>
                                <!-- <option value="--><?php //= $course->id ?><!--"  --><?php //= $course->id == $course->course_id && $course->promocode_id == $id ? 'selected':'' ?><!--> --><?php //= $course->name ?><!-- </option>-->
                                <option value="<?= $course->id ?>"> <?= $course->name ?> </option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('courseIds') ?>
                    </div>

                    <label for="start_date" class="col-sm-2 control-label">Amount :</label>

                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="Amount" value="<?php echo $amount; ?>"/>


                            <span class="input-group-addon" style="padding: 0 20px">
                                <div class="icheck-primary icheck-inline">
                                    <input type="radio" name="discount_type" id="fixed" value="Fixed" <?= $discount_type == 'Fixed'? 'checked': ''?> >
                                    <label for="fixed"> Fixed </label>
                                </div>

                                <div class="icheck-primary icheck-inline" style="padding: 0 20px">
                                    <input type="radio" name="discount_type" id="percentage" value="Percentage" <?= $discount_type == 'Percentage'? 'checked': ''?>>
                                    <label for="percentage"> Percentage </label>
                                </div>
                            </span>
                        </div>
                        <?php echo form_error('amount') ?>
                        <?php echo form_error('distcount_type') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="start_date" class="col-sm-2 control-label">Start Date :</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control js_datepicker" name="start_date" id="start_date"
                               placeholder="Start Date" value="<?= $start_date; ?>"/>
                        <?php echo form_error('start_date') ?>
                    </div>

                    <label for="end_date" class="col-sm-2 control-label">End Date :</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control js_datepicker" name="end_date" id="end_date" placeholder="End Date"
                               value="<?= $end_date; ?>"/>
                        <?php echo form_error('end_date') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label" style="padding-top: 0px">Status :</label>
                    <div class="col-sm-4">
                        <div class="icheck-primary icheck-inline" style="margin: 0 5px 20px!important">
                            <input type="radio" name="status" value="Public" id="public" <?= $status == 'Public' ? 'checked' :'' ?> />
                            <label for="public"> Public </label>
                        </div>

                        <div class="icheck-primary icheck-inline" style="margin: 0 5px 20px!important">
                            <input type="radio" name="status" value="Draft" id="draft" <?= $status == 'Draft' ? 'checked' :'' ?> />
                            <label for="draft"> Draft </label>
                        </div>
                    </div>


                    <label for="use_multiple" class="col-sm-2 control-label" style="padding-top: 0px" > Use Multiple Time :</label>
                    <div class="col-sm-4">
                        <div class="icheck-primary icheck-inline" style="margin: 0 5px 20px!important">
                            <input type="checkbox" name="use_multiple" value="yes" id="use_multiple" <?= $use_multiple == 'yes' ? 'checked' :'' ?> />
                            <label for="use_multiple"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="is_special" class="col-sm-2 control-label" style="padding-top: 0px" > Specific student :</label>
                    <div class="col-sm-4">
                        <div class="icheck-primary icheck-inline" style="margin: 0 5px 20px!important">
                            <input type="checkbox" name="is_special" value="1" id="is_special" <?= $is_special == '1' ? 'checked' :'' ?> />
                            <label for="is_special"></label>
                        </div>
                    </div>

                    <div class="content-show">
                        <label for="studentIds" class="col-sm-2 control-label"> Student :</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="studentIds[]" id="studentIds" multiple data-placeholder="--Select Student--" aria-label="">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="promoter_name" class="col-sm-2 control-label"> Promoter Name : </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="promoter_name" id="promoter_name"
                               placeholder="Promoter Name" value="<?= $promoter_name; ?>"/>
                    </div>
                </div>

                <div class="col-md-12 text-right">
                    <input type="hidden" name="id" value="<?= $id; ?>"/>
                    <button type="submit" class="btn btn-primary"><?= $button ?></button>
                    <a href="<?php echo site_url(Backend_URL . 'promocodes') ?>" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.flatpickr').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayBtn: true,
            todayHighlight: true
        });

        $('#is_special').on('change', function() {
            $('#studentIds').parent().parent().toggleClass('content-show');
        });

        if($('#is_special').is(':checked')){
            $('#studentIds').parent().parent().removeClass('content-show');
        }

        // selected courses
        $('#courseIds').val([<?= $courseIds ?>]).trigger('change');

        $('#studentIds').select2({
            ajax: {
                url: 'admin/all_students',
                method: 'get',
                dataType: 'json',
                delay: 250,
                cache: true,

                data: function(params) {
                    return {
                        search_keyword: params.term,
                        page: params.page || 1,
                    }
                },

                processResults: function(response, params) {
                    params.page = params.page || 1;

                    return {
                        results: $.map(response.students.data, function(value) {

                            let label = value.full_name + ' (' + value.email + ')';

                            return {
                                text: label,
                                id: value.id
                            };
                        }),

                        pagination: {
                            more: (params.page * 10) < response.students.total
                        }
                    };
                }
            },

            allowClear: true,
            closeOnSelect: false,
            width: '100%',
            language: {
                noResults: function(params) {
                    return "No result found ";
                }
            }
        });

        // Active selected student
        let students = <?= json_encode($students); ?>;
        $.each(students, function (key, value) {
            $('#studentIds').append('<option value="' + value.id + '" selected>' + value.full_name + ' (' + value.email + ')' + '</option>');
        });
    });
</script>