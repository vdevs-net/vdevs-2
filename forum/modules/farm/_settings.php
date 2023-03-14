<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'farm';
$page_title = 'Nông trại vui vẻ';

// check access rights
if (!$user_id) {
    $module_error = $lng['access_guest_forbidden'];
} else {

    $tpl_file = 'farm::farm';

    define('TIME', time());
    define('FARM_AREA_TABLE', 'farm_area');
    define('FARM_ITEMS_TABLE', 'farm_items');
    define('FARM_WAREHOUSE_TABLE', 'farm_warehouse');
    define('USERS_TABLE', 'users');

    $ref = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';

    echo '<link rel="stylesheet" href="' . SITE_URL . '/assets/farm/styles.css" />';

    $farm_products = [
        1  => ['name' => 'Khế',             'price' => 10],
        2  => ['name' => 'Cà chua',         'price' => 1],
        3  => ['name' => 'Cà rốt',          'price' => 1],
        4  => ['name' => 'Khóm',            'price' => 1],
        5  => ['name' => 'Dưa hấu',         'price' => 1],
        6  => ['name' => 'Nho',             'price' => 1],
        7  => ['name' => 'Hoa hồng',        'price' => 1],
        8  => ['name' => 'Lúa',             'price' => 1],
        9  => ['name' => 'Xoài',            'price' => 1],
        10 => ['name' => 'Thanh long',      'price' => 1],
        11 => ['name' => 'Hoa hướng dương', 'price' => 1],
        12 => ['name' => 'Hoa tulip',       'price' => 1],
        13 => ['name' => 'Trứng gà',        'price' => 10]
    ];

    $farm_items = [
        1 => [
            'name'             => 'Cà chua',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 2,
            'product_count'    => 75,
            'product_interval' => 0,
            'grow_time'        => 14400,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        2 => [
            'name'             => 'Cà rốt',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 3,
            'product_count'    => 108,
            'product_interval' => 0,
            'grow_time'        => 21600,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        3 => [
            'name'             => 'Khóm',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 4,
            'product_count'    => 165,
            'product_interval' => 0,
            'grow_time'        => 36000,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        4 => [
            'name'             => 'Dưa hấu',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 5,
            'product_count'    => 138,
            'product_interval' => 0,
            'grow_time'        => 28800,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        5 => [
            'name'             => 'Nho',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 6,
            'product_count'    => 240,
            'product_interval' => 0,
            'grow_time'        => 57600,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        6 => [
            'name'             => 'Hoa hồng',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 7,
            'product_count'    => 45,
            'product_interval' => 0,
            'grow_time'        => 7200,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        7 => [
            'name'             => 'Lúa',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 8,
            'product_count'    => 720,
            'product_interval' => 0,
            'grow_time'        => 172800,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        8 => [
            'name'             => 'Xoài',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 9,
            'product_count'    => 360,
            'product_interval' => 0,
            'grow_time'        => 86400,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        9 => [
            'name'             => 'Thanh long',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 10,
            'product_count'    => 189,
            'product_interval' => 0,
            'grow_time'        => 43200,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        10 => [
            'name'             => 'Hoa hướng dương',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 11,
            'product_count'    => 189,
            'product_interval' => 0,
            'grow_time'        => 43200,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        11 => [
            'name'             => 'Hoa tulip',
            'type'             => 1,
            'size'             => 1,
            'cost'             => 10,
            'currency'         => 0,
            'product'          => 12,
            'product_count'    => 108,
            'product_interval' => 0,
            'grow_time'        => 21600,
            'dead_time'        => 604800, // 7 days
            'price'            => 0
        ],
        12 => [
            'name'             => 'Gà',
            'type'             => 2,
            'size'             => 1,
            'cost'             => 500,
            'currency'         => 0,
            'product'          => 13,
            'product_count'    => 20,
            'product_interval' => 21600,
            'grow_time'        => 86400,
            'dead_time'        => 604800, // 7 days
            'price'            => 950
        ],
        13 => [
            'name'             => 'Heo',
            'type'             => 2,
            'size'             => 2,
            'cost'             => 1000,
            'currency'         => 0,
            'product'          => 0,
            'product_count'    => 0,
            'product_interval' => 0,
            'grow_time'        => 172800, // 2 days
            'dead_time'        => 1728000, // 20 days
            'price'            => 2000
        ],
        14 => [
            'name'             => 'Cá',
            'type'             => 3,
            'size'             => 1,
            'cost'             => 5000,
            'currency'         => 0,
            'product'          => 0,
            'product_count'    => 0,
            'product_interval' => 0,
            'grow_time'        => 259200, // 3 days
            'dead_time'        => 2592000, // 30 days
            'price'            => 15000
        ]
    ];
    $exp = [
        10, 20, 40, 80, 140, 245, 429, 643, 965, 1302, // 11
        1302, 1758, 2373, 3086, 3857, 4725, 6662, 7661, 8695, 9782, // 21
        11005, 12381, 13928, 15669, 17628, 19832, 22311, 25099, 28237, 32049, // 31
        36375, 41286, 46860, 53186, 60366, 68515, 77765, 88263, 100178, 117710, // 41
        138309, 162513, 190953, 224369, 263634, 309770, 363980, 427676, 502519, 585435, // 51
        682032, 794567, 925671, 1078406, 1256343, 1463640, 1705140, 1986489, 2314259, 2672969, // 61
        3087280, 3565808, 4118508, 4756877, 5494193, 6345793, 7329390, 8465446, 9777590, 12710867, // 71
        16524127, 21481365, 27925775, 37699796, 50894725, 68707878, 92755636, 125220108, 169047146 // 80
    ];
    $space_cost = [
        1 => [ // plant
            10800, 14700, 19200, 24300, 30000, 36300, // 2
            43200, 50700, 58800, 67500, 76800, 86700, // 3
            97200, 108300, 120000, 132300, 145200, 158700, // 4
            172800, 187500, 202800, 212300, 235200, 252300, // 5
            270000, 288300, 307200, 326700, 364800, 367500, // 6
            388800, 410700, 433200, 456300, 480000, 504300, // 7
            529200, 554700, 580800, 607500, 634800, 662700 // 8
        ],
        2 => [ // animal
            20000, 80000, 180000, 320000, 500000, 720000, 980000, 1280000, 1620000, 2000000
        ],
        3 => [ // fish
            50000, 200000, 450000, 800000, 1250000, 1800000, 2450000, 3200000
        ]
    ];
    $init_space = [
        1 => 6,
        2 => 10,
        3 => 3
    ];
    $max_space = [
        1 => 48,
        2 => 20,
        3 => 10
    ];
    // effect
    $min_effect_0_time = 43200;
    // Star fruit tree
    $sft_time = 28800; // 8 hours
    $sft_time_per_level = 600;
    // Real sft time
    $sft_time = $sft_time - $datauser['sft_level'] * $sft_time_per_level;
    // minimun stf time
    if ($sft_time < 7200) {
        $sft_time = 7200;
    }
    $sft_timer = $datauser['sft_time'] + $sft_time >= TIME ? $datauser['sft_time'] + $sft_time - TIME : 0;
    function timer($time, $mod = 0) {
        if($time <= 0) $time = 0;
        $d = floor($time / 86400);
        $h = floor(($time - $d * 86400) / 3600);
        $m = floor(($time - $d * 86400 - $h * 3600) / 60);
        $s = $time - $d * 86400 - $h * 3600 - $m * 60;
        if ($mod) {
            return ($d ? $d . ' ngày' : '') . ($h ? ($d ? ' ':'') . $h . ' giờ' : '') . ($m ? ($h ? ' ':'') . $m  . ' phút' : '') . ($mod == 2 ? ($s ? ($h || $m ? ' ':'') . $s  . ' giây' : '') : '' );
        }
        return $h . ':' . ($m < 10 ? '0' : '') . $m . ':' . ($s < 10 ? '0' : '') . $s;
    }
    function status($data) {
        if ($data['type'] == 2 || $data['type'] == 3) {
            if (TIME >= $data['grow_time']) {
                return '1';
            } else {
                return '0';
            }
        } else {
            if ($data['item_id']) {
                if ($data['collect_time'] != 0 || $data['dead_time'] <= TIME) {
                    return '6';
                }
                $time_count = TIME - $data['time'];
                $effect_0_time = TIME - $data['effect_0_time'];
                $interval = ($data['grow_time'] - $data['time']) / 6;
                $w_interval = 2 * $interval;
                // min effect 0 time = 6 hours
                if ($w_interval > 21600) {
                    $w_interval = 21600;
                }
                if (TIME >= $data['grow_time']) {
                    return '5_' . ($effect_0_time > $w_interval ? '1' : '0');
                }
                if ($time_count >= $interval * 5) {
                    return '4_' . ($effect_0_time > $w_interval ? '1' : '0');
                }
                if ($time_count >= $interval * 4) {
                    return '3_' . ($effect_0_time > $w_interval ? '1' : '0');
                }
                if ($time_count >= $interval * 2) {
                    return '2_' . ($effect_0_time > $w_interval ? '1' : '0');
                }
                if ($time_count >= $interval) {
                    return '1_0';
                }
                return '0_0';
            } else {
                return '0';
            }
        }
    }
    function ns($data) {
        global $min_effect_0_time;
        if ($data['item_id'] == 0 || $data['dead_time'] < TIME || ($data['type'] == 1 && $data['collect_time'] != 0)) {
            return 0;
        }
        $interval = ($data['grow_time'] - $data['time']) / 3;
        // min effect 0 time = 12 hours
        if ($interval > $min_effect_0_time) {
            $interval = $min_effect_0_time;
        }
        $effect_0_time = TIME - $data['effect_0_time'];
        if ($effect_0_time >= $interval) {
            $data['ns'] -= round(($effect_0_time - $interval) * 25 / $interval);
            if ($data['ns'] < 5) {
                $data['ns'] = 5;
            }
        } else {
            if ($data['type'] == 1) {
                $effect_0_time = min(TIME, $data['grow_time']) - $data['effect_0_time'];
            }
            $data['ns'] += round($effect_0_time * 300 / $interval);
            if ($data['ns'] > 100) {
                $data['ns'] = 100;
            }
        }
        return $data['ns'];
    }
    function cacl_price($data) {
        global $farm_items;
        if (TIME >= $data['grow_time']) {
            $price = $farm_items[$data['item_id']]['price'];
        } else {
            $price = $farm_items[$data['item_id']]['cost'] / 2;
        }
        return ceil($price * ns($data) / 100);

    }
    $stmt = mysql_query('SELECT `type`, COUNT(*) as `count` FROM `' . FARM_AREA_TABLE . '` WHERE `user_id` = "'. $user_id .'" GROUP BY `type`');
    $count = [
        1 => 0,
        2 => 0,
        3 => 0
    ];
    while ($res = mysql_fetch_assoc($stmt)) {
        $count[$res['type']] = $res['count'];
    }

    if (!$count[1]) { // Nếu chưa có ô đất
        // add spaces
        $sql = 'INSERT INTO `' . FARM_AREA_TABLE . '` (`user_id`, `type`) VALUES ';
        $tmp = [];
        foreach ($init_space as $type => $space) {
            for ($i = 0; $i < $space; $i++) {
                $tmp[] = '("'. $user_id .'", "' . $type . '")';
            }
        }
        $sql .= implode(', ', $tmp);
        mysql_query($sql);
        // add items
        $sql = 'INSERT INTO `' . FARM_ITEMS_TABLE . '` (`user_id`, `item_id`, `type`) VALUES ';
        $tmp = [];
        foreach ($farm_items as $id => $item) {
            if ($item['type'] != 2 && $item['type'] != 3) {
                $tmp[] = '("'. $user_id .'", "'. $id .'", "' . ($item['type'] == 1 ? '1' : '0') . '")';
            }
        }
        $sql .= implode(', ', $tmp);
        mysql_query($sql);
        // add products
        $sql = 'INSERT INTO `' . FARM_WAREHOUSE_TABLE . '` (`user_id`, `product_id`) VALUES ';
        $tmp = [];
        foreach ($farm_products as $id => $product) {
            $tmp[] = '("'. $user_id .'", "'. $id .'")';
        }
        $sql .= implode(', ', $tmp);
        mysql_query($sql);
        mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = (`coin` + 5000), `sft_time` = "' . TIME . '" WHERE `id` = "'. $user_id .'"');
        header('Location: ' . SITE_URL . '/farm/'); exit;
    }
}
