<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Shared "Book Student for Exam" modal (Alpine.js, server-side search, additive save).
 *
 * Usage (from any admin view):
 *   <?php $this->load->view('student/student/book_for_exam_modal', [
 *       'schedule_id' => $exam_schedule_id,      // required: exam_schedules.id to book into
 *       'exam_id'     => $exam_id,               // required: exams.id pre-selected in the filter
 *       'source'      => 'Exam/Student page',    // optional: stored in student_exam_enrollments.remarks
 *       'exams'       => $exams,                 // optional: defaults to getBookableExams()
 *   ]); ?>
 *
 * The page then only needs a trigger:  <button type="button" onclick="linkStudent();">…</button>
 *
 * Endpoints: admin/student/search_for_exam, admin/student/book_for_exam
 */
$schedule_id = (int)($schedule_id ?? 0);
$exam_id     = (int)($exam_id ?? 0);
$source      = $source ?? 'Exam/Student page';
$exams       = $exams ?? getBookableExams();
?>
<div class="modal fade" id="student_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     x-data="bookingModal(<?= $schedule_id ?>, <?= $exam_id ?>)"
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
                               placeholder="Search by Name, Email or Student ID"/>
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
                            <th width="100" class="text-center">StudentID</th>
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
                                <td class="text-center" x-text="s.student_id"></td>                                
                                <td x-text="s.full_name"></td>
                                <td x-text="s.email"></td>
                            </tr>
                        </template>
                        <tr x-show="loading && rows.length === 0">
                            <td colspan="4" class="text-center"><p class="ajax_processing">Loading...</p></td>
                        </tr>
                        <tr x-show="!loading && rows.length === 0 && examIds.length">
                            <td colspan="4" class="text-center">
                                <p class="ajax_notice">No student found.</p>
                                <a :href="'admin/student/create?id=' + examIds[0]" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Click here to add Student
                                </a>
                            </td>
                        </tr>
                        <tr x-show="!examIds.length">
                            <td colspan="4" class="text-center"><p class="ajax_notice">Select at least one exam above.</p></td>
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

<!-- Alpine.js v3 — powers the "Book Student for Exam" modal above -->
<script defer src="assets/lib/plugins/alpinejs/alpine.min.js"></script>
<script type="text/javascript">
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
        const source      = <?= json_encode((string)$source, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        return {
            exams   : <?= json_encode($exams, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            examIds : defaultExamId ? [String(defaultExamId)] : [],
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
                if (!scheduleId) {
                    toastr.error('No exam schedule selected.');
                    return;
                }
                this.q        = '';
                this.page     = 1;
                this.rows     = [];
                this.total    = 0;
                this.selected = [];
                this.examIds  = defaultExamId ? [String(defaultExamId)] : [];
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

                const body = new URLSearchParams({exam_schedule_id: scheduleId, source: source});
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
</script>
