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
                uploadUrl: "<?php echo site_url('assets/lib/ckeditor5/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'); ?>",
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
        .catch(error => {
            console.error(error);
        });
</script>