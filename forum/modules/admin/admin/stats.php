<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

switch ($mod) {
    case 'robots':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Robots');
        $count = mysql_result(mysql_query('SELECT COUNT(DISTINCT `robot`) FROM `counter` WHERE `robot` != "";'), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = '';
        $tpl_data['total'] = $count;
        if ($count > 0) {
            $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(`robot`) as `hit` FROM `counter` WHERE `robot` != "" GROUP BY `robot`) as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
            while ($arr = mysql_fetch_assoc($req)) {
                $tpl_data['items'][] = [
                    'content' => '<div><img src="' . SITE_URL . '/assets/images/robot.png" alt="."/> <a href="stats?mod=robot_types&amp;robot='.$arr['robot'].'">'.$arr['robot'].'</a></div><div class="sub">Lượt truy cập: '.$arr['hit'] . '</div>'
                ];
            }
        }
        break;
    case 'robot_types':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('/admin/stats?mod=robots', 'Robots');

        $robot = isset($_GET['robot']) ? htmlspecialchars($_GET['robot']) : FALSE;
        if ($robot) {
            $breadcrumb->add($robot);
            $count = mysql_result(mysql_query('SELECT COUNT(DISTINCT `robot_type`) FROM `counter` WHERE `robot` = "' . mysql_real_escape_string($robot) . '"'), 0);
            $tpl_file = 'admin::stats.list';
            $tpl_data['items'] = [];
            $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=siteadr&site=' . $site . '&page=', $start, $count, $kmess) : '');
            $tpl_data['total'] = $count;
            if ($count) {
                $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(`robot_type`) as `hit` FROM `counter` WHERE `robot` = "' . mysql_real_escape_string($robot) . '" GROUP BY `robot_type`) as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
                while ($arr = mysql_fetch_assoc($req)) {
                    $tpl_data['items'][] = [
                        'content' => '<div><img src="' . SITE_URL . '/assets/images/robot.png" alt="."/> <b>'.$arr['robot_type'].'</b></div><div class="sub">Lượt truy cập: '.$arr['hit'].'</div>'
                    ];
                }
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_wrong_data'];
        }
        break;
    case 'hosts':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Host');
        $count = mysql_result(mysql_query('SELECT COUNT(*) FROM `counter` WHERE `robot` = "" AND `host` != "0";'), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=hosts&page=', $start, $count, $kmess) : '');
        $tpl_data['total'] = $count;
        if ($count > 0) {
            $req = mysql_query('SELECT * FROM `counter` WHERE `robot` = "" AND `host` != "0" LIMIT ' . $start . ' , ' . $kmess);
            while ($arr = mysql_fetch_assoc($req)) {
                $count_view = mysql_result(mysql_query('SELECT COUNT(*) FROM `counter` WHERE `browser` = "' . mysql_real_escape_string($arr['browser']) . '" AND `ip` = "' . $arr['ip'] . '"') , 0);

                $time = date('H:i', $arr['date']);
                $tpl_data['items'][] = [
                    'content' => '<div><b>' . $time . '</b> - ' . htmlspecialchars($arr['browser']) . '</div>' .
                        '<div class="sub">Ip: <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip='.$arr['ip'].'">' . $arr['ip'] . '</a> <a href="' . SITE_URL . '/' . $set['admp'] . '/ip-whois?ip=' . $arr['ip'] . '" title = "WhoIS ip">[?]</a>' . ($arr['ip_via_proxy'] ? ' | <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip='.$arr['ip_via_proxy'].'">'.$arr['ip_via_proxy'].'</a> <a href="' . SITE_URL . '/' . $set['admp'] . '/ip-whois?ip='.$arr['ip_via_proxy'].'" title = "WhoIS ip">[?]</a>' : '') . ' | Lượt truy cập: '.$count_view.'</div>'
                ];

            }

        }
        break;
    case 'point_in':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Điểm vào');
        $count = mysql_result(mysql_query('SELECT COUNT(DISTINCT `pop`) FROM `counter` WHERE `robot` = "" AND `host` != "0";'), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=point_in&page=', $start, $count, $kmess) : '');
        $tpl_data['total'] = $count;
        if($count){
            $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(`pop`) as `hit` FROM `counter` WHERE `robot` = "" AND `host` != "0" GROUP BY `pop` ORDER BY `aid` DESC LIMIT ' . $start . ', ' . $kmess . ') as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
            while ($arr = mysql_fetch_array($req)) {
                $tpl_data['items'][] = [
                    'content' => ($arr['pop'] !== '/' ? '<div><b>' . functions::display_date($arr['date']) . '</b> | Tiêu đề: ' . htmlspecialchars($arr['head']) . '</div><div class="sub">Địa chỉ: <a href="' . htmlspecialchars($arr['pop']) . '">' . htmlspecialchars($arr['pop']) . '</a> | ' : '<div><b>' . functions::display_date($arr['date']) . '</b> | <a href="'.$arr['pop'].'">Trang chủ</a></div><div class="sub">') . 'Lượt xem: '.$arr['hit'].'</div>'
                ];

            }

        }
        break;
    case 'users':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Người dùng');
        $count = mysql_result(mysql_query('SELECT COUNT(DISTINCT `user`) FROM `counter`;'), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=users&page=', $start, $count, $kmess) : '');
        $tpl_data['total'] = $count;
        if($count){
            $req = mysql_query('SELECT MAX(`counter`.`hits`) as `aid`, COUNT(`counter`.`user`) as `hit`, `users`.`id`, `users`.`account`, `users`.`rights`, `users`.`browser`, `users`.`ip`, `users`.`ip_via_proxy`, `users`.`lastdate`, `users`.`status` FROM `counter` LEFT JOIN `users` ON `counter`.`user` = `users`.`id` GROUP BY `counter`.`user` ORDER BY `hit` DESC LIMIT ' . $start . ',' . $kmess);
            while ($arr = mysql_fetch_assoc($req)) {
                $arg = array (
                    'stshide' => 1,
                    'header' => 'Lượt truy cập: ' . $arr['hit']
                );
                $tpl_data['items'][] = [
                    'content' => functions::display_user($arr, $arg)
                ];
            }
        }
        break;
    case 'referer':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Nguồn chuyển hướng');

        $my_url = parse_url(SITE_URL);
        $count = mysql_result(mysql_query('SELECT COUNT(DISTINCT `site`) FROM `counter` WHERE `site` NOT LIKE "%'.$my_url['host'].'"'), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=referer&page=', $start, $count, $kmess) : '');
        $tpl_data['total'] = $count;
        if ($count) {
            $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(`site`) as `hit` FROM `counter` WHERE `site` NOT LIKE "%' . $my_url['host'] . '" GROUP BY `site` ORDER BY `aid` DESC LIMIT ' . $start . ', ' . $kmess . ') as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
            while ($arr = mysql_fetch_assoc($req)) {
                $tpl_data['items'][] = [
                    'content' => '<div><img src="' . SITE_URL . '/assets/images/url.png" alt="."/> <a href="stats?mod=siteadr&site=' . htmlspecialchars($arr['site']) . '">' . htmlspecialchars($arr['site']) . '</a></div><div class="sub">'.functions::display_date($arr['date']).' | Lượt xem: '.$arr['hit'].'</div>'
                ];
            }
        }
        break;
    case 'siteadr':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('/admin/stats?mod=referer', 'Nguồn chuyển hướng');

        $site = isset($_GET['site']) ? htmlspecialchars($_GET['site']) : FALSE;
        if ($site) {
            $breadcrumb->add($site);
            $count = mysql_result(mysql_query("SELECT COUNT(DISTINCT `ref`) FROM `counter` WHERE `site` = '".mysql_real_escape_string($site)."';"), 0);
            $tpl_file = 'admin::stats.list';
            $tpl_data['items'] = [];
            $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=siteadr&site=' . $site . '&page=', $start, $count, $kmess) : '');
            $tpl_data['total'] = $count;
            if ($count) {
                $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(`ref`) as `hit` FROM `counter` WHERE `site` = "' . mysql_real_escape_string($site) . '" GROUP BY `ref` ORDER BY `aid` DESC LIMIT ' . $start . ', ' . $kmess . ') as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
                while ($arr = mysql_fetch_array($req)) {
                    $tpl_data['items'][] = [
                        'content' => '<div><img src="' . SITE_URL . '/assets/images/url.png" alt="."/> ' . ($arr['ref'] == 'unknow' ? 'Không xác định' : '<a href="' . htmlspecialchars($arr['ref']) . '">' . htmlspecialchars($arr['ref']) . '</a>') . '</div><div class="sub">'.functions::display_date($arr['date']).' | Lượt xem: '.$arr['hit'].'</div>'
                    ];
                }

            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_wrong_data'];
        }
        break;
    case 'pop':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Nội dung phổ biến');
        $count = mysql_result(mysql_query("SELECT COUNT(DISTINCT `pop`) FROM `counter` WHERE `robot` = '';"), 0);
        $tpl_file = 'admin::stats.list';
        $tpl_data['items'] = [];
        $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=pop&page=', $start, $count, $kmess) : '');
        $tpl_data['total'] = $count;
        if ($count) {
            $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(*) as `hit` FROM `counter` WHERE `robot` = "" GROUP BY `pop` ORDER BY `hit` DESC LIMIT ' . $start . ', ' . $kmess . ') as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
            while ($arr = mysql_fetch_array($req)) {
                $tpl_data['items'][] = [
                'content' => ($arr['pop'] !== '/' ? '<div><b>'.functions::display_date($arr['date']).'</b> | Tiêu đề: '.($arr['head'] == '' ? 'No Title' : functions::checkout($arr['head'])) . '</div><div class="sub">Địa chỉ: <a href="' . functions::checkout($arr['pop']) . '">' . functions::checkout($arr['pop']) . '</a> | ' : '<b>' . functions::display_date($arr['date']) . '</b> | <a href="' . functions::checkout($arr['pop']) . '">Trang chủ</a><div class="sub">') . 'Lượt xem: '.$arr['hit'].'</div>'
                ];
            }
        }
        break;
    case 'allstat':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Thống kê theo ngày');

        $tpl_file = 'admin::stats.all';

        $days = isset($_GET['days']) ? intval($_GET['days']) : 1;
        $start_time_stat = strtotime(date('d F y', SYSTEM_TIME - $days * 86400));
        $stop_stat_time = $start_time_stat + 86400;
        $count_stat = mysql_result(mysql_query('SELECT COUNT(*) FROM `countersall` WHERE `date` > "' . $start_time_stat . '" AND `date` < "' . $stop_stat_time . '";'), 0);
        $tpl_data['hasData'] = false;
        $tpl_data['currentDay'] = date('d.m.y', $start_time_stat);
        if ($count_stat) {
            $tpl_data['hasData'] = true;
            $day_array = mysql_fetch_assoc(mysql_query('SELECT * FROM `countersall` WHERE `date` > "' . $start_time_stat . '" AND `date` < "' . $stop_stat_time . '" LIMIT 1;'));
            $tpl_data['currentDayHost'] = $day_array['host'];
            $tpl_data['currentDayHits'] = $day_array['hits'];
            $tpl_data['currentDaySearch'] = [
                'yandex' => $day_array['yandex'],
                'rambler' => $day_array['rambler'],
                'google' => $day_array['google'],
                'mail' => $day_array['mail'],
                'gogo' => $day_array['gogo'],
                'yahoo' => $day_array['yahoo'],
                'bing' => $day_array['bing'],
                'nigma' => $day_array['nigma'],
                'qip' => $day_array['qip'],
                'aport' => $day_array['aport'],
                'ask' => $day_array['ask']
            ];
            $tpl_data['currentDaySearchCount'] = array_sum(array_slice($day_array, 3));

            $searchc = mysql_fetch_assoc(mysql_query('SELECT SUM(`hits`) as `hits`, sum(`host`) as `host`, sum(`yandex`) as `yandex`, sum(`rambler`) as `rambler`, sum(`google`) as `google`, sum(`mail`) as `mail`, sum(`gogo`) as `gogo`, sum(`yahoo`) as `yahoo`, sum(`bing`) as `bing`, sum(`nigma`) as `nigma`, sum(`qip`) as `qip`, sum(`aport`) as `aport`, sum(`ask`) as `ask` FROM `countersall`'));
            $tpl_data['allHost'] = $searchc['host'];
            $tpl_data['allHits'] = $searchc['hits'];
            $tpl_data['allSearchCount'] = array_sum(array_slice($searchc, 2));
            $tpl_data['allSearch'] = $statsSearchData = [
                'yandex' => $searchc['yandex'],
                'rambler' => $searchc['rambler'],
                'google' => $searchc['google'],
                'mail' => $searchc['mail'],
                'gogo' => $searchc['gogo'],
                'yahoo' => $searchc['yahoo'],
                'bing' => $searchc['bing'],
                'nigma' => $searchc['nigma'],
                'qip' => $searchc['qip'],
                'aport' => $searchc['aport'],
                'ask' => $searchc['ask'],
            ];

            // График хостов за неделю
            $daytime = date('d.m.y', SYSTEM_TIME);
            $filetime = date('d.m.y', @filemtime(ROOTPATH . '/assets/assets/images/stats/we.png'));
            if (!is_file(ROOTPATH . '/assets/images/stats/we.png') || $filetime != $daytime) {
                $req = mysql_query('SELECT * FROM `countersall` WHERE `date` > "'.(SYSTEM_TIME - 604800).'" ORDER BY `date` ASC LIMIT 7');
                $a = array(); // Массив с хитами
                $b = array(); // Массив с хостами
                $c = array(); // Массив с датами
                while($arr = mysql_fetch_array($req)){
                    $a[] = $arr['hits']; // Добавляем хит
                    $b[] = $arr['host']; // Добавляем хост
                    $c[] = $arr['date']; // Добавляем дату
                }
                require_once(ROOTPATH . 'system/libraries/pChart/pData.class');
                require_once(ROOTPATH . 'system/libraries/pChart/pChart.class');
                $DataSet = new pData;
                $DataSet->AddPoint($a, "Serie1"); // Передаём массив с хитами
                $DataSet->AddPoint($b, "Serie2"); // Передаём массив с хостами
                $DataSet->AddPoint($c, "Serie3"); // Передаём массив с датами
                $DataSet->AddSerie("Serie1");
                $DataSet->AddSerie("Serie2");
                $DataSet->SetAbsciseLabelSerie("Serie3");
                $DataSet->SetSerieName("Hits","Serie1"); // Пояснительные надписи
                $DataSet->SetSerieName("Hosts","Serie2");
                //$DataSet->SetSerieName("Дата","Serie3");
                $DataSet->SetXAxisFormat("date"); // Как обрабатывать массив с датами (в виде даты)

                $Test = new pChart(170,140); // Размер графика
                $Test->setFontProperties(ROOTPATH . 'files/system/fonts/tahoma.ttf',5); // Шрифт боковых надписей
                $Test->setGraphArea(30,10,164,110); // Положение самого графика
                $Test->drawFilledRoundedRectangle(3,3,167,136,5,240,240,240); // Обводка
                $Test->drawRoundedRectangle(1,1,169,138,5,138,230,230);  // Обводка
                $Test->drawGraphArea(252,252,252,TRUE); // Цвет фона на котором расположен график
                $Test->setDateFormat("d"); // Формат вывода даты по оси Х
                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
                $Test->drawGrid(4,TRUE,230,230,230,50);
                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

                $Test->setFontProperties(ROOTPATH . 'files/system/fonts/tahoma.ttf',8); // Шрифт заголовка
                $Test->drawLegend(31,10,$DataSet->GetDataDescription(),230,255,255, -1,-1,-1, TRUE); // Подложка с пояснениями к линиям
                $Test->drawTitle(1,9,'7 ngày gần đây',50,50,50,195); // Заголовок графика
                $Test->Render(ROOTPATH . '/assets/images/stats/we.png'); //Место хранения картинки
            }

            // График поисковиков
            $filetime = date('d.m.y', @filemtime(ROOTPATH . '/assets/images/stats/se.png'));
            if (!is_file(ROOTPATH . '/assets/images/stats/we.png') || $filetime != $daytime) {
                require_once(ROOTPATH . 'system/libraries/pChart/pData.class');
                require_once(ROOTPATH . 'system/libraries/pChart/pChart.class');
                // Dataset definition
                $serie1 = $seria2 = [];
                foreach ($statsSearchData as $key => $value) {
                    if ($value != 0) {
                        $serie1[] = $value;
                        $serie2[] = ucfirst($key);
                    }
                }
                $DataSet = new pData;
                $DataSet->AddPoint($serie1, 'Serie1');
                $DataSet->AddPoint($serie2, 'Serie2');
                $DataSet->AddAllSeries();
                $DataSet->SetAbsciseLabelSerie('Serie2');
                // Initialise the graph
                $Test = new pChart(235,161);
                $Test->setFontProperties(ROOTPATH . 'files/system/fonts/tahoma.ttf',7);
                $Test->drawFilledRoundedRectangle(7,7,235,193,5,240,240,240);
                $Test->drawRoundedRectangle(5,5,234,160,5,20,230,230);
                // Draw the pie chart
                $Test->AntialiasQuality = 0;
                $Test->setShadowProperties(2,2,200,200,200);
                $Test->drawFlatPieGraphWithShadow($DataSet->GetData(), $DataSet->GetDataDescription(), 70, 80, 50, PIE_PERCENTAGE, 8);
                $Test->clearShadow();
                $Test->drawPieLegend(158,8,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
                $Test->Render(ROOTPATH . '/assets/images/stats/se.png');
            }
        }
        ++$days;
        $tpl_data['prevDay'] = date('d.m.Y', SYSTEM_TIME - $days * 24 * 3600);
        $tpl_data['prevDayUrl'] = 'stats?mod=allstat&amp;days='.$days;
        break;
    case 'phones':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Thiết bị - Trình duyệt');
        $tpl_file = 'admin::stats.phones';
        $query = mysql_query('(SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%nokia%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "SIE%" OR `browser` LIKE "%benq%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%sony%" OR `browser` LIKE "%sonyeric%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%sec%" OR `browser` LIKE "%samsung%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%lg%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%mot%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%nec%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%philips%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%pantech%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%sagem%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%fly%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%panasonic%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%opera mini%") UNION ALL
            (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE "%windows%" OR `browser` LIKE "%linux%")');

            $phones = array();
            while ($result_array = mysql_fetch_array($query)) {
                $phones[] = $result_array[0];
            }
            $tpl_data['phones'] = [];

            $col = array();
            $name = array();
            if ($phones[0] > 0) {
                $col[] = $phones[0];
                $name[] = 'Nokia';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Nokia',
                    'name' => 'Nokia',
                    'count' => $phones[0]
                ];
            }
            if ($phones[1] > 0) {
                $col[] = $phones[1];
                $name[] = 'Siemens';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Siemens',
                    'name' => 'Siemens',
                    'count' => $phones[1]
                ];
            }
            if ($phones[2] > 0) {
                $col[] = $phones[2];
                $name[] = 'Sony Ericsson';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=SE',
                    'name' => 'Sony Ericsson',
                    'count' => $phones[2]
                ];
            }
            if($phones[3] > 0){
                $col[] = $phones[3];
                $name[] = 'Samsung';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Samsung',
                    'name' => 'Samsung',
                    'count' => $phones[3]
                ];
            }
            if($phones[4] > 0){
                $col[] = $phones[4];
                $name[] = 'LG';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=LG',
                    'name' => 'LG',
                    'count' => $phones[4]
                ];
            }
            if ($phones[5] > 0) {
                $col[] = $phones[5];
                $name[] = 'Motorola';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Motorola',
                    'name' => 'Motorola',
                    'count' => $phones[5]
                ];
            }
            if($phones[6] > 0){
                $col[] = $phones[6];
                $name[] = 'NEC';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=NEC',
                    'name' => 'NEC',
                    'count' => $phones[6]
                ];
            }
            if ($phones[7] > 0) {
                $col[] = $phones[7];
                $name[] = 'Philips';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Philips',
                    'name' => 'Philips',
                    'count' => $phones[7]
                ];
            }
            if ($phones[8] > 0) {
                $col[] = $phones[8];
                $name[] = 'Pantech';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Pantech',
                    'name' => 'Pantech',
                    'count' => $phones[8]
                ];
            }
            if ($phones[9] > 0) {
                $col[] = $phones[9];
                $name[] = 'Sagem';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Sagem',
                    'name' => 'Sagem',
                    'count' => $phones[9]
                ];
            }
            if ($phones[10] > 0) {
                $col[] = $phones[10];
                $name[] = 'Fly';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Fly',
                    'name' => 'Fly',
                    'count' => $phones[10]
                ];
            }
            if ($phones[11] > 0) {
                $col[] = $phones[11];
                $name[] = 'Panasonic';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Panasonic',
                    'name' => 'Panasonic',
                    'count' => $phones[11]
                ];
            }
            if ($phones[12] > 0) {
                $col[] = $phones[12];
                $name[] = 'Opera Mini';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=Opera',
                    'name' => 'Opera Mini',
                    'count' => $phones[12]
                ];
            }
            if($phones[13] > 0){
                $col[] = $phones[13];
                $name[] = 'Máy tính';
                $tpl_data['phones'][] = [
                    'url' => 'stats?mod=phone&amp;model=komp',
                    'name' => 'Máy tính',
                    'count' => $phones[13]
                ];
            }

            ////// График //////
            require(ROOTPATH . 'system/libraries/pChart/pData.class');
            require(ROOTPATH . 'system/libraries/pChart/pChart.class');
            // Dataset definition
            $DataSet = new pData;

            $DataSet->AddPoint($col, "Serie1");
            $DataSet->AddPoint($name, "Serie2");
            $DataSet->AddAllSeries();
            $DataSet->SetAbsciseLabelSerie("Serie2");

            // Initialise the graph
            $Test = new pChart(235,161);
            $Test->setFontProperties(ROOTPATH . 'files/system/fonts/tahoma.ttf',6);
            $Test->drawFilledRoundedRectangle(7,7,235,193,5,240,240,240);
            $Test->drawRoundedRectangle(5,5,234,160,5,20,230,230);

            // Draw the pie chart
            $Test->AntialiasQuality = 0;
            $Test->setShadowProperties(2,2,200,200,200);
            $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),80,80,40,PIE_PERCENTAGE,8);
            $Test->clearShadow();
            $Test->drawPieLegend(150,6,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
            $Test->Render(ROOTPATH . '/assets/images/stats/model.png');
        break;
    case 'phone':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('/admin/stats?mod=phones', 'Điện thoại - Trình duyệt');

        $model = isset($_GET['model']) ? trim($_GET['model']) : '';
        $arr_model = array('Nokia', 'Siemens', 'SE', 'Samsung', 'LG', 'Motorola', 'NEC', 'Philips', 'Sagem', 'Fly', 'Panasonic', 'Opera', 'komp');
        if (in_array($model, $arr_model)) {
            $model1 = $model;
            $sql = '';
            if ($model == "Nokia") {
                $sql = "WHERE `browser` LIKE '%nokia%'";
            } elseif($model == "Siemens") {
                $sql = "WHERE `browser` LIKE 'SIE%' OR `browser` LIKE '%benq%'";
            } elseif($model == "SE") {
                $model1 = 'Sony Ericsson';
                $sql = "WHERE `browser` LIKE '%sony%' OR `browser` LIKE '%sonyeric%'";
            } elseif($model == "Samsung") {
                $sql = "WHERE `browser` LIKE '%sec%' OR `browser` LIKE '%samsung%'";
            } elseif($model == "LG") {
                $sql = "WHERE `browser` LIKE '%lg%'";
            } elseif($model == "Motorola") {
                $sql = "WHERE `browser` LIKE '%mot%' OR `browser` LIKE '%motorol%'";
            } elseif($model == "NEC") {
                $sql = "WHERE `browser` LIKE '%nec%'";
            } elseif($model == "Philips") {
                $sql = "WHERE `browser` LIKE '%philips%'";
            } elseif($model == "Sagem") {
                $sql = "WHERE `browser` LIKE '%sagem%'";
            } elseif($model == "Fly") {
                $sql = "WHERE `browser` LIKE '%fly%'";
            } elseif($model == "Panasonic"){
                $sql = "WHERE `browser` LIKE '%panasonic%'";
            } elseif($model == "Opera") {
                $model1 = 'Opera Mini';
                $sql = "WHERE `browser` LIKE '%opera mini%'";
            } elseif($model == "komp") {
                $model1 = 'Máy tính';
                $sql = "WHERE `browser` LIKE '%windows%' OR `browser` LIKE '%linux%'";
            }
            $breadcrumb->add($model1);

            $count = mysql_result(mysql_query("SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` ".$sql.";"), 0);
            if ($count) {
                $req = mysql_query('SELECT * FROM `counter` JOIN (SELECT MAX(`hits`) as `aid`, COUNT(*) as `hit` FROM `counter` ' . $sql . ' GROUP BY `ip`, `browser` ORDER BY `aid` DESC LIMIT ' . $start . ', ' . $kmess . ') as `tmp` ON `tmp`.`aid` = `counter`.`hits`');
                $tpl_file = 'admin::stats.list';
                $tpl_data['items'] = [];
                $tpl_data['pagination'] = ($count > $kmess ? functions::display_pagination('stats?mod=phone&model=' . $model . '&page=', $start, $count, $kmess) : '');
                $tpl_data['total'] = $count;
                while ($arr = mysql_fetch_assoc($req)) {
                    $tpl_data['items'][] = [
                        'content' => '<div><b>'.functions::display_date($arr['date']).'</b> - ' . htmlspecialchars($arr['browser']) . '</div>' .
                            '<div class="sub">IP: <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip='.$arr['ip'].'">'.$arr['ip'].'</a> <a href="' . SITE_URL . '/' . $set['admp'] . '/ip-whois?ip='.$arr['ip'].'" title="WhoIS IP">[?]</a> ' . ($arr['ip_via_proxy'] ? ' | <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip='.$arr['ip_via_proxy'].'">'.$arr['ip_via_proxy'].'</a> <a href="' . SITE_URL . '/' . $set['admp'] . '/ip-whois?ip='.$arr['ip_via_proxy'].'" title = "WhoIS ip">[?]</a> ' : '') . ' | Lượt truy cập: '.$arr['hit'].'</div>'
                    ];
                }

            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_wrong_data'];
        }
        break;
    case 'os':
        $breadcrumb->add('/admin/stats', 'Thống kê');
        $breadcrumb->add('Hệ điều hành');
        $tpl_file = 'admin::stats.os';
        $query = mysql_query("(SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Windows NT 5.1%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Windows NT 6.0%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Windows NT 6.1%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Windows NT 6.2%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Windows NT 10.0%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%linux%' OR `browser` LIKE '%bsd%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%SymbianOS/9.1;%' OR `browser` LIKE '%Series60/3.0%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%SymbianOS/9.2;%' OR `browser` LIKE '%Series60/3.1%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%SymbianOS/9.3;%' OR `browser` LIKE '%Series60/3.2%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%SymbianOS/9.4;%' OR `browser` LIKE '%Series60/5.0%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Symbian/3;%' OR `browser` LIKE '%Series60/5.2%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%Series60/2.%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%macos%' OR `browser` LIKE '%macintosh%') UNION ALL
        (SELECT COUNT(DISTINCT `ip`, `browser`) FROM `counter` WHERE `browser` LIKE '%android%')");

        $os = array();
        while($result_array = mysql_fetch_array($query)) {
            $os[] = $result_array[0];
        }
        $tpl_data['items'] = [];
        $col = array();
        $name = array();
        if ($os[0] > 0) {
            $col[] = $os[0];
            $name[] = 'Windows XP';
            $tpl_data['items'][] = [
                'name' => 'Windows XP',
                'count' => $os[0]
            ];
        }
        if ($os[1] > 0) {
            $col[] = $os[1];
            $name[] = 'Windows Vista';
            $tpl_data['items'][] = [
                'name' => 'Windows Vista',
                'count' => $os[1]
            ];
        }
        if ($os[2] > 0) {
            $col[] = $os[2];
            $name[] = 'Windows 7';
            $tpl_data['items'][] = [
                'name' => 'Windows 7',
                'count' => $os[2]
            ];
        }
        if ($os[3] > 0) {
            $col[] = $os[3];
            $name[] = 'Windows 8';
            $tpl_data['items'][] = [
                'name' => 'Windows 8',
                'count' => $os[3]
            ];
        }
        if ($os[4] > 0) {
            $col[] = $os[4];
            $name[] = 'Windows 10';
            $tpl_data['items'][] = [
                'name' => 'Windows 10',
                'count' => $os[4]
            ];
        }
        if ($os[5] > 0) {
            $col[] = $os[5];
            $name[] = 'Linux';
            $tpl_data['items'][] = [
                'name' => 'Linux',
                'count' => $os[5]
            ];
        }
        if ($os[12] > 0) {
            $col[] = $os[12];
            $name[] = 'MAC OS';
            $tpl_data['items'][] = [
                'name' => 'MAC OS',
                'count' => $os[12]
            ];
        }
        if ($os[6] > 0) {
            $col[] = $os[6];
            $name[] = 'Symbian OS 9.1';
            $tpl_data['items'][] = [
                'name' => 'Symbian OS 9.1',
                'count' => $os[6]
            ];
        }
        if ($os[7] > 0) {
            $col[] = $os[7];
            $name[] = 'Symbian OS 9.2';
            $tpl_data['items'][] = [
                'name' => 'Symbian OS 9.2',
                'count' => $os[7]
            ];
        }
        if ($os[8] > 0) {
            $col[] = $os[8];
            $name[] = 'Symbian OS 9.3';
            $tpl_data['items'][] = [
                'name' => 'Symbian OS 9.3',
                'count' => $os[8]
            ];
        }
        if ($os[9] > 0) {
            $col[] = $os[9];
            $name[] = 'Symbian OS 9.4';
            $tpl_data['items'][] = [
                'name' => 'Symbian OS 9.4',
                'count' => $os[9]
            ];
        }
        if ($os[10] > 0) {
            $col[] = $os[10];
            $name[] = 'Symbian OS ^3';
            $tpl_data['items'][] = [
                'name' => 'Symbian OS ^3',
                'count' => $os[10]
            ];
        }
        if ($os[10] > 0) {
            $col[] = $os[10];
            $name[] = 'Other Symbian';
            $tpl_data['items'][] = [
                'name' => 'Other Symbian',
                'count' => $os[10]
            ];
        }
        if ($os[13] > 0) {
            $col[] = $os[13];
            $name[] = 'Android';
            $tpl_data['items'][] = [
                'name' => 'Android',
                'count' => $os[13]
            ];
        }
        if (array_sum($os)) {
            ////// График //////
            require(ROOTPATH . 'system/libraries/pChart/pData.class');
            require(ROOTPATH . 'system/libraries/pChart/pChart.class');
            // Dataset definition
            $DataSet = new pData;
            $DataSet->AddPoint($col, "Serie1");
            $DataSet->AddPoint($name, "Serie2");
            $DataSet->AddAllSeries();
            $DataSet->SetAbsciseLabelSerie("Serie2");

            // Initialise the graph
            $Test = new pChart(237, 202);
            $Test->setFontProperties(ROOTPATH . 'files/system/fonts/tahoma.ttf',6);
            $Test->drawFilledRoundedRectangle(7,7,237,223,5,240,240,240);
            $Test->drawRoundedRectangle(5,5,236,201,5,20,230,230);

            // Draw the pie chart
            $Test->AntialiasQuality = 0;
            $Test->setShadowProperties(2,2,200,200,200);
            $Test->drawFlatPieGraphWithShadow($DataSet->GetData(), $DataSet->GetDataDescription() ,80,80,40,PIE_PERCENTAGE,8);
            $Test->clearShadow();
            $Test->drawPieLegend(150, 8, $DataSet->GetData(), $DataSet->GetDataDescription(), 250,250,250);
            $Test->Render(ROOTPATH . '/assets/images/stats/os.png');

        }
        break;
    case 'stat_search':
        $breadcrumb->add('/admin/stats', 'Thống kê');

        $day = isset($_GET['sday']) ? trim($_GET['sday']) : '';
        $engine = isset($_GET['sengine']) ? trim($_GET['sengine']) : '';

        if (isset($_GET['sengine']) || isset($_GET['sday'])) {
            $breadcrumb->add('/admin/stats?mod=stat_search', 'Công cụ tìm kiếm');
            $tpl_file = 'admin::stats.search-details';
            $sql = '';
            $n = 'Tất cả';
            /////// Выбираем поисковую машину ///////
            switch ($engine) {
                case 'google':
                    $sql = " AND `engine` LIKE '%google%'";
                    $n = 'Goole';
                    break;
                case 'mail':
                    $sql = " AND `engine` LIKE '%mail%'";
                    $n = 'Mail.ru';
                    break;
                case 'rambler':
                    $sql = " AND `engine` LIKE '%rambler%'";
                    $n = 'Rambler.ru';
                    break;
                case 'yandex':
                    $sql = " AND `engine` LIKE '%yandex%'";
                    $n = 'Yandex.ru';
                    break;
                case 'bing':
                    $sql = " AND `engine` LIKE '%bing%'";
                    $n = 'Bing.com';
                    break;
                case 'nigma':
                    $sql = " AND `engine` LIKE '%nigma%'";
                    $n = 'Nigma.ru';
                    break;
                case 'qip':
                    $sql = " AND `engine` LIKE '%qip%'";
                    $n = 'search.qip.ru';
                    break;
                case 'aport':
                    $sql = " AND `engine` LIKE '%aport%'";
                    $n = 'Aport.ru';
                    break;
                case 'gogo':
                    $sql = " AND `engine` LIKE '%gogo%'";
                    $n = 'Gogo.ru';
                    break;
                case 'yahoo':
                    $sql = " AND `engine` LIKE '%yahoo%'";
                    $n = 'Yahoo.ru';
                    break;
                case 'ask':
                    $sql = " AND `engine` LIKE '%ask%'";
                    $n = 'Ask.com';
                    break;
                default:
                    $engine = 'all';
            }
            $breadcrumb->add('Thống kê ' . $n);
            /////// Вычисляем время /////////
            $time = strtotime(date('d F y', SYSTEM_TIME));
            $time1 = $time - 86400;
            $time7 = $time - 604800;
            ///// Выбираем нужный период ////
            switch ($day) {
                ///// Весь период /////
                case 'all':
                    $sql = str_replace('AND', 'WHERE', $sql);
                    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `stat_robots` ".$sql.""), 0);
                    if ($total) {
                        $req = mysql_query("SELECT * FROM `stat_robots` ".$sql." ORDER BY `date` DESC LIMIT " . $start . "," . $kmess);
                    }
                    break;
                ///// Семь дней /////
                case 'seven':
                    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$time7."' and `date` < '".$time."' ".$sql.""), 0);
                    if($total) {
                        $req = mysql_query("SELECT * FROM `stat_robots` WHERE `date` > '".$time7."' and `date` < '".$time."'".$sql." ORDER BY `date` DESC LIMIT " . $start . "," . $kmess);
                    }
                    break;
                 ///// За прошедший день (вчера) /////
                case 'two':
                    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$time1."' and `date` < '".$time."'".$sql.""), 0);
                    if ($total) {
                        $req = mysql_query("SELECT * FROM `stat_robots` WHERE `date` > '".$time1."' and `date` < '".$time."'".$sql." ORDER BY `date` DESC LIMIT " . $start . "," . $kmess);
                    }
                    break;
                 /////// Стандарт за сутки /////
                default:
                    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$time."'".$sql.""), 0);
                    if ($total) {
                        $req = mysql_query("SELECT * FROM `stat_robots` WHERE `date` > '".$time."'".$sql." ORDER BY `date` DESC LIMIT " . $start . "," . $kmess);
                    }
                    $day = 'one';
                 break;
            }
            $tpl_data['tabs'] = [
                [
                    'url' => 'stats?mod=stat_search&sengine=' . $engine . '&sday=one',
                    'name' => 'Hôm nay',
                    'active' => ($day !== 'seven' && $day !== 'two' && $day !== 'all'),
                ],
                [
                    'url' => 'stats?mod=stat_search&sengine=' . $engine . '&sday=two',
                    'name' => 'Hôm qua',
                    'active' => ($day === 'two'),
                ],
                [
                    'url' => 'stats?mod=stat_search&sengine=' . $engine . '&sday=seven',
                    'name' => '7 ngày',
                    'active' => ($day === 'seven'),
                ],
                [
                    'url' => 'stats?mod=stat_search&sengine=' . $engine . '&sday=all',
                    'name' => 'Tất cả',
                    'active' => ($day === 'all'),
                ]

            ];
            $tpl_data['total'] = $total;
            $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('stats?mod=stat_search&sengine=' . $engine . '&sday=' . $day . '&page=', $start, $total, $kmess) : '');
            $tpl_data['items'] = [];
            if ($total) {
                while ($arr = mysql_fetch_array($req)) {
                    $tpl_data['items'][] = [
                        'content' => '<div><a href="' . htmlspecialchars($arr['url']) . '" target="_blank">' . htmlspecialchars($arr['query']) . '</a> [' . date('H:i', $arr['date']) . ']</div><div class="sub">IP: <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip='.long2ip($arr['ip']).'">'.long2ip($arr['ip']).'</a>' . ($day !== 'seven' && $day !== 'two' ? ' . Hôm nay: '.$arr['today'] : '') . ' Tất cả: '.$arr['count'] . '<br/>UA: ' . htmlspecialchars($arr['ua']) . '</div>'
                    ];
                }
            }

        } else {
            $breadcrumb->add('Công cụ tìm kiếm');
            $tpl_file = 'admin::stats.search';
            $where_time = strtotime(date('d F y', SYSTEM_TIME));
            $query = mysql_query("(SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%yandex%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%mail%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%rambler%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%google%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%gogo%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%yahoo%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%bing%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%nigma%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%qip%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%aport%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."' AND `engine` LIKE '%ask%') UNION ALL
                (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '".$where_time."')");
            $count_query = array();
            while ($result_array = mysql_fetch_array($query)) {
                $count_query[] = $result_array[0];
            }
            $tpl_data['engines'] = [
                'yandex' => [
                    'url' => 'stats?mod=stat_search&sengine=yandex',
                    'name' => 'Yandex',
                    'count' => $count_query[0],
                ],
                'mail' => [
                    'url' => 'stats?mod=stat_search&sengine=mail',
                    'name' => 'Mail.ru',
                    'count' => $count_query[1],
                ],
                'rambler' => [
                    'url' => 'stats?mod=stat_search&sengine=rambler',
                    'name' => 'Rambler',
                    'count' => $count_query[2],
                ],
                'google' => [
                    'url' => 'stats?mod=stat_search&sengine=google',
                    'name' => 'Goole',
                    'count' => $count_query[3],
                ],
                'gogo' => [
                    'url' => 'stats?mod=stat_search&sengine=gogo',
                    'name' => 'Gogo',
                    'count' => $count_query[4],
                ],
                'yahoo' => [
                    'url' => 'stats?mod=stat_search&sengine=yahoo',
                    'name' => 'Yahoo.com',
                    'count' => $count_query[5],
                ],
                'bing' => [
                    'url' => 'stats?mod=stat_search&sengine=bing',
                    'name' => 'Bing.com',
                    'count' => $count_query[6],
                ],
                'nigma' => [
                    'url' => 'stats?mod=stat_search&sengine=nigma',
                    'name' => 'Nigma.ru',
                    'count' => $count_query[7],
                ],
                'qip' => [
                    'url' => 'stats?mod=stat_search&sengine=qip',
                    'name' => 'QIP.ru',
                    'count' => $count_query[8],
                ],
                'aport' => [
                    'url' => 'stats?mod=stat_search&sengine=aport',
                    'name' => 'Aport.ru',
                    'count' => $count_query[9],
                ],
                'ask' => [
                    'url' => 'stats?mod=stat_search&sengine=ask',
                    'name' => 'Ask.com',
                    'count' => $count_query[10],
                ],
            ];
            $tpl_data['allCount'] = $count_query[11];
            $tpl_data['allUrl'] = 'stats?mod=stat_search&sengine=all';
        }
        break;
    default:
        require_once(ROOTPATH . 'system/header.php');
        $breadcrumb->add('Thống kê');

        $begin_day = strtotime(date('d F y', SYSTEM_TIME));
        $my_url = parse_url(SITE_URL);

        $query = mysql_query('(SELECT COUNT(*) FROM `counter` WHERE `robot` != "") UNION ALL
            (SELECT COUNT(DISTINCT `pop`) FROM `counter` WHERE `robot` = "") UNION ALL
            (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > "' . $begin_day . '") UNION ALL
            (SELECT COUNT(DISTINCT `robot`) FROM `counter` WHERE `robot` != "") UNION ALL
            (SELECT COUNT(DISTINCT `user`) FROM `counter`) UNION ALL
            (SELECT COUNT(DISTINCT `site`) FROM `counter` WHERE `site` NOT LIKE "%' . $my_url['host'] . '")') or die(mysql_error());
        $count_stat = array();
        while($result_array = mysql_fetch_array($query)) {
            $count_stat[] = $result_array[0];
        }

        $tpl_file = 'admin::stats';
        $tpl_data['todayHit'] = statistic::$hity;
        $tpl_data['todayHost'] = statistic::$hosty;
        $tpl_data['todayHitRobot'] = $count_stat[0];
        $tpl_data['todayHitNoRobot'] = statistic::$hity - $count_stat[0];
        $tpl_data['todayAverage'] = (statistic::$hosty > 0 ? round((statistic::$hity - $count_stat[0]) / statistic::$hosty) : 0);

        $tpl_data['maxHost'] = $tpl_data['maxHit'] = $tpl_data['maxHostTime'] = $tpl_data['maxHitTime'] = 0;
        $maxhost = mysql_query('SELECT `date`, `host` FROM `countersall` ORDER BY `countersall`.`host` DESC LIMIT 1');
        if (mysql_num_rows($maxhost) > 0) {
            $maxhost = mysql_fetch_assoc($maxhost);
            $tpl_data['maxHost'] = $maxhost['host'];
            $tpl_data['maxHostTime'] = date('d / m / Y', $maxhost['date']);

            $maxhits = mysql_fetch_assoc(mysql_query('SELECT `date`, `hits` FROM `countersall` ORDER BY `countersall`.`hits` DESC LIMIT 1'));
            $tpl_data['maxHit'] = $maxhits['hits'];
            $tpl_data['maxHitTime'] = date('d / m / Y', $maxhits['date']);
        }
        $tpl_data['todayPageViewed'] = $count_stat[1];
        $tpl_data['todaySearch'] = $count_stat[2];
        $tpl_data['todaySearchPercent'] = (statistic::$hosty > 0 ? round(($count_stat[2] * 100 / statistic::$hosty), 2) : 0);
        $tpl_data['todayRobot'] = $count_stat[3];
        $tpl_data['todayUser'] = $count_stat[4];
        $tpl_data['todayReferer'] = $count_stat[5];
}
$_breadcrumb = $breadcrumb->out();