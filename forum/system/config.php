<?php
defined('_MRKEN_CMS') or die ('Error: restricted access');
define('DEV_MODE', false);

define('DAILY_CHAT_REWARD', 1000);

// SMTP config (For send mail)
define('SMTP_USER', 'mxh.phonho@gmail.com');
define('SMTP_PASSWORD', 'smtp_password');
define('SMTP_SEND_FROM', defined('SMTP_USER') && SMTP_USER ? SMTP_USER : 'mxh.phonho@gmail.com');
define('SMTP_REPLY_TO', defined('SMTP_USER') && SMTP_USER ? SMTP_USER : 'mxh.phonho@gmail.com');

if (DEV_MODE) {
    define('VERSION', mt_rand(1000, 9999));
    define('SITE_SCHEME', 'http://');
    define('SITE_HOST', 'phonho.pro');
    define('SITE_PATH', '');
    define('SITE_URL', SITE_SCHEME . SITE_HOST . SITE_PATH);
    define('API_URL', 'http://api.phonho.pro');
    define('FB_APP_ID', 'fb_app_id');
    define('FB_APP_SECRET', 'fb_app_secret');

    // database info
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'phonhone_forum');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('VERSION', '2020.04.12.05.04');
    define('SITE_SCHEME', 'https://');
    define('SITE_HOST', 'forum.vdevs.net');
    define('SITE_PATH', '');
    define('SITE_URL', SITE_SCHEME . SITE_HOST . SITE_PATH);
    define('API_URL', 'https://api.vdevs.net');
    define('FB_APP_ID', 'fb_app_id');
    define('FB_APP_SECRET', 'fb_app_secret');

    // database info
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'phonhone_forum');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

define('SALT', 'xnmabsd@234HJA');

// IMGUR
define('IMGUR_CLIENT_ID', ''); // API client ID
define('IMGUR_ALBUM_ID', ''); // Album id for upload
define('IMGUR_ALBUM_DELETEHASH', ''); // or Album delete hash for upload (Anonymous album)

define('COOKIE_PATH', SITE_PATH . '/');

define('IMAGE_PER_MESSAGE', 5);
define('MAX_POLL_RESPONSE', 10);

// Google Analytics
define('GA_ID', 'UA-xxxxxxxx-3');
// Google site verify code
define('GSV_CODE', 'xxxx-xxxxxxxxxxxxxxxxx');

// Buy coin from gold
define('BUY_COIN_RATIO', 10);

define('MIN_FORUM_MESSAGE_LENGTH', 10);
