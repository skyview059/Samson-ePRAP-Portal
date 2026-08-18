<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<section class="content-header">
    <h1> SCA Feedback <small>Domains &amp; Statements</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">SCA Feedback</li>
    </ol>
</section>

<section class="content">

    <!-- Domains -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Feedback Domains
            <button type="button" class="btn btn-primary btn-xs pull-right js-domain-add"><i class="fa fa-plus"></i> Add Domain</button>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="40">#ID</th>
                            <th width="280">Name</th>
                            <th>Standard for marking</th>
                            <th class="text-center" width="60">Sort</th>
                            <th class="text-center" width="90">Statements</th>
                            <th class="text-center" width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($domains as $domain) { ?>
                            <tr>
                                <td><?php echo $domain->id; ?></td>
                                <td><?php echo html_escape($domain->name); ?></td>
                                <td><?php echo character_limiter(strip_tags($domain->standard), 130); ?></td>
                                <td class="text-center"><?php echo $domain->sort_order; ?></td>
                                <td class="text-center"><span class="badge"><?php echo $domain->statement_count; ?></span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-default js-preview" data-type="domain" data-id="<?php echo $domain->id; ?>" title="Preview"><i class="fa fa-fw fa-eye"></i></button>
                                    <button type="button" class="btn btn-xs btn-default js-domain-edit" data-id="<?php echo $domain->id; ?>" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                    <button type="button" class="btn btn-xs btn-danger js-domain-delete" data-id="<?php echo $domain->id; ?>" title="Delete"><i class="fa fa-fw fa-times"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statements -->
    <div class="panel panel-default">
        <div class="panel-heading">Feedback Statements</div>
        <div class="panel-body">
            <ul class="nav nav-tabs" id="statementTabs">
                <?php foreach ($domains as $i => $domain) { ?>
                    <li class="<?php echo $i === 0 ? 'active' : ''; ?>">
                        <a href="#domain-tab-<?php echo $domain->id; ?>" data-toggle="tab"><?php echo html_escape($domain->name); ?></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content" style="padding-top: 15px;">
                <?php foreach ($domains as $i => $domain) { ?>
                    <div class="tab-pane <?php echo $i === 0 ? 'active' : ''; ?>" id="domain-tab-<?php echo $domain->id; ?>">
                        <p class="text-right">
                            <button type="button" class="btn btn-primary btn-xs js-statement-add" data-domain="<?php echo $domain->id; ?>"><i class="fa fa-plus"></i> Add Statement</button>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50">No.</th>
                                        <th>Subject</th>
                                        <th class="text-center" width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statements as $statement) { if ((int) $statement->domain_id !== (int) $domain->id) { continue; } ?>
                                        <tr>
                                            <td class="text-center"><?php echo $statement->sl_no; ?></td>
                                            <td><?php echo html_escape($statement->subject); ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-default js-preview" data-type="statement" data-id="<?php echo $statement->id; ?>" title="Preview"><i class="fa fa-fw fa-eye"></i></button>
                                                <button type="button" class="btn btn-xs btn-default js-statement-edit" data-id="<?php echo $statement->id; ?>" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                                <button type="button" class="btn btn-xs btn-danger js-statement-delete" data-id="<?php echo $statement->id; ?>" title="Delete"><i class="fa fa-fw fa-times"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- Domain Modal -->
<div class="modal fade" id="domainModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="domainModalTitle">Add Domain</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="domain_id" value="0" />
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="domain_name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="domain_name" maxlength="150" placeholder="e.g. Domain 1 - Data gathering and diagnosis" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="domain_sort">Sort Order</label>
                            <input type="number" class="form-control" id="domain_sort" min="0" value="0" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Standard for marking (passing level)</label>
                    <textarea id="domain_standard"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary js-save-btn" id="domainSaveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Statement Modal -->
<div class="modal fade" id="statementModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="statementModalTitle">Add Statement</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="statement_id" value="0" />
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="statement_domain">Domain <span class="text-danger">*</span></label>
                            <select class="form-control" id="statement_domain">
                                <?php foreach ($domains as $domain) { ?>
                                    <option value="<?php echo $domain->id; ?>"><?php echo html_escape($domain->name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="statement_sl_no">Statement No. <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="statement_sl_no" min="1" value="1" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="statement_subject">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="statement_subject" maxlength="255" />
                </div>
                <div class="form-group">
                    <label>Description (educator notes)</label>
                    <textarea id="statement_description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary js-save-btn" id="statementSaveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="previewModalTitle">Preview</h4>
            </div>
            <div class="modal-body" id="previewModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/lib/plugins/ckeditor5/classic/build/ckeditor.js"></script>
<style>
    .ck-editor__editable { min-height: 250px; }
    /* keep CKEditor balloons above the bootstrap modal */
    .ck.ck-balloon-panel { z-index: 10600 !important; }
</style>
<script type="text/javascript">
(function ($) {
    'use strict';

    var DOMAINS    = <?php echo json_encode($domains, $json_flags); ?>;
    var STATEMENTS = <?php echo json_encode($statements, $json_flags); ?>;

    var URL_DOMAIN_SAVE      = '<?php echo site_url(Backend_URL . 'feedback/domain_save'); ?>';
    var URL_DOMAIN_DELETE    = '<?php echo site_url(Backend_URL . 'feedback/domain_delete'); ?>/';
    var URL_STATEMENT_SAVE   = '<?php echo site_url(Backend_URL . 'feedback/statement_save'); ?>';
    var URL_STATEMENT_DELETE = '<?php echo site_url(Backend_URL . 'feedback/statement_delete'); ?>/';

    var domainById = {}, statementById = {};
    DOMAINS.forEach(function (d) { domainById[d.id] = d; });
    STATEMENTS.forEach(function (s) { statementById[s.id] = s; });

    // Bootstrap modal focus-trap blocks CKEditor balloon inputs (link URL etc.)
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    var CK_CONFIG = {
        htmlSupport: {
            allow: [{ name: /.*/, attributes: true, classes: true, styles: true }]
        },
        toolbar: {
            items: [
                'heading', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList',
                'outdent', 'indent', 'insertTable', 'undo', 'redo', 'horizontalLine', 'sourceEditing'
            ],
            shouldNotGroupWhenFull: true
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
            ]
        }
    };

    var editors = {};       // textarea id -> CKEditor instance
    var pendingHtml = {};   // textarea id -> html to load once editor is ready

    function initEditor(id) {
        ClassicEditor
            .create(document.getElementById(id), CK_CONFIG)
            .then(function (editor) {
                editors[id] = editor;
                editor.setData(pendingHtml[id] || '');
            })
            .catch(function (error) { console.error(error); });
    }

    function destroyEditor(id) {
        if (editors[id]) {
            editors[id].destroy().catch(function (error) { console.error(error); });
            delete editors[id];
        }
    }

    function editorData(id) {
        return editors[id] ? editors[id].getData() : (pendingHtml[id] || '');
    }

    $('#domainModal').on('shown.bs.modal', function () { initEditor('domain_standard'); });
    $('#domainModal').on('hidden.bs.modal', function () { destroyEditor('domain_standard'); });
    $('#statementModal').on('shown.bs.modal', function () { initEditor('statement_description'); });
    $('#statementModal').on('hidden.bs.modal', function () { destroyEditor('statement_description'); });

    function reloadKeepingTab(domainId) {
        if (domainId) {
            window.location.hash = 'domain-tab-' + domainId;
        }
        setTimeout(function () { window.location.reload(); }, 600);
    }

    // re-activate the tab stored in the url hash after a reload
    if (window.location.hash) {
        var $tabLink = $('#statementTabs a[href="' + window.location.hash + '"]');
        if ($tabLink.length) { $tabLink.tab('show'); }
    }

    function ajaxSave($btn, url, payload, domainId) {
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(url, payload, null, 'json')
            .done(function (res) {
                if (res.status === 'success') {
                    toastr.success(res.message);
                    reloadKeepingTab(domainId);
                } else {
                    toastr.error(res.message);
                    $btn.prop('disabled', false).text('Save');
                }
            })
            .fail(function () {
                toastr.error('Request failed. Please try again.');
                $btn.prop('disabled', false).text('Save');
            });
    }

    function ajaxDelete(url, domainId) {
        $.post(url, {}, null, 'json')
            .done(function (res) {
                if (res.status === 'success') {
                    toastr.success(res.message);
                    reloadKeepingTab(domainId || res.domain_id);
                } else {
                    toastr.error(res.message);
                }
            })
            .fail(function () { toastr.error('Request failed. Please try again.'); });
    }

    /* ---------------- Domain CRUD ---------------- */

    function openDomainModal(row) {
        $('#domainModalTitle').text(row ? 'Edit Domain' : 'Add Domain');
        $('#domain_id').val(row ? row.id : 0);
        $('#domain_name').val(row ? row.name : '');
        $('#domain_sort').val(row ? row.sort_order : nextDomainSort());
        pendingHtml['domain_standard'] = row ? row.standard : '';
        $('#domainModal').modal('show');
    }

    function nextDomainSort() {
        var max = 0;
        DOMAINS.forEach(function (d) { max = Math.max(max, parseInt(d.sort_order, 10) || 0); });
        return max + 1;
    }

    $('.js-domain-add').on('click', function () { openDomainModal(null); });

    $(document).on('click', '.js-domain-edit', function () {
        var row = domainById[$(this).data('id')];
        if (row) { openDomainModal(row); }
    });

    $('#domainSaveBtn').on('click', function () {
        var name = $.trim($('#domain_name').val());
        if (!name) { toastr.error('Domain name is required'); return; }
        ajaxSave($(this), URL_DOMAIN_SAVE, {
            id:         $('#domain_id').val(),
            name:       name,
            sort_order: $('#domain_sort').val() || 0,
            standard:   editorData('domain_standard')
        }, 0);
    });

    $(document).on('click', '.js-domain-delete', function () {
        var row = domainById[$(this).data('id')];
        if (!row) { return; }
        if (!confirm('Delete "' + row.name + '"?\n\nAll ' + row.statement_count + ' feedback statement(s) under this domain will be deleted too!')) { return; }
        ajaxDelete(URL_DOMAIN_DELETE + row.id, 0);
    });

    /* ---------------- Statement CRUD ---------------- */

    function nextSlNo(domainId) {
        var max = 0;
        STATEMENTS.forEach(function (s) {
            if (parseInt(s.domain_id, 10) === parseInt(domainId, 10)) {
                max = Math.max(max, parseInt(s.sl_no, 10) || 0);
            }
        });
        return max + 1;
    }

    function openStatementModal(row, domainId) {
        $('#statementModalTitle').text(row ? 'Edit Statement' : 'Add Statement');
        $('#statement_id').val(row ? row.id : 0);
        $('#statement_domain').val(row ? row.domain_id : domainId);
        $('#statement_sl_no').val(row ? row.sl_no : nextSlNo(domainId));
        $('#statement_subject').val(row ? row.subject : '');
        pendingHtml['statement_description'] = row ? row.description : '';
        $('#statementModal').modal('show');
    }

    // suggest the next number when the domain is switched on a new statement
    $('#statement_domain').on('change', function () {
        if (parseInt($('#statement_id').val(), 10) === 0) {
            $('#statement_sl_no').val(nextSlNo($(this).val()));
        }
    });

    $(document).on('click', '.js-statement-add', function () {
        openStatementModal(null, $(this).data('domain'));
    });

    $(document).on('click', '.js-statement-edit', function () {
        var row = statementById[$(this).data('id')];
        if (row) { openStatementModal(row, row.domain_id); }
    });

    $('#statementSaveBtn').on('click', function () {
        var subject  = $.trim($('#statement_subject').val());
        var domainId = $('#statement_domain').val();
        if (!subject) { toastr.error('Subject is required'); return; }
        ajaxSave($(this), URL_STATEMENT_SAVE, {
            id:          $('#statement_id').val(),
            domain_id:   domainId,
            sl_no:       $('#statement_sl_no').val(),
            subject:     subject,
            description: editorData('statement_description')
        }, domainId);
    });

    $(document).on('click', '.js-statement-delete', function () {
        var row = statementById[$(this).data('id')];
        if (!row) { return; }
        if (!confirm('Delete statement No. ' + row.sl_no + ' "' + row.subject + '" ?')) { return; }
        ajaxDelete(URL_STATEMENT_DELETE + row.id, row.domain_id);
    });

    /* ---------------- Preview ---------------- */

    $(document).on('click', '.js-preview', function () {
        var type = $(this).data('type');
        var row  = (type === 'domain') ? domainById[$(this).data('id')] : statementById[$(this).data('id')];
        if (!row) { return; }
        if (type === 'domain') {
            $('#previewModalTitle').text(row.name);
            $('#previewModalBody').html('<h4>Standard for marking</h4>' + (row.standard || '<p class="text-muted">Empty</p>'));
        } else {
            var domain = domainById[row.domain_id] || { name: '' };
            $('#previewModalTitle').text(row.sl_no + '. ' + row.subject);
            $('#previewModalBody').html('<p class="text-muted">' + domain.name + '</p>' + (row.description || '<p class="text-muted">Empty</p>'));
        }
        $('#previewModal').modal('show');
    });

})(jQuery);
</script>
