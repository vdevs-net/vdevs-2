<?php
define('_MRKEN_CMS',1);
require('../../system/core.php');
if ($user_id) {
	$text1 = isset($_GET['text1']) ? mb_strtoupper(rawurldecode(trim($_GET['text1']))) : 'VDEVS';
	$text2 = isset($_GET['text2']) ? mb_strtoupper(rawurldecode(trim($_GET['text2']))) : '.NET';
	$text3 = isset($_GET['text3']) ? rawurldecode(trim($_GET['text3'])) : 'MXH vDevs';
	$position = isset($_GET['position']) ? abs(intval($_GET['position'])) : 60;

	if ($text1 != 'VDEVS' || $text2 != '.NET') {
		$check = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_paid` WHERE `uid`="' . $user_id . '" AND `type`="logo_zencms" AND `d1`="' . mysql_real_escape_string($text1) . '" AND `d2`="' . mysql_real_escape_string($text2) . '" AND `d3`="' . mysql_real_escape_string($text3) . '"'), 0);
		if($check == 0){
			if($datauser['coin'] >= 200){
				mysql_query('INSERT INTO `cms_paid` SET `uid`="' . $user_id . '", `type`="logo_zencms", `d1`="' . mysql_real_escape_string($text1) . '", `d2`="'.mysql_real_escape_string($text2).'", `d3`="' . mysql_real_escape_string($text3) . '",`time`="'.time().'"');
				mysql_query('UPDATE `users` SET `coin` = (`coin` - 200) WHERE `id`="' . $user_id . '"');
				mysql_query('UPDATE `users` SET `coin` = (`coin` + 200) WHERE `id`="2"');
				mysql_query('INSERT INTO `cms_log` SET `uid`="' . $user_id . '", `pid`="2", `type`="4", `text`="200",`time`="'.time().'"');
			} else {
				$text3 = 'Bạn không đủ xu!';
			}
		}
	}
} else {
	$text1 = 'VDEVS';
	$text2 = '.NET';
	$text3 = 'MXH vDevs';
	$position = 60;
}
//gia tri
$size = 30;
$size1 = 15;
$angel = 0;
$cao = 50;
$text = $text1.''.$text2;
$font= 'zencms/font.TTF';
$font1 = 'zencms/DaLat.TTF';
$bird = imagecreatefrompng('zencms/logo.png');
$bbox1 = imagettfbbox($size,$angel,$font,$text1);
$bbox= imagettfbbox($size,$angel,$font,$text);
$bbox3 = imagettfbbox($size1,$angel,$font1,$text3);
$rong= $bbox[2] + 10;
$w = $bbox1[2] + 2;
$w1 = $bbox3[2] + 1;
//tao anh moi
$im = imagecreatetruecolor($rong,$cao);
//dinh dang mau sac
$white = imagecolorallocate($im,255,255,255);
$red= imagecolorallocate($im,117,214,19);
$trans = imagecolorallocatealpha($im,255,255,255,127);
$xanh = imagecolorallocate($im,89,90,88);
imagealphablending($im, FALSE);
imagesavealpha($im,TRUE);

//in van ban
imagefilledrectangle($im,0,0,$rong,$cao,$trans);
imagecopyresized($im,$bird,($w1+$position+2),33,0,0,20,12,imagesx($bird),imagesy($bird));
imagettftext($im,$size,$angel,2,30,$red,$font,$text1);
imagettftext($im,$size,$angel,($w+3),30,$xanh,$font,$text2);
imagettftext($im,$size1,$angel,$position,45,$xanh,$font1,$text3);



//Xuat
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.time().'.png"');
imagepng($im);
imagedestroy($im);
