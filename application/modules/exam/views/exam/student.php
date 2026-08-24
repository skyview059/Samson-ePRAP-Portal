<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<style type="text/css">
    .table thead tr th,
    .table tbody tr td {
        vertical-align: middle;
    }
     .ck-editor__editable {
         min-height: 200px;
     }
</style>
<section class="content-header">
    <h1>Mock Exam <small>Student List</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'exam') ?>">Mock Exam</a></li>
        <li class="active">Students</li>
    </ol>
</section>
<section class="content personaldevelopment">
    <?php echo examTabs($id, 'student'); ?>
    <div class="box no-border">
        <form method="post" id="student_list" onsubmit="return send_link(event);">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pull-left">
                            <button type="button" class="btn btn-primary pull-right hide_on_print"
                                    onclick="linkStudent();">
                                <i class="fa fa-hospital-o"></i>
                                Book Student for Exam
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 class="no-margin">Exam Name: <?php echo $course_name; ?></h2>
                        <h4>
                            Centre: <?php echo($centre_name); ?>, <?php echo($centre_address); ?><br/>
                            Date & Time: <?php echo globalDateTimeFormat($datetime); ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="70">
                                <label>
                                    <input type="checkbox" onclick="checkUncheck();"/>
                                    S/L
                                </label>
                            </th>
                            <th width="80">Photo</th>
                            <th>Name & Email</th>                            
                            <th>StudentID</th>
                            <th>Number</th>
                            <th>Phone</th>
                            <th>Booked At</th>
                            <th>Attendance</th>
                            <th class="text-center hide_on_print" width="170">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($students as $student) {
                            $options    = "<input name='students[]' value='{$student->id}' class='mark' type='checkbox'/>";
                            $exam_link  = "exam_schedule_id={$id}&number_type={$student->number_type}&gmc={$student->gmc_number}";
                            ?>
                            <tr>
                                <td><label><?= $options . ' ' . sprintf('%02d', ++$start); ?></label></td>
                                <td><?php echo getPhoto_v3($student->photo, $student->gender, $student->fname, 60, 60); ?></td>
                                <td><?php                                     
                                        echo anchor(
                                            site_url(Backend_URL . 'student/read/' . $student->id),
                                            "{$student->fname} {$student->mname} {$student->lname}" . ' <i class="fa fa-fw fa-external-link"></i>',
                                            'target="_blank"'
                                        ); ?><br/>
                                    <?= $student->email ?> 
                                </td>                                
                                <td><?php echo globalDateFormat($student->exam_date); ?></td>
                                <td><?php echo studentID($student->id); ?></td>
                                <td>
                                    <a href="tel:<?php echo "+{$student->phone_code}{$student->phone}"; ?>">
                                        <i class="fa fa-mobile-phone"></i> <?php echo "+{$student->phone_code}{$student->phone}"; ?>
                                        <br/>
                                    </a>
                                    <a href="https://wa.me/<?= "+{$student->whatsapp_code}{$student->whatsapp}"; ?>"
                                       target="_blank">
                                        <i class="fa fa-whatsapp"></i> <?php echo "+{$student->whatsapp_code}{$student->whatsapp}"; ?>
                                    </a>
                                </td>
                                <td><?php echo globalDateTimeFormat($student->assign_at); ?></td>
                                <td>
                                <span class="label <?= ($student->attendance) ? 'label-success' : 'label-warning' ?> btn-xs"> 
                                <?= ($student->attendance) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-fw fa-close"></i>'; ?>
                                <?= ($student->attendance) ? 'Yes' : 'No'; ?>
                                </span>
                                </td>
                                <td class="text-center hide_on_print">
                                    <?php
                                    
                                    if ($student->exam_status == 'Enrolled') {
                                        ?>
                                        <span class="btn  btn-xs btn-danger"
                                              onclick="studentStatusChange(<?php echo "{$student->student_exam_id}"; ?>);">
                                  <i class="fa fa-times"></i>
                                  Cancel
                                </span>
                                    <?php } else { ?>
                                        <span class="btn  btn-xs btn-default disabled">
                                      <i class="fa fa-ban"></i>
                                      Canceled
                                    </span>
                                    <?php } ?>

                                    <?php 
                                        echo anchor(
                                            site_url(Backend_URL . 'student/login/' . $student->id), 
                                            '<i class="fa fa-fw fa-gear"></i> Login', 
                                            'class="btn btn-xs btn-info" target="_blank"'
                                        ); 
                                    ?>
                
                                    <p style="margin-top: 5px;">
                                        <a class="btn btn-primary btn-xs" href="<?= site_url(Backend_URL . 'assess/search_student?' . $exam_link ) ?>" target="_blank">
                                            Open Exam View
                                            <i class="fa fa-fw fa-external-link"></i>
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box-footer with-border hide_on_print">
                <div id="respond"></div>
                <div class="row">
                    <div class="col-md-5">
                        <button class="btn btn-success" type="button" onclick="sendLinkModal()">
                            <i class="fa fa-send"></i>
                            Create Group
                        </button>
                    </div>
<!--                                    <div class="col-md-5">-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">Select Whatsapp Group to Assign Student:</span>-->
<!--                                            <select class="form-control" name="whatsapp_id">-->
<!--                                                --><?php //= getDropDownWhatsapp(); ?>
<!--                                            </select>-->
<!--                                            <span class="input-group-btn">-->
<!--                                                <button class="btn btn-primary" type="submit">-->
<!--                                                    <i class="fa fa-send"></i>-->
<!--                                                    Send Link-->
<!--                                                </button>-->
<!--                                            </span>-->
<!--                                        </div>-->
<!--                                    </div>-->
                    <div class="col-md-7">
                        <button type="button" class="btn btn-primary print_btn" onclick="return window.print();">
                            <i class="fa fa-print"></i>
                            Print
                        </button>
                        <a href="admin/exam/student_export_csv/<?= $id; ?>" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            Download CSV
                        </a>
                    </div>
                </div>
            </div>
            <p class="show_on_print">Printed at <?php echo date('d/m/Y h:i a'); ?></p>
        </form>
    </div>
</section>

<!-- Book Student for Exam — Alpine.js component (server-side search, additive save) -->
<div class="modal fade" id="student_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     x-data="bookingModal(<?= (int)$id ?>, <?= (int)$exam_id ?>)"
     @open-booking-modal.window="open()">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Book Student for Mock Exam</h4>
            </div>

            <div class="modal-body">
                <!-- Exam filter -->
                <div class="form-group">
                    <label class="control-label">Show students interested in:</label>
                    <div>
                        <template x-for="e in exams" :key="e.id">
                            <label class="checkbox-inline" style="margin-left:0; margin-right:15px;">
                                <input type="checkbox" :value="String(e.id)" x-model="examIds" @change="search(1)">
                                <span x-text="e.name"></span>
                                <small class="text-muted" x-text="e.exam_type ? '(' + e.exam_type + ')' : ''"></small>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Keyword -->
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control" autocomplete="off"
                               x-model="q" @input.debounce.300ms="search(1)"
                               x-ref="keyword"
                               placeholder="Search by name, email, student ID or GMC number"/>
                        <span class="input-group-btn" x-show="q !== ''">
                            <button type="button" class="btn btn-default" @click="q = ''; search(1); $refs.keyword.focus()">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <p class="text-muted" style="margin-bottom:5px;">
                    <span x-text="statusText()"></span>
                    <span class="pull-right" x-show="selected.length">
                        <span class="label label-primary" x-text="selected.length + ' selected'"></span>
                    </span>
                </p>

                <div class="table-responsive" style="max-height:450px; overflow-y:auto;">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th width="60" class="text-center">Mark</th>
                            <th width="70" class="text-center">ID</th>
                            <th width="110" class="text-center">GMC/G No</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="s in rows" :key="s.id">
                            <tr :class="{'success': selected.includes(String(s.id))}">
                                <td class="text-center">
                                    <span class="label label-success" x-show="s.booked == 1" title="Already booked for this schedule">
                                        <i class="fa fa-check"></i> Booked
                                    </span>
                                    <input type="checkbox" x-show="s.booked != 1" :value="String(s.id)" x-model="selected">
                                </td>
                                <td class="text-center" x-text="s.id"></td>
                                <td class="text-center" x-text="s.number_type + '-' + (s.gmc || '')"></td>
                                <td x-text="s.full_name"></td>
                                <td x-text="s.email"></td>
                            </tr>
                        </template>
                        <tr x-show="loading && rows.length === 0">
                            <td colspan="5" class="text-center"><p class="ajax_processing">Loading...</p></td>
                        </tr>
                        <tr x-show="!loading && rows.length === 0 && examIds.length">
                            <td colspan="5" class="text-center">
                                <p class="ajax_notice">No student found.</p>
                                <a :href="'admin/student/create?id=' + examIds[0]" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Click here to add Student
                                </a>
                            </td>
                        </tr>
                        <tr x-show="!examIds.length">
                            <td colspan="5" class="text-center"><p class="ajax_notice">Select at least one exam above.</p></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pager -->
                <div class="text-center" x-show="total > limit">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm" @click="search(page - 1)" :disabled="page <= 1 || loading">
                            <i class="fa fa-chevron-left"></i> Prev
                        </button>
                        <button type="button" class="btn btn-default btn-sm" disabled
                                x-text="'Page ' + page + ' of ' + Math.max(1, Math.ceil(total / limit))"></button>
                        <button type="button" class="btn btn-default btn-sm" @click="search(page + 1)" :disabled="page * limit >= total || loading">
                            Next <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    Close
                </button>
                <button type="button" class="btn btn-success" @click="save()" :disabled="!selected.length || saving">
                    <i class="fa" :class="saving ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                    <span x-text="selected.length ? 'Book ' + selected.length + ' Student(s)' : 'Save Changes'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="student_exam_status">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Student Exam Cancel</h4>
                </div>
                <div class="modal-body">
                    <div class="js_respond"></div>
                    <div class="student_exams_box"></div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="save_student_exam_status();" type="button" class="btn btn-success">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Link to Students Modal -->
<div class="modal fade" id="send_link" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="send_link_form" class="form-horizontal">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Send Link to Students</h4>
                    <p>You select <span class="student_selected_count" style="font-weight: bold;">0</span> students to send link</p>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="link_type" class="col-sm-2 control-label">Link Type <sup>*</sup></label>
                        <div class="col-sm-10">
                            <?php
                            echo htmlRadio('link_type', 'Whatsapp', [
                                'Whatsapp' => 'WhatsApp',
                                'Telegram' => 'Telegram',
                                'Email'    => 'Email'
                            ], 'class="link_type"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group" id="whatsapp_link_area">
                        <label for="whatsapp_link_id" class="col-sm-2 control-label">Whatsapp Link<sup>*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="whatsapp_link_id" id='whatsapp_link_id'>
                                <option value="0">--Select WhatsApp Group--</option>
                                <?php echo wa::getLinks(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="telegram_link_area" style="display: none;">
                        <label for="telegram_link_id" class="col-sm-2 control-label">Telegram Link<sup>*</sup></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="telegram_link_id" id='telegram_link_id'>
                                <option value="0">--Select Telegram Group--</option>
                                <?php echo wa::getLinks(0, 'Telegram'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="mail_body_area">
                        <label for="mail_body" class="col-sm-2 control-label">Mail Body<sup>*</sup></label>
                        <div class="col-sm-10">
                            <textarea name="mail_body" id="mail_body" class="form-control" rows="15"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <button type="button" class="btn btn-default" id="close_scenario_modal" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        Close
                    </button>
                    <button onclick="send_link();" type="button" class="btn btn-success">
                        <i class="fa fa-send"></i>
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/lib/plugins/ckeditor5/classic/build/ckeditor.js"></script>
<!-- Alpine.js v3 — loaded on this page only, powers the "Book Student for Exam" modal -->
<script defer src="assets/lib/plugins/alpinejs/alpine.min.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create(document.querySelector('#mail_body'), {
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },
            toolbar: {
                items: [
                    'fontSize', 'fontColor', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList',
                    'alignment', 'insertTable', 'horizontalLine', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });

    $(document).ready(function () {
        $('.link_type').on('change', function() {
            if($(this).val() === 'Whatsapp') {
                $('#whatsapp_link_area').show();
                $('#telegram_link_area').hide();
            } else if($(this).val() === 'Telegram') {
                $('#whatsapp_link_area').hide();
                $('#telegram_link_area').show();
            } else {
                $('#whatsapp_link_area').hide();
                $('#telegram_link_area').hide();
            }
        });

        $('#whatsapp_link_id, #telegram_link_id').on('change', function() {
            const link_id  = $(this).val();
            const link_type = $('input[name="link_type"]:checked').val();
            $.ajax({
                type      : 'POST',
                data      : {link_id: link_id, link_type: link_type},
                url       : 'admin/whatsapp/get_link_data',
                dataType  : 'json',
                beforeSend: function () {
                    toastr.info('Please wait...');
                },
                success   : function (respond) {
                    toastr.clear();
                    if(respond.Status === 'OK') {
                        toastr.success('Link loaded successfully!');
                        editor.setData(`${respond.Msg.title} <br/> ${respond.Msg.link} <br/><br/> Thanks <br/> Team Samson`);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });

    });

    function sendLinkModal() {
        const len = $(".mark:checked").length;
        if (len) {
            $('.student_selected_count').text(len);
            $('#send_link').modal({
                show    : 'false',
                backdrop: 'static'
            });
        } else {
            alert('Please select at least one student');
        }
    }

    function checkUncheck() {
        const len = $(".mark:checked").length;
        if (len) {
            $('.mark').prop('checked', '');
        } else {
            $('.mark').prop('checked', 'checked');
        }
    }

    function send_link() {
        let student_list = $('#student_list').serialize();

        // post additional data
        student_list += '&link_type=' + $('input[name="link_type"]:checked').val();
        student_list += '&whatsapp_link_id=' + $('#whatsapp_link_id').val();
        student_list += '&telegram_link_id=' + $('#telegram_link_id').val();
        student_list += '&mail_body=' + editor.getData('html');

        $.ajax({
            url       : 'admin/whatsapp/send_link',
            type      : 'POST',
            dataType  : "json",
            data      : student_list,
            beforeSend: function () {
                toastr.info('Please wait...');
            },
            success   : function (respond) {
                toastr.clear();
                if(respond.Status === 'OK') {
                    toastr.success(respond.Msg);
                    location.reload();
                } else {
                    toastr.error(respond.Msg);
                }
            }
        });
        return false;
    }

    /* Opens the Alpine-powered booking modal (see bookingModal() below). */
    function linkStudent() {
        window.dispatchEvent(new CustomEvent('open-booking-modal'));
    }

    /**
     * Alpine.js component for #student_popup.
     * Server-side search: admin/student/search_for_exam
     * Additive booking:   admin/student/book_for_exam
     */
    function bookingModal(scheduleId, defaultExamId) {
        const ajaxHeaders = {'X-Requested-With': 'XMLHttpRequest'};
        return {
            exams   : <?= json_encode($exams, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            examIds : [String(defaultExamId)],
            q       : '',
            page    : 1,
            limit   : 25,
            total   : 0,
            rows    : [],
            selected: [],      // student ids (strings) — persists across searches & pages
            loading : false,
            saving  : false,
            _req    : 0,

            open() {
                this.q        = '';
                this.page     = 1;
                this.rows     = [];
                this.total    = 0;
                this.selected = [];
                this.examIds  = [String(defaultExamId)];
                $('#student_popup').modal({show: true, backdrop: 'static'});
                this.search(1);
                this.$nextTick(() => this.$refs.keyword && this.$refs.keyword.focus());
            },

            async search(page) {
                this.page    = Math.max(1, page);
                this.loading = true;
                const reqId  = ++this._req;

                if (!this.examIds.length) {
                    this.rows = []; this.total = 0; this.loading = false;
                    return;
                }

                const body = new URLSearchParams({
                    exam_schedule_id: scheduleId,
                    q               : this.q,
                    page            : this.page
                });
                this.examIds.forEach(id => body.append('exam_ids[]', id));

                try {
                    const res  = await fetch('admin/student/search_for_exam', {method: 'POST', body, headers: ajaxHeaders});
                    const data = await res.json();
                    if (reqId !== this._req) return;   // a newer request superseded this one
                    this.rows  = data.rows  || [];
                    this.total = data.total || 0;
                    this.limit = data.limit || this.limit;
                } catch (e) {
                    if (reqId !== this._req) return;
                    toastr.error('Search failed. Please try again.');
                    this.rows = []; this.total = 0;
                } finally {
                    if (reqId === this._req) this.loading = false;
                }
            },

            async save() {
                if (!this.selected.length || this.saving) return;
                this.saving = true;

                const body = new URLSearchParams({exam_schedule_id: scheduleId});
                this.selected.forEach(id => body.append('student_ids[]', id));

                try {
                    const res  = await fetch('admin/student/book_for_exam', {method: 'POST', body, headers: ajaxHeaders});
                    const data = await res.json();
                    if (data.Status === 'OK') {
                        toastr.success(data.Msg);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        toastr.error(data.Msg || 'Something went wrong!');
                        this.saving = false;
                    }
                } catch (e) {
                    toastr.error('Save failed. Please try again.');
                    this.saving = false;
                }
            },

            statusText() {
                if (this.loading) return 'Loading...';
                if (!this.total)  return '';
                const from = (this.page - 1) * this.limit + 1;
                const to   = Math.min(this.page * this.limit, this.total);
                return `Showing ${from}–${to} of ${this.total} student(s)`;
            }
        };
    }

    function studentStatusChange(student_exam_id) {
        $('#change_status').modal({
            show    : 'false',
            backdrop: 'static'
        });
        $.ajax({
            url       : "admin/exam/get_student_exams/" + student_exam_id,
            type      : "GET",
            dataType  : "html",
            beforeSend: function () {
                $('.student_exams_box').html('<p class="ajax_processing">Loading...</p>');
            },
            success   : function (msg) {
                $('.student_exams_box').html(msg);
            }
        });
    }

    function save_student_exam_status() {
        const FormData = $('#student_exam_status').serialize();
        $.ajax({
            url       : "admin/exam/assign_exam_set_status",
            type      : "POST",
            dataType  : "json",
            data      : FormData,
            beforeSend: function () {
                $('.js_respond').html('<p class="ajax_processing">Please Wait...</p>');
            },
            success   : function (respond) {
                $('.js_respond').html(respond.Msg);
                if (respond.Status === 'OK') {
                    setTimeout(function () { location.reload(); }, 1000);
                }
            }
        });
    }
</script>