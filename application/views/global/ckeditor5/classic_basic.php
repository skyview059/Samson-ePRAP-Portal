<?php if ($cdn): ?>
    <script src="assets/lib/plugins/ckeditor5/classic/build/ckeditor.js"></script>
    <script src="assets/lib/plugins/ckeditor5/ckfinder/ckfinder.js"></script>
<?php endif; ?>
<style>
    .ck-editor__editable {
        min-height: <?= $min_height; ?>px;
    }
</style>

<script type="text/javascript">
    ClassicEditor
        .create(document.querySelector('<?= $selector; ?>'), {
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
            ckfinder: {
                uploadUrl: "<?php echo site_url('ck-image-upload'); ?>",
            },
            toolbar: {
                items: [
                    'heading', 'fontSize', 'fontColor', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList',
                    'alignment', 'outdent', 'indent', 'insertTable', 'undo', 'redo', 'findAndReplace', 'horizontalLine', 'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            }
        })
        .then(editor => {
            // Registry of CKEditor 5 instances keyed by selector (CKEditor 5 has no CKEDITOR.instances).
            // Call window.CK5.updateSourceElements() before serializing a form.
            window.CK5 = window.CK5 || {};
            window.CK5['<?= $selector; ?>'] = editor;
            window.CK5.updateSourceElements = window.CK5.updateSourceElements || function () {
                Object.keys(window.CK5).forEach(function (key) {
                    const ed = window.CK5[key];
                    if (!ed || typeof ed.updateSourceElement !== 'function') {
                        return;
                    }
                    // If the user is still in "Source" mode, commit that raw HTML first —
                    // otherwise getData()/updateSourceElement() still return the pre-source-edit content.
                    if (ed.plugins && ed.plugins.has('SourceEditing')) {
                        const se = ed.plugins.get('SourceEditing');
                        if (se.isSourceEditingMode) {
                            if (typeof se.updateEditorData === 'function') {
                                se.updateEditorData();
                            } else if (typeof se._updateEditorData === 'function') {
                                se._updateEditorData(); // v35 build: only the private method exists
                            }
                        }
                    }
                    ed.updateSourceElement();
                });
            };
        })
        .catch(error => {
            console.error(error);
        });
</script>