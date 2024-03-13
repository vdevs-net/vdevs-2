<?php
defined('_MRKEN_CMS') or die('ERROR!');

$page_title = 'Chơi oẳn tù tì Online';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/game/', 'Game');

// settings
$gameName = 'Oẳn tù tì Online';
$minCoin = 100;
$maxCoin = 5000;
$receiveRation = 90;
$maxRoom = 5;
$array = array(
    1 => 'Kéo',
    2 => 'Búa',
    3 => 'Bao'
);
$countRoom = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_rps_game` WHERE `user_id`="' . $user_id . '"'), 0);
switch ($mod) {
    case 'create':
        $breadcrumb->add('/game/rock-paper-scissors-online', 'Oẳn tù tì Online');
        $breadcrumb->add('Đặt kèo');
        $tpl_file = 'game::rock-paper-scissors-online';
        $error = false;
        $coin = isset($_POST['coin']) ? abs(intval($_POST['coin'])) : $minCoin;
        $select = isset($_POST['select']) ? abs(intval($_POST['select'])) : 0;
        $tpl_data['success'] = false;
        if (IS_POST && TOKEN_VALID) {
            if ($coin < $minCoin || $coin > $maxCoin || ($coin % 100)) {
                $error = 'Số xu cược không hợp lệ!';
            } elseif ($select < 1 || $select > 3) {
                $error = 'Lựa chọn không hợp lệ';
            } elseif ($datauser['coin'] < $coin) {
                $error = 'Bạn chỉ còn ' . $datauser['coin'] . ' xu, không thể đặt kèo với mức cược ' . $coin . ' xu!';
            } else {
                $flood = functions::antiflood();
                if ($flood) {
                    $error = 'Vui lòng chờ '. $flood . ' giây để chơi tiếp!';
                } elseif ($countRoom >= $maxRoom) {
                    $error = 'Bạn không thể đặt quá ' . $maxRoom . ' kèo cùng lúc!';
                }
            }
            if (!$error) {
                mysql_query('INSERT INTO `cms_rps_game` SET `user_id` = "' . $user_id . '", `choice` = "' . $select . '", `coin` = "' . $coin . '", `time` = "' . SYSTEM_TIME . '"');
                $datauser['coin'] = $datauser['coin'] - $coin;
                mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin'] . '", `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
                $tpl_data['success'] = 'Đặt kèo thành công! Bạn chọn <b>' . $array[$select] . '</b> với mức cược <b>' . $coin . '</b> xu';
                if (mt_rand(1, 10) < 2) {
                    mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="[url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]'. $login .'[/url] vừa đặt kèo trong [b][url=' . SITE_URL . '/game/rock-paper-scissors-online]' . $gameName . '[/url][/b] với mức cược [b]' . $coin . '[/b] xu! Mọi người cùng bắt kèo nào!", `time`="' . SYSTEM_TIME . '"');
                }
                ++$countRoom;
            }
        }
        $tpl_data['coinInput'] = $coin;
        $tpl_data['select'] = $select;
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['game_description'] = 'QUY TẮC:<br />- Dựa trên trò chơi Oẳn tù tì<br/>- Số xu đặt cược từ '. $minCoin .' đến ' . $maxCoin . ' xu và là bội của 100!<br />- Các thành viên khác có thể vào phòng để bắt kèo.<br />- Kèo hòa thì người bắt kèo sẽ mất  ' . (100 - $receiveRation) . '% số xu đặt cược.<br />- Người thua sẽ mất số xu bằng số xu đặt cược, người thắng nhận được '. $receiveRation .'% số xu đặt cược!';
        $tpl_data['items'] = [];
        if ($countRoom) {
            $req = mysql_query('SELECT * FROM `cms_rps_game` WHERE `user_id` = "' . $user_id . '" ORDER BY `time` DESC');
            while ($res = mysql_fetch_assoc($req)) {
                $tpl_data['items'][] = [
                    'time' => functions::display_date($res['time']),
                    'choice' => $array[$res['choice']],
                    'coin' => $res['coin']
                ];
            }
        }
        break;

    case 'room':
        $breadcrumb->add('/game/rock-paper-scissors-online', 'Oẳn tù tì Online');
        $breadcrumb->add('Bắt kèo');
        $error = false;
        if ($id) {
            $req = mysql_query('SELECT `cms_rps_game`.*, `users`.`account`, `users`.`rights` FROM `cms_rps_game` LEFT JOIN `users` ON `cms_rps_game`.`user_id` = `users`.`id` WHERE `cms_rps_game`.`id` = "' . $id . '" AND `cms_rps_game`.`user_id` != "' . $user_id . '"');
            if (mysql_num_rows($req)) {
                $res = mysql_fetch_assoc($req);
                $tpl_file = 'game::rock-paper-scissors-online';
                $tpl_data['formAction'] = 'rock-paper-scissors-online?mod=room&id=' . $res['id'];
                $tpl_data['userProfileUrl'] = SITE_URL . '/profile/' . $res['account'] . '.' . $res['user_id'] . '/';
                $tpl_data['userAccount'] = $res['account'];
                $tpl_data['userClass'] = 'user_' . $res['rights'];
                $tpl_data['userAvatar'] = functions::get_avatar($res['user_id'], 1);
                $tpl_data['roomCoin'] = $res['coin'];
                $tpl_data['roomTime'] = functions::display_date($res['time']);

                $select = isset($_POST['select']) ? abs(intval($_POST['select'])) : 0;
                $choice = (int) $res['choice'];
                $tpl_data['select'] = $select;
                $tpl_data['win'] = $tpl_data['lose'] = $tpl_data['tied'] = false;
                if (IS_POST && TOKEN_VALID) {
                    if ($select < 1 || $select > 3) {
                        $error = 'Lựa chọn không hợp lệ';
                    } elseif ($datauser['coin'] < $res['coin']) {
                        $error = 'Số xu của bạn không đủ để bắt kèo này!';
                    }
                    if (!$error) {
                        if (($select === 1 && $choice === 3) || ($select === 2 && $choice === 1) || ($select === 3 && $choice === 2)) {
                            $tpl_data['win'] = true;
                            $coinExchange = $res['coin'];
                            $datauser['coin'] = $datauser['coin'] + 0.9 * $coinExchange;
                            ++$datauser['game_rps_win'];
                            mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin'] . '", `game_rps_win` = "' . $datauser['game_rps_win'] . '"  WHERE `id` = "' . $user_id . '"');
                            mysql_query('UPDATE `users` SET `game_rps_lose` = (`game_rps_lose` + 1) WHERE `id` = "' . $res['user_id'] . '"');
                            mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="[url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]'. $login .'[/url] đã chiến thắng thắng [url=' . SITE_URL . '/profile/' . $res['account'] . '.'. $res['user_id'] .'/]'. $res['account'] .'[/url] trong [b][url=' . SITE_URL . '/game/rock-paper-scissors-online]' . $gameName . '[/url][/b] và nhận '. (0.9 * $res['coin']) .' xu!", `time`="'. SYSTEM_TIME .'"');
                        } elseif ($select === $choice) {
                            $tpl_data['tied'] = true;
                            $coinExchange = $res['coin'];
                            $datauser['coin'] = $datauser['coin'] - 0.1 * $coinExchange;
                            mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin'] . '"  WHERE `id` = "' . $user_id . '"');
                            mysql_query('UPDATE `users` SET `coin` = (`coin` + ' . $res['coin'] . ') WHERE `id` = "' . $res['user_id'] . '"');
                            mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="[url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]'. $login .'[/url] bắt kèo [url=' . SITE_URL . '/profile/' . $res['account'] . '.'. $res['user_id'] .'/]'. $res['account'] .'[/url] trong [b][url=' . SITE_URL . '/game/rock-paper-scissors-online]' . $gameName . '[/url][/b], kết quả hoà!", `time`="'. SYSTEM_TIME .'"');
                        } else {
                            $tpl_data['lose'] = true;
                            $coinExchange = $res['coin'];
                            $datauser['coin'] = $datauser['coin'] - $coinExchange;
                            ++$datauser['game_rps_lose'];
                            $coinPlus = 1.9 * $res['coin'];
                            mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin'] . '", `game_rps_lose` = "' . $datauser['game_rps_lose'] . '" WHERE `id` = "' . $user_id . '"');
                            mysql_query('UPDATE `users` SET `game_rps_win` = (`game_rps_win` + 1), `coin` = (`coin` + ' . $coinPlus . ') WHERE `id` = "' . $res['user_id'] . '"');
                            mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="[url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]'. $login .'[/url] bắt kèo [url=' . SITE_URL . '/profile/' . $res['account'] . '.'. $res['user_id'] .'/]'. $res['account'] .'[/url] trong [b][url=' . SITE_URL . '/game/rock-paper-scissors-online]' . $gameName . '[/url][/b] nhưng rất tiếc đã thua!", `time`="'. SYSTEM_TIME .'"');
                        }
                        mysql_query('UPDATE `users` SET (`coin` = `coin` + ' . ($coinExchange / 10) . ') WHERE `id` = "2"');
                        mysql_query('DELETE FROM `cms_rps_game` WHERE `id` = "' . $id . '"');
                    }
                }
                $tpl_data['error'] = ($error ? functions::display_error($error) : '');
            } else {
                $error = 'Phòng không tồn tại hoặc không hợp lệ!';
            }
        } else {
            $error = $lng['error_wrong_data'];
        }
        if (!$tpl_file) {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($error);
        }
        break;

    case 'top':
        $breadcrumb->add('/game/rock-paper-scissors-online', 'Oẳn tù tì Online');
        $breadcrumb->add('Top cao thủ');
        $tpl_file = 'game::rock-paper-scissors-online';
        $tpl_data['items'] = [];
        $req = mysql_query('SELECT `id`, `account`, `rights`, `game_rps_win`, `game_rps_lose`, (`game_rps_lose` / `game_rps_win`) as `lose_rate` FROM `users` WHERE `game_rps_win` > 0 ORDER BY `game_rps_win` DESC, `lose_rate` ASC LIMIT 10');
        while ($res = mysql_fetch_assoc($req)) {
            $tpl_data['items'][] = [
                'winCount' => $res['game_rps_win'],
                'winRate' => round(($res['game_rps_win'] / ($res['game_rps_win'] + $res['game_rps_lose'])) * 100, 2) . '%',
                'userAccount' => $res['account'],
                'userProfileUrl' => SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/',
                'userHTMLClass' => 'user_' . $res['rights'],
                'userAvatar' => functions::get_avatar($res['id'])
            ];
        }
        break;
        
    default:
        $breadcrumb->add('Oẳn tù tì Online');
        $mod = false;
        $tpl_file = 'game::rock-paper-scissors-online';
        $tpl_data['countRoom'] = $countRoom;
        $tpl_data['maxRoom'] = $maxRoom;

        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_rps_game` WHERE `user_id` != "' . $user_id . '"'), 0);
        $start = functions::fixStart($start, $total, $kmess);
        $tpl_data['total'] = $total;
        $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('rock-paper-scissors-online?page=', $start, $total, $kmess) : '');
        $tpl_data['items'] = [];
        if ($total) {
            $req = mysql_query('SELECT `cms_rps_game`.`id`, `cms_rps_game`.`user_id`, `cms_rps_game`.`coin`, `cms_rps_game`.`time`, `users`.`account`, `users`.`rights` FROM `cms_rps_game` LEFT JOIN `users` ON `cms_rps_game`.`user_id` = `users`.`id` WHERE  `cms_rps_game`.`user_id` != "' . $user_id . '" ORDER BY `cms_rps_game`.`time` DESC LIMIT ' . $start . ', ' . $kmess);
            while ($res = mysql_fetch_assoc($req)) {
                $tpl_data['items'][] = [
                    'url' => 'rock-paper-scissors-online?mod=room&id=' . $res['id'],
                    'user_profile_url' => SITE_URL . '/profile/' . $res['account'] . '.' . $res['user_id'] . '/',
                    'user_account' => $res['account'],
                    'user_class' => 'user_' . $res['rights'],
                    'user_avatar' => functions::get_avatar($res['user_id'], 1),
                    'coin' => $res['coin'],
                    'time' => functions::display_date($res['time'])
                ];
            }

        }
        break;
}
$tpl_data['action'] = $mod;
$_breadcrumb = $breadcrumb->out();
