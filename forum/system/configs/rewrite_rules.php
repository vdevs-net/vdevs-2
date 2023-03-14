<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$rewrite_rules = array(
    // special module

    // Basic module
    '#^([a-z]+)(/?)$#'                                       => ['index.php?module=$1', 2],
    // EX: game/
    // EX: home/
    // EX: news/
    // EX: shop/

    '#^([a-z]+)/([a-z\-]+)$#'                                => ['index.php?module=$1&module_action=$2'],
    // EX: news/(add|clean)
    // EX: shop/()

    '#^([a-z]+)/([a-z\-]+)/$#'                               => ['index.php?module=$1&module_file=$2'],
    // EX: news/(add|clean)
    // EX: shop/()

    '#^([a-z]+)/([a-z\-]+)/([a-z\-]+)$#'                     => ['index.php?module=$1&module_file=$2&module_action=$3'],
    // EX: news/(add|clean)
    // EX: shop/()

    '#^([a-z]+)/([A-Za-z0-9\-.]+\.)?(\d+)(/?)$#'             => ['index.php?module=$1&id=$3', 4],
    // EX: news/1/
    // EX: profile/example.1/

    '#^([a-z]+)/([A-Za-z0-9\-.]+\.)?(\d+)/page-(\d+)$#'      => ['index.php?module=$1&id=$3&page=$4'],
    // EX: messages/example.1/page-1

    '#^([a-z]+)/([A-Za-z0-9\-.]+\.)?(\d+)/([a-z\-]+)$#'      => ['index.php?module=$1&id=$3&module_action=$4'],
    // EX: news/1/edit
    // EX: profile/example.1/activity

    '#^([a-z]+)/([a-z]+)/([a-z0-9\-]+\.)?(\d+)(/?)$#'        => ['index.php?module=$1&module_file=$2&id=$4', 5],
    // EX: forum/threads/example.1/

    '#^([a-z]+)/([a-z]+)/([a-z0-9\-]+\.)?(\d+)/([a-z\-]+)$#' => ['index.php?module=$1&module_file=$2&id=$4&module_action=$5'],
    // EX: forum/threads/example.1/edit
);
