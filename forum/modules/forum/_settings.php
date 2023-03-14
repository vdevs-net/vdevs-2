<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$lng = array_merge($lng, core::load_lng('forum'));
if (isset($_SESSION['ref'])) {
    unset($_SESSION['ref']);
}
// check access rights
if (!$set['mod_forum'] && $rights < 7) {
    $module_error = $lng['forum_closed'];
} elseif ($set['mod_forum'] == 1 && !$user_id) {
    $module_error = $lng['access_guest_forbidden'];
}

$headmod = 'forum';
$page_title = $lng['forum'];

// The list of file extensions allowed for unloading
// Archive
$ext_arch = array('zip','rar','7z','tar','gz','apk');
// Audio
//$ext_audio = array('mp3','amr');
$ext_audio = array();
// Text
$ext_doc = array('txt','pdf','doc','docx','rtf','djvu','xls','xlsx');
// Java
$ext_java = array('sis','sisx','apk');
// image
//$ext_pic = array('jpg','jpeg','png');
$ext_pic = array();
// SIS
$ext_sis = array('sis','sisx');
// video
//$ext_video = array('3gp','avi','flv','mpeg','mp4');
$ext_video = array();
// soft Windows
$ext_win = array('exe','msi');
// other
$ext_other = array('wmf');

function get_breadcrumb($refid, $add = array(), &$_breadcrumb, $scope = 0)
{
    global $lng;

    $forum = new forum();
    $tree = $forum->get_parents($refid);
    if ($add) {
        $tree[] = $add;
    }

    $breadcrumb = new breadcrumb($scope);
    $breadcrumb->add($tree);
    $_breadcrumb = $breadcrumb->out();
}
// The backlight function query results
function ReplaceKeywords($search, $text){
    $search = str_replace('*', '', $search);
    return mb_strlen($search) < 3 ? $text : preg_replace('|(' . preg_quote($search, '/') . ')|siu', '<span style="background-color:#FFFF33">$1</span>', $text);
}