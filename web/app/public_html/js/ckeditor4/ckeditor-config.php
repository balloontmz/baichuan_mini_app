<?php
$type = @$_GET['type'];
?>
/**
* @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
* For licensing, see LICENSE.md or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config ) {
// Define changes to default configuration here. For example:
config.language = 'zh-cn';
<?php if($type=="officialdoc"){?>
    config.toolbar = [
    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-','Find', 'Replace','-','Image','Table','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
    { name: 'colors', items: [ 'Bold', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
    { name: 'styles', items: [ 'Font' ] },
    { name: 'tools', items: [ 'Maximize'] }
    ];
    config.tabSpaces = "6";
    config.contentsCss=[ '/css/document-editor.css' ];
    config.bodyClass='document-editor';config.font_names = '宋体/SimSun;仿宋/FangSong;黑体/SimHei;'
<?php }else if($type=="list"){?>
    config.toolbar = [
    { name: 'paragraph', items: [ 'Outdent', 'Indent','Find', 'Replace','NumberedList'] },
    { name: 'tools', items: [ 'Maximize'] }
    ];
    config.tabSpaces = "6";
<?php }else if($type=="form"){?>
    config.toolbar = [
    { name: 'paragraph', items: [ 'Table', 'Image'] },
    { name: 'tools', items: [ 'Maximize'] }
    ];
    config.tabSpaces = "6";
<?php }else{?>
config.toolbar = [

{ name: 'styles', items: [ 'Format', 'FontSize' ] },
{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',  '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock','-','TextColor', 'BGColor' ] },
{ name: 'colors', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule',  ] },


{ name: 'document', items: [  'Find', 'Replace'] },
{ name: 'tools', items: [ 'Maximize'] }
];
<?php } ?>
config.removeDialogTabs = 'image:advanced;link:advanced';

};
