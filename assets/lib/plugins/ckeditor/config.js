CKEDITOR.editorConfig = function (config) {
    config.allowedContent = true;
    config.removeFormatAttributes = '';
    CKEDITOR.dtd.$removeEmpty['i'] = false;


    config.filebrowserBrowseUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl 	= 'assets/lib/plugins/plugins/ckeditor/plugins/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl 	= 'assets/lib/plugins/ckeditor/plugins/kcfinder/upload.php?type=flash';
};