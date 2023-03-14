<?php
error_reporting(E_ALL);
set_time_limit(30);
ini_set('display_errors', 1);

function error_image() {
    $image = imagecreatefromstring(base64_decode('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'));

    header('Content-Type: image/png');
    ob_start();
    header('Content-Disposition: inline; filename=missing_image.png');
    imagesavealpha($image, true);
    imagepng($image);
    header('Content-Length: '. ob_get_length());
	ob_end_flush();
    imagedestroy($image);
	exit;
}
// Check referer
$referer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
if ($referer) {
    $ref = parse_url($referer);
    if ($_SERVER['HTTP_HOST'] !== $ref['host']) {
        error_image();
    }
} else {
    error_image();
}

// Main script
$img_data = '';
function resize_image($image,$max_height,$max_width,$transformation,$alignment,$trim,$interlace){
	global $img_data;

	$cur_width = $img_data[0];
	$cur_height = $img_data[1];

	if($trim > 0){
		$ta = imageTrimmedBox($cur_width,$cur_height,$image,$trim);
		if($ta !== false){
			$cur_width = $ta['w'];
			$cur_height = $ta['h'];
		}
	}

	if(($max_height+$max_width) > 0 || $trim > 0 || isset($_GET['crop'])){
		$new = set_dimension($cur_width,$cur_height,$max_width,$max_height,$transformation,$alignment);

		if($trim > 0 && $ta !== false){
			$new['org_x'] += $ta['l'];
			$new['org_y'] += $ta['t'];
		}

		// This is transparency-preserving magic!
		$image_resized = imagecreatetruecolor($new['width'],$new['height']);
		if(($img_data[2] == IMAGETYPE_GIF)){
			$tidx = imagecolortransparent($image);
			$palletsize = imagecolorstotal($image);
			if($tidx >= 0 && $tidx < $palletsize){
				$trnprt_color  = imagecolorsforindex($image, $tidx);
				$tidx  = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($image_resized, 0, 0, $tidx);
				imagecolortransparent($image_resized, $tidx);
			}
		}elseif($img_data[2] == IMAGETYPE_PNG){
			imagealphablending($image_resized, false);
			imagesavealpha($image_resized, true);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
		}

		imagecopyresampled($image_resized, $image, 0, 0, $new['org_x'], $new['org_y'], $new['width'], $new['height'],$new['org_width'], $new['org_height']);
		imagedestroy($image);
	}else{
		$image_resized = $image;
	}

	if($interlace == '_il'){
		imageinterlace($image_resized,1);
	}

	return $image_resized;
}
function set_dimension($imageWidth,$imageHeight,$maxWidth,$maxHeight,$transformation,$alignment){
	global $_GET;
	if(isset($_GET['crop'])){ //V2 API
		$crop_arr = explode(',',$_GET['crop']);
		$new['org_width'] = $crop_arr[0];
		$new['org_height'] = $crop_arr[1];

		$new['width'] = $crop_arr[0];
		$new['height'] = $crop_arr[1];

		$new['org_x'] = $crop_arr[2];
		$new['org_y'] = $crop_arr[3];
	}elseif($transformation == 'fit' || $transformation == 'fitup'){
		if($maxWidth < 1 && $maxHeight < 1){
			$maxWidth = $imageWidth;
			$maxHeight = $imageHeight;
		}

		$maxWidth = ($maxWidth > 0) ? $maxWidth : $maxHeight*100;
		$maxHeight = ($maxHeight > 0) ? $maxHeight : $maxWidth*100;

		$wRatio = $imageWidth / $maxWidth;
		$hRatio = $imageHeight / $maxHeight;
		$maxRatio = max($wRatio, $hRatio);
		if($maxRatio > 1 || $transformation == 'fitup') {
			$new['width'] = $imageWidth / $maxRatio;
			$new['height'] = $imageHeight / $maxRatio;
		}else{
			$new['width'] = $imageWidth;
			$new['height'] = $imageHeight;
		}

		$new['org_width'] = $imageWidth;
		$new['org_height'] = $imageHeight;

		$new['org_x'] = 0;
		$new['org_y'] = 0;
	}elseif($transformation == 'square' || $transformation == 'squaredown'){
		$new['width'] = ($maxWidth > 0) ? $maxWidth : $imageWidth;
		$new['height'] = ($maxHeight > 0) ? $maxHeight : $imageHeight;

		if($transformation == 'squaredown'){
			if($imageWidth <= $new['width']){
				$new['width'] = $imageWidth;
			}
			if($imageHeight <= $new['height']){
				$new['height'] = $imageHeight;
			}
		}

		$wRatio = $imageWidth / $maxWidth;
		$hRatio = $imageHeight / $maxHeight;

		$ratioComputed		= $imageWidth / $imageHeight;
		$cropRatioComputed	= $new['width'] / $new['height'];

		if ($ratioComputed < $cropRatioComputed){
			$new['org_width'] = $imageWidth;
			$new['org_height'] = $imageWidth/$cropRatioComputed;

			$new['org_x'] = 0;
			if($alignment == 't'){
				$new['org_y'] = 0;
			}elseif($alignment == 'b'){
				$new['org_y'] = ($imageHeight - $new['org_height']);
			}else{
				$new['org_y'] = ($imageHeight - $new['org_height']) / 2;
			}
		}elseif($ratioComputed > $cropRatioComputed){
			$new['org_width'] = $imageHeight*$cropRatioComputed;
			$new['org_height'] = $imageHeight;

			if($alignment == 'l'){
				$new['org_x'] = 0;
			}elseif($alignment == 'r'){
				$new['org_x'] = ($imageWidth - $new['org_width']);
			}else{
				$new['org_x'] = ($imageWidth - $new['org_width']) / 2;
			}
			$new['org_y'] = 0;
		}else{
			$new['org_width'] = $imageWidth;
			$new['org_height'] = $imageHeight;

			$new['org_x'] = 0;
			$new['org_y'] = 0;
		}
	}elseif($transformation == 'absolute'){
		$new['org_width'] = $imageWidth;
		$new['org_height'] = $imageHeight;

		$new['width'] = ($maxWidth > 0) ? $maxWidth : $imageWidth;
		$new['height'] = ($maxHeight > 0) ? $maxHeight : $imageHeight;

		$new['org_x'] = 0;
		$new['org_y'] = 0;
	}
	return $new;
}
function imageTrimmedBox($cur_width,$cur_height,$img,$t,$hex=null){
	if($hex == null) $hex = imagecolorat($img, 2, 2); // 2 pixels in to avoid messy edges
	$r = ($hex >> 16) & 0xFF;
	$g = ($hex >> 8) & 0xFF;
	$b = $hex & 0xFF;
	$c = round(($r+$g+$b)/3); // average of rgb is good enough for a default

	$width = $cur_width;
	$height = $cur_height;
	$b_top = 0;
	$b_lft = 0;
	$b_btm = $height - 1;
	$b_rt = $width - 1;

	//top
	for(; $b_top < $height; ++$b_top) {
		for($x = 0; $x < $width; ++$x) {
			$rgb = imagecolorat($img, $x, $b_top);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			if (
				($r < $c-$t || $r > $c+$t) && // red not within tolerance of trim colour
				($g < $c-$t || $g > $c+$t) && // green not within tolerance of trim colour
				($b < $c-$t || $b > $c+$t) // blue not within tolerance of trim colour
			){
				break 2;
			}
		}
	}

	// return false when all pixels are trimmed
	if ($b_top == $height) return false;

	// bottom
	for(; $b_btm >= 0; --$b_btm) {
		for($x = 0; $x < $width; ++$x) {
			$rgb = imagecolorat($img, $x, $b_btm);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			if (
				($r < $c-$t || $r > $c+$t) && // red not within tolerance of trim colour
				($g < $c-$t || $g > $c+$t) && // green not within tolerance of trim colour
				($b < $c-$t || $b > $c+$t) // blue not within tolerance of trim colour
			){
				break 2;
			}
		}
	}

	// left
	for(; $b_lft < $width; ++$b_lft) {
		for($y = $b_top; $y <= $b_btm; ++$y) {
			$rgb = imagecolorat($img, $b_lft, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			if (
				($r < $c-$t || $r > $c+$t) && // red not within tolerance of trim colour
				($g < $c-$t || $g > $c+$t) && // green not within tolerance of trim colour
				($b < $c-$t || $b > $c+$t) // blue not within tolerance of trim colour
			){
				break 2;
			}
		}
	}

	// right
	for(; $b_rt >= 0; --$b_rt) {
		for($y = $b_top; $y <= $b_btm; ++$y) {
			$rgb = imagecolorat($img, $b_rt, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			if (
				($r < $c-$t || $r > $c+$t) && // red not within tolerance of trim colour
				($g < $c-$t || $g > $c+$t) && // green not within tolerance of trim colour
				($b < $c-$t || $b > $c+$t) // blue not within tolerance of trim colour
			){
				break 2;
			}
		}
	}

	$b_btm++;
	$b_rt++;
	return array(
		'l' => $b_lft,
		't' => $b_top,
		'r' => $b_rt,
		'b' => $b_btm,
		'w' => $b_rt - $b_lft,
		'h' => $b_btm - $b_top
	);
}

function check_utf8($str){
	$len = strlen($str);
	for($i = 0; $i < $len; $i++){
		$c = ord($str[$i]);
		if($c > 128){
			if(($c > 247)) return false;
			elseif($c > 239) $bytes = 4;
			elseif($c > 223) $bytes = 3;
			elseif($c > 191) $bytes = 2;
			else return false;
			if(($i + $bytes) > $len) return false;
			while ($bytes > 1) {
				$i++;
				$b = ord($str[$i]);
				if($b < 128 || $b > 191) return false;
				$bytes--;
			}
		}
	}
	return true;
}
if (empty($_GET['url'])) {
    error_image();
} else {
	$h = (empty($_GET['h']) OR !ctype_digit($_GET['h'])) ? '0' : $_GET['h'];
	$w = (empty($_GET['w']) OR !ctype_digit($_GET['w'])) ? '0' : $_GET['w'];
	$t = (empty($_GET['t']) OR !in_array($_GET['t'],array('fit','fitup','square','squaredown','absolute'))) ? 'fit' : $_GET['t'];
	$a = (empty($_GET['a']) OR !in_array($_GET['a'],array('t','b','r','l'))) ? 'c' : $_GET['a'];
	$q = (empty($_GET['q']) OR !ctype_digit($_GET['q']) OR $_GET['q'] > 100 OR $_GET['q'] < 0) ? '85' : $_GET['q'];
	$il = (isset($_GET['il'])) ? '_il' : '';

	//Trim
	if (isset($_GET['trim'])) {
		// if tolerance ($_GET['trim']) isn't a number between 0 - 255 use 10 as default
		if (empty($_GET['trim']) || !ctype_digit($_GET['trim']) || $_GET['trim'] < 0 || $_GET['trim'] > 255) {
			$s = 10;
		} else {
			$s = (int)$_GET['trim'];
		}
	} else {
		$s = 0;
	}

    $parts = parse_url($_GET['url']);

	//IDN-rewriting
	if(idn_to_ascii($parts['host']) == ''){
		$parts['host'] = utf8_encode($parts['host']);
	}

	if(!isset($parts['scheme'])){
		error_image();
	}

	$_GET['url'] = $parts['scheme'] . '://' . idn_to_ascii($parts['host']);
	if(isset($parts['path'])){
		$parts['path'] = (check_utf8($parts['path']) === false) ? utf8_encode($parts['path']) : $parts['path'];
		$_GET['url'] .= $parts['path'];
		$_GET['url'] .= isset($parts['query']) ? '?' . $parts['query'] : '';
	}

	$_GET['url'] = str_replace(' ', '%20', $_GET['url']);
	$fname = tempnam('/dev/shm','imo_');
    $curl_file = fopen($fname, 'w');
    $options = array(
		CURLOPT_FILE => $curl_file,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_URL => $_GET['url'],
		CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
		CURLOPT_TIMEOUT => 10,
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; ImageFetcher/5.6; +http://vdevs.net)',
	);

	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$return = curl_exec($ch);
    curl_close($ch);
    fclose($curl_file);

	if ($return === false){
		unlink($fname);
		error_image();
	}
    $image = false;

	$img_data = @getimagesize($fname);
    if (isset($img_data[2]) && in_array($img_data[2], array(IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG))) {
		if ($img_data[0] * $img_data[1] <= 7990272) {
            switch ($img_data[2]) {
                case IMAGETYPE_JPEG:
                    if (function_exists('exif_read_data')) {
                        $img_data['exif'] = exif_read_data($fname);
                    }

                    $image = imagecreatefromjpeg($fname);
                    break;

                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($fname);
                    break;

                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($fname);
                    break;
            }
        }
	}
    unlink($fname);
    if ($image === false) {
        error_image();
    }
	//Change orientation on EXIF-data
	if (isset($img_data['exif'])) {
		if(isset($img_data['exif']['Orientation']) && !empty($img_data['exif']['Orientation'])){
			switch($img_data['exif']['Orientation']){
				case 8:
					$image = imagerotate($image,90,0);

					//Change source dimensions
					$temp_w = $img_data[0];
					$img_data[0] = $img_data[1];
					$img_data[1] = $temp_w;
					unset($temp_w);
				break;
				case 3:
					$image = imagerotate($image,180,0);
				break;
				case 6:
					$image = imagerotate($image,-90,0);

					//Change source dimensions
					$temp_w = $img_data[0];
					$img_data[0] = $img_data[1];
					$img_data[1] = $temp_w;
					unset($temp_w);
				break;
			}
		}
	}

	//Resize only when needed
	if($h > 0 || $w > 0 || $s > 0 || $il == '_il' || isset($_GET['crop'])){
		$image = resize_image($image,$h,$w,$t,$a,$s,$il);
	}

	$output_formats = array('png' => 'image/png', 'jpg' => 'image/jpeg', 'gif' => 'image/gif');
	if (isset($_GET['output']) && isset($output_formats[$_GET['output']])) {
		$img_data['mime'] = $output_formats[$_GET['output']];
	}

	header('Expires: ' . gmdate('D, d M Y H:i:s', (time()+2678400)) . ' GMT'); //31 days
	header('Cache-Control: max-age=2678400'); //31 days
    header('Content-Type: ' . $img_data['mime']);
	ob_start();
    switch($img_data['mime']){
		case 'image/jpeg':
			header('Content-Disposition: inline; filename=image.jpg');
			imagejpeg($image, NULL, $q);
		break;

		case 'image/gif':
			header('Content-Disposition: inline; filename=image.gif');
			imagegif($image);
		break;

		case 'image/png':
			header('Content-Disposition: inline; filename=image.png');
			imagesavealpha($image,true);
			imagepng($image);
		break;
	}
    header('Content-Length: '. ob_get_length());
	ob_end_flush();
	exit;
}
