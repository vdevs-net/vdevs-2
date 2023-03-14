<?php
define('_MRKEN_CMS',1);
require('../../system/core.php');
if ($user_id) {
	$text = isset($_GET['text']) ? rawurldecode(trim($_GET['text'])) : 'vDevs.net';
	$style = isset($_GET['style']) ? abs(intval($_GET['style'])) : 2;
	$style = ($style > 0 && $style < 3) ? $style : 2;

	if($text != 'vDevs.net'){
		$check = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_paid` WHERE `uid`="'.$user_id.'" AND `type`="logo_facebook" AND `d1`="'.mysql_real_escape_string(mb_strtolower($text)).'" AND `d2`="'.$style.'"'), 0);
		if($check == 0){
			if($datauser['coin'] >= 200){
                mysql_query('INSERT INTO `cms_paid` SET `uid`="'.$user_id.'", `type`="logo_facebook", `d1`="'.mysql_real_escape_string(mb_strtolower($text)).'", `d2`="'.$style.'", `time`="'.time().'"');
				mysql_query('UPDATE `users` SET `coin`=`coin` - 200 WHERE `id`="'.$user_id.'"');
				mysql_query('UPDATE `users` SET `coin`=`coin` + 200 WHERE `id`="2"');
				mysql_query('INSERT INTO `cms_log` SET `uid`="'.$user_id.'", `pid`="2", `type`="4", `text`="200", `time`="'.time().'"');
			} else {
				$text = 'vDevs.net';
			}
		}
	}
} else {
	$text = 'vDevs.net';
	$style = 2;
}
$size = 21;
$angel = 0;
$height = 23;
$font= 'facebook/font.ttf';
$bbox= imagettfbbox($size,$angel,$font,$text);
$width = $bbox[2] + 4;
//tao anh moi
$im = imagecreatetruecolor($width,$height);
//dinh dang mau sac
$white = imagecolorallocate($im,255,255,255);
$fb = imagecolorallocate($im,59,89,152);
$trans = imagecolorallocatealpha($im,255,255,255,127);
imagealphablending($im, FALSE);
imagesavealpha($im,TRUE);

//in van ban
if($style == 1){
	$color = $white;
}else{
	$color = $fb;
}
imagefilledrectangle($im,0,0,$width,$height,$trans);
imagettftext($im,$size,$angel,2,22,$color,$font,$text);

//Xuat
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . time() . '.png"');
imagepng($im);
imagedestroy($im);
