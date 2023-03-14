-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Mar 14, 2023 at 11:08 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vdevs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_ads`
--

CREATE TABLE `cms_ads` (
  `id` int UNSIGNED NOT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `view` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `layout` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `count` int UNSIGNED NOT NULL DEFAULT '0',
  `count_link` int UNSIGNED NOT NULL DEFAULT '0',
  `name` mediumtext NOT NULL,
  `link` mediumtext NOT NULL,
  `to` int UNSIGNED NOT NULL DEFAULT '0',
  `color` varchar(10) NOT NULL DEFAULT '',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `day` int UNSIGNED NOT NULL DEFAULT '0',
  `mesto` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `bold` tinyint(1) NOT NULL DEFAULT '0',
  `italic` tinyint(1) NOT NULL DEFAULT '0',
  `underline` tinyint(1) NOT NULL DEFAULT '0',
  `show` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_ban_ip`
--

CREATE TABLE `cms_ban_ip` (
  `id` int UNSIGNED NOT NULL,
  `ip1` bigint NOT NULL DEFAULT '0',
  `ip2` bigint NOT NULL DEFAULT '0',
  `ban_type` tinyint NOT NULL DEFAULT '0',
  `who` varchar(25) NOT NULL,
  `reason` mediumtext NOT NULL,
  `date` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_ban_users`
--

CREATE TABLE `cms_ban_users` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `ban_time` int NOT NULL DEFAULT '0',
  `ban_while` int NOT NULL DEFAULT '0',
  `ban_type` tinyint NOT NULL DEFAULT '1',
  `ban_who` varchar(30) NOT NULL DEFAULT '',
  `ban_reason` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_chat`
--

CREATE TABLE `cms_chat` (
  `id` int NOT NULL,
  `uid` int NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `time` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_settings`
--

INSERT INTO `cms_chat` (`uid`, `text`, `time`) VALUES
('2', 'Welcome to chat room!', 1679943502);

-- --------------------------------------------------------

--
-- Table structure for table `cms_forum_files`
--

CREATE TABLE `cms_forum_files` (
  `id` int UNSIGNED NOT NULL,
  `cat` int UNSIGNED NOT NULL DEFAULT '0',
  `subcat` int UNSIGNED NOT NULL DEFAULT '0',
  `topic` int UNSIGNED NOT NULL DEFAULT '0',
  `post` int UNSIGNED NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `filename` mediumtext NOT NULL,
  `filetype` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `dlcount` int UNSIGNED NOT NULL DEFAULT '0',
  `del` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_forum_rdm`
--

CREATE TABLE `cms_forum_rdm` (
  `topic_id` int UNSIGNED NOT NULL DEFAULT '0',
  `post_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_forum_vote`
--

CREATE TABLE `cms_forum_vote` (
  `id` int UNSIGNED NOT NULL,
  `type` int NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `topic` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL,
  `count` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_forum_vote_users`
--

CREATE TABLE `cms_forum_vote_users` (
  `id` int UNSIGNED NOT NULL,
  `user` int NOT NULL DEFAULT '0',
  `topic` int NOT NULL,
  `vote` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_images`
--

CREATE TABLE `cms_images` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `deleteHash` varchar(31) NOT NULL,
  `size` int NOT NULL,
  `width` smallint UNSIGNED NOT NULL,
  `height` smallint UNSIGNED NOT NULL,
  `link` varchar(127) NOT NULL,
  `time` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_likes`
--

CREATE TABLE `cms_likes` (
  `id` int UNSIGNED NOT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `user_like` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `sub_id` int UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_log`
--

CREATE TABLE `cms_log` (
  `id` int UNSIGNED NOT NULL,
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `uid` int UNSIGNED NOT NULL DEFAULT '0',
  `pid` int UNSIGNED NOT NULL DEFAULT '0',
  `type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `text` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_mail`
--

CREATE TABLE `cms_mail` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `from_id` int UNSIGNED NOT NULL DEFAULT '0',
  `text` mediumtext NOT NULL,
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `read` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `sys` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `delete` int UNSIGNED NOT NULL DEFAULT '0',
  `file_name` varchar(100) NOT NULL DEFAULT '',
  `count` int NOT NULL DEFAULT '0',
  `size` int NOT NULL DEFAULT '0',
  `them` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_paid`
--

CREATE TABLE `cms_paid` (
  `id` int UNSIGNED NOT NULL,
  `uid` int UNSIGNED NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL DEFAULT '',
  `d1` varchar(255) NOT NULL DEFAULT '',
  `d2` varchar(255) NOT NULL DEFAULT '',
  `d3` varchar(255) NOT NULL DEFAULT '',
  `time` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_profile_posts`
--

CREATE TABLE `cms_profile_posts` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED NOT NULL,
  `time` int UNSIGNED NOT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `text` mediumtext NOT NULL,
  `privacy` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_rps_game`
--

CREATE TABLE `cms_rps_game` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `choice` tinyint UNSIGNED NOT NULL,
  `coin` int UNSIGNED NOT NULL,
  `time` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_sessions`
--

CREATE TABLE `cms_sessions` (
  `session_id` char(32) NOT NULL DEFAULT '',
  `ip` bigint NOT NULL DEFAULT '0',
  `ip_via_proxy` bigint NOT NULL DEFAULT '0',
  `browser` varchar(255) NOT NULL DEFAULT '',
  `lastdate` int UNSIGNED NOT NULL DEFAULT '0',
  `sestime` int UNSIGNED NOT NULL DEFAULT '0',
  `views` int UNSIGNED NOT NULL DEFAULT '0',
  `movings` smallint UNSIGNED NOT NULL DEFAULT '0',
  `place` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_settings`
--

CREATE TABLE `cms_settings` (
  `key` text NOT NULL,
  `val` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_settings`
--

INSERT INTO `cms_settings` (`key`, `val`) VALUES
('active', '1'),
('admp', 'admin'),
('antiflood', 'a:5:{s:4:\"mode\";i:1;s:3:\"day\";i:5;s:5:\"night\";i:15;s:7:\"dayfrom\";i:10;s:5:\"dayto\";i:22;}'),
('clean_time', '1678674682'),
('copyright', 'vDevs - Cộng đồng Web Developers'),
('email', 'mxh.phonho@gmail.com'),
('flsz', '4096'),
('gzip', '1'),
('lng', 'vi'),
('lng_list', 'a:2:{s:2:\"en\";s:7:\"English\";s:2:\"vi\";s:10:\"Việt Nam\";}'),
('meta_desc', 'Diễn đàn thảo luận wap/web'),
('meta_key', 'giải trí, chat chit, chém gió, kết bạn, wapmaster, webmaster, johncms, wapego'),
('mod_forum', '2'),
('mod_reg', '1'),
('offer', '0'),
('theme_wap', 'wap'),
('site_access', '2'),
('chat_last', '1678698012'),
('news', 'a:8:{s:4:\"view\";i:1;s:4:\"size\";i:500;s:8:\"quantity\";i:3;s:4:\"days\";i:0;s:6:\"breaks\";b:1;s:7:\"smileys\";b:1;s:4:\"tags\";b:1;s:3:\"kom\";b:1;}'),
('theme_touch', 'bootstrap'),
('theme_web', 'bootstrap');

-- --------------------------------------------------------

--
-- Table structure for table `cms_users_data`
--

CREATE TABLE `cms_users_data` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `key` varchar(30) NOT NULL DEFAULT '',
  `val` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_users_iphistory`
--

CREATE TABLE `cms_users_iphistory` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `ip` bigint NOT NULL DEFAULT '0',
  `ip_via_proxy` bigint NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `date` int NOT NULL,
  `browser` varchar(200) NOT NULL DEFAULT '',
  `robot` varchar(200) NOT NULL DEFAULT '',
  `robot_type` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `ip_via_proxy` varchar(15) NOT NULL DEFAULT '',
  `ref` varchar(200) NOT NULL DEFAULT '',
  `host` int NOT NULL,
  `hits` int NOT NULL,
  `site` varchar(200) NOT NULL DEFAULT '',
  `pop` varchar(200) NOT NULL DEFAULT '',
  `head` varchar(200) NOT NULL DEFAULT '',
  `user` int NOT NULL DEFAULT '0',
  `phone` varchar(200) NOT NULL DEFAULT '',
  `os` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countersall`
--

CREATE TABLE `countersall` (
  `date` int NOT NULL DEFAULT '0',
  `hits` int NOT NULL DEFAULT '0',
  `host` int NOT NULL DEFAULT '0',
  `yandex` int NOT NULL DEFAULT '0',
  `rambler` int NOT NULL DEFAULT '0',
  `google` int NOT NULL DEFAULT '0',
  `mail` int NOT NULL DEFAULT '0',
  `gogo` int NOT NULL DEFAULT '0',
  `yahoo` int NOT NULL DEFAULT '0',
  `bing` int NOT NULL DEFAULT '0',
  `nigma` int NOT NULL DEFAULT '0',
  `qip` int NOT NULL DEFAULT '0',
  `aport` int NOT NULL DEFAULT '0',
  `ask` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farm_area`
--

CREATE TABLE `farm_area` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `type` tinyint UNSIGNED NOT NULL,
  `item_id` smallint UNSIGNED NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `grow_time` int UNSIGNED NOT NULL DEFAULT '0',
  `effect_0_time` int UNSIGNED NOT NULL DEFAULT '0',
  `effect_1_time` int UNSIGNED NOT NULL DEFAULT '0',
  `effect_2_time` int UNSIGNED NOT NULL DEFAULT '0',
  `dead_time` int UNSIGNED NOT NULL DEFAULT '0',
  `collect_time` int UNSIGNED NOT NULL DEFAULT '0',
  `ns` smallint UNSIGNED NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `farm_items`
--

CREATE TABLE `farm_items` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `item_id` smallint UNSIGNED NOT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `count` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farm_warehouse`
--

CREATE TABLE `farm_warehouse` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `product_id` smallint UNSIGNED NOT NULL DEFAULT '0',
  `count` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int UNSIGNED NOT NULL,
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `avt` varchar(25) NOT NULL DEFAULT '',
  `name` mediumtext NOT NULL,
  `text` mediumtext NOT NULL,
  `kom` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phonho_forums`
--

CREATE TABLE `phonho_forums` (
  `id` int UNSIGNED NOT NULL,
  `refid` int UNSIGNED NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `realid` int NOT NULL DEFAULT '0',
  `forum_desc` varchar(255) NOT NULL DEFAULT '',
  `forum_name` varchar(255) NOT NULL,
  `allow` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `phonho_posts`
--

CREATE TABLE `phonho_posts` (
  `id` int UNSIGNED NOT NULL,
  `refid` int UNSIGNED NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `from` varchar(25) NOT NULL DEFAULT '',
  `ip` bigint NOT NULL DEFAULT '0',
  `ip_via_proxy` bigint NOT NULL DEFAULT '0',
  `soft` varchar(255) NOT NULL DEFAULT '',
  `text` mediumtext NOT NULL,
  `post_deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `post_deleted_user` varchar(30) NOT NULL DEFAULT '',
  `edit` varchar(32) NOT NULL DEFAULT '',
  `tedit` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `phonho_threads`
--

CREATE TABLE `phonho_threads` (
  `id` int UNSIGNED NOT NULL,
  `refid` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `from` varchar(25) NOT NULL DEFAULT '',
  `first_post_id` int UNSIGNED NOT NULL DEFAULT '0',
  `time` int UNSIGNED NOT NULL DEFAULT '0',
  `realid` int NOT NULL DEFAULT '0',
  `soft` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(511) NOT NULL,
  `prefix` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `thread_deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `thread_deleted_user` varchar(30) NOT NULL DEFAULT '',
  `sticked` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `portal` int UNSIGNED NOT NULL DEFAULT '0',
  `thread_closed` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `stat_robots`
--

CREATE TABLE `stat_robots` (
  `engine` varchar(255) NOT NULL DEFAULT '',
  `date` int NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `query` varchar(255) NOT NULL DEFAULT '',
  `ua` varchar(255) NOT NULL DEFAULT '',
  `ip` bigint NOT NULL DEFAULT '0',
  `count` int NOT NULL DEFAULT '0',
  `today` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `account` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `fb_id` varchar(32) NOT NULL DEFAULT '',
  `rights` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `failed_login` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `imname` varchar(50) NOT NULL DEFAULT '',
  `sex` varchar(2) NOT NULL DEFAULT '',
  `coin` int UNSIGNED NOT NULL DEFAULT '0',
  `gold` int UNSIGNED NOT NULL DEFAULT '0',
  `xu` int UNSIGNED NOT NULL DEFAULT '0',
  `luong` int UNSIGNED NOT NULL DEFAULT '0',
  `vip_exp` int UNSIGNED NOT NULL DEFAULT '0',
  `komm` int UNSIGNED NOT NULL DEFAULT '0',
  `postforum` int UNSIGNED NOT NULL DEFAULT '0',
  `game_rps_win` int UNSIGNED NOT NULL DEFAULT '0',
  `game_rps_lose` int UNSIGNED NOT NULL DEFAULT '0',
  `dayb` int NOT NULL DEFAULT '0',
  `monthb` int NOT NULL DEFAULT '0',
  `yearb` int NOT NULL DEFAULT '0',
  `datereg` int UNSIGNED NOT NULL DEFAULT '0',
  `lastdate` int UNSIGNED NOT NULL DEFAULT '0',
  `mail` varchar(50) NOT NULL DEFAULT '',
  `skype` varchar(50) NOT NULL DEFAULT '',
  `facebook` varchar(50) NOT NULL DEFAULT '',
  `about` mediumtext NOT NULL,
  `live` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT '',
  `ip` bigint NOT NULL DEFAULT '0',
  `ip_via_proxy` bigint NOT NULL DEFAULT '0',
  `browser` mediumtext NOT NULL,
  `preg` tinyint(1) NOT NULL DEFAULT '0',
  `regadm` varchar(25) NOT NULL DEFAULT '',
  `mailvis` tinyint(1) NOT NULL DEFAULT '0',
  `sestime` int UNSIGNED NOT NULL DEFAULT '0',
  `total_on_site` int UNSIGNED NOT NULL DEFAULT '0',
  `lastpost` int UNSIGNED NOT NULL DEFAULT '0',
  `rest_code` varchar(32) NOT NULL DEFAULT '',
  `rest_time` int UNSIGNED NOT NULL DEFAULT '0',
  `movings` int UNSIGNED NOT NULL DEFAULT '0',
  `place` varchar(30) NOT NULL DEFAULT '',
  `set_user` mediumtext NOT NULL,
  `set_site` mediumtext NOT NULL,
  `day_time` int UNSIGNED NOT NULL DEFAULT '0',
  `chat_read` int UNSIGNED NOT NULL DEFAULT '0',
  `sft_level` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `sft_time` int UNSIGNED NOT NULL DEFAULT '0',
  `daily_reward_received` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `account`, `password`, `fb_id`, `rights`, `failed_login`, `imname`, `sex`, `coin`, `gold`, `xu`, `luong`, `vip_exp`, `komm`, `postforum`, `game_rps_win`, `game_rps_lose`, `dayb`, `monthb`, `yearb`, `datereg`, `lastdate`, `mail`, `skype`, `facebook`, `about`, `live`, `mobile`, `status`, `ip`, `ip_via_proxy`, `browser`, `preg`, `regadm`, `mailvis`, `sestime`, `total_on_site`, `lastpost`, `rest_code`, `rest_time`, `movings`, `place`, `set_user`, `set_site`, `day_time`, `chat_read`, `sft_level`, `sft_time`, `daily_reward_received`) VALUES
(2, 'BOT', 'b1463e4fd5d7d5181b88c39dac60ce4', '', 0, 3, 'Máy chém tự động', 'm', 183710, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 1434326504, 1678700867, '', '', '', 'Máy êm', '', '', '', 2065234085, 0, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0', 1, '', 0, 1434380449, 999, 1434374845, '', 0, 4, 'mainpage', '', '', 0, 0, 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_ads`
--
ALTER TABLE `cms_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_ban_ip`
--
ALTER TABLE `cms_ban_ip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip1` (`ip1`),
  ADD UNIQUE KEY `ip2` (`ip2`);

--
-- Indexes for table `cms_ban_users`
--
ALTER TABLE `cms_ban_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ban_time` (`ban_time`);

--
-- Indexes for table `cms_chat`
--
ALTER TABLE `cms_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_forum_files`
--
ALTER TABLE `cms_forum_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat` (`cat`),
  ADD KEY `subcat` (`subcat`),
  ADD KEY `topic` (`topic`),
  ADD KEY `post` (`post`);

--
-- Indexes for table `cms_forum_rdm`
--
ALTER TABLE `cms_forum_rdm`
  ADD PRIMARY KEY (`topic_id`,`user_id`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `cms_forum_vote`
--
ALTER TABLE `cms_forum_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `topic` (`topic`);

--
-- Indexes for table `cms_forum_vote_users`
--
ALTER TABLE `cms_forum_vote_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic` (`topic`);

--
-- Indexes for table `cms_images`
--
ALTER TABLE `cms_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `cms_likes`
--
ALTER TABLE `cms_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_log`
--
ALTER TABLE `cms_log`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `cms_mail`
--
ALTER TABLE `cms_mail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `time` (`time`),
  ADD KEY `read` (`read`),
  ADD KEY `sys` (`sys`),
  ADD KEY `delete` (`delete`);

--
-- Indexes for table `cms_paid`
--
ALTER TABLE `cms_paid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_profile_posts`
--
ALTER TABLE `cms_profile_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_rps_game`
--
ALTER TABLE `cms_rps_game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_sessions`
--
ALTER TABLE `cms_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `lastdate` (`lastdate`),
  ADD KEY `place` (`place`(10));

--
-- Indexes for table `cms_settings`
--
ALTER TABLE `cms_settings`
  ADD PRIMARY KEY (`key`(30));

--
-- Indexes for table `cms_users_data`
--
ALTER TABLE `cms_users_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `key` (`key`);

--
-- Indexes for table `cms_users_iphistory`
--
ALTER TABLE `cms_users_iphistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_ip` (`ip`);

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`hits`);

--
-- Indexes for table `farm_area`
--
ALTER TABLE `farm_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farm_items`
--
ALTER TABLE `farm_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farm_warehouse`
--
ALTER TABLE `farm_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phonho_forums`
--
ALTER TABLE `phonho_forums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refid` (`refid`),
  ADD KEY `type` (`type`);
ALTER TABLE `phonho_forums` ADD FULLTEXT KEY `text` (`forum_name`);

--
-- Indexes for table `phonho_posts`
--
ALTER TABLE `phonho_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refid` (`refid`),
  ADD KEY `time` (`time`),
  ADD KEY `close` (`post_deleted`),
  ADD KEY `user_id` (`user_id`);
ALTER TABLE `phonho_posts` ADD FULLTEXT KEY `text` (`text`);

--
-- Indexes for table `phonho_threads`
--
ALTER TABLE `phonho_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refid` (`refid`),
  ADD KEY `time` (`time`),
  ADD KEY `close` (`thread_deleted`),
  ADD KEY `user_id` (`user_id`);
ALTER TABLE `phonho_threads` ADD FULLTEXT KEY `text` (`text`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name_lat` (`account`),
  ADD KEY `lastdate` (`lastdate`),
  ADD KEY `place` (`place`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_ads`
--
ALTER TABLE `cms_ads`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_ban_ip`
--
ALTER TABLE `cms_ban_ip`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_ban_users`
--
ALTER TABLE `cms_ban_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_chat`
--
ALTER TABLE `cms_chat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_forum_files`
--
ALTER TABLE `cms_forum_files`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_forum_vote`
--
ALTER TABLE `cms_forum_vote`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_forum_vote_users`
--
ALTER TABLE `cms_forum_vote_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_images`
--
ALTER TABLE `cms_images`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_likes`
--
ALTER TABLE `cms_likes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_log`
--
ALTER TABLE `cms_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_mail`
--
ALTER TABLE `cms_mail`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_paid`
--
ALTER TABLE `cms_paid`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_profile_posts`
--
ALTER TABLE `cms_profile_posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_rps_game`
--
ALTER TABLE `cms_rps_game`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_users_data`
--
ALTER TABLE `cms_users_data`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_users_iphistory`
--
ALTER TABLE `cms_users_iphistory`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `hits` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farm_area`
--
ALTER TABLE `farm_area`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farm_items`
--
ALTER TABLE `farm_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farm_warehouse`
--
ALTER TABLE `farm_warehouse`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phonho_forums`
--
ALTER TABLE `phonho_forums`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phonho_posts`
--
ALTER TABLE `phonho_posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phonho_threads`
--
ALTER TABLE `phonho_threads`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
