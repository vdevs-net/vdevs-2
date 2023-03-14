<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$_breadcrumb_template = [
    'container'       => '<div class="phdr">{breadcrumb}</div>',
    'start'           => '',
    'template_scope'  => '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="itemscope"><a itemprop="url" href="' . SITE_URL . '{link}"><span itemprop="title">{link_name}</span></a></span>',
    'template'        => '<a href="' . SITE_URL . '{link}">{link_name}</a>',
    'template_active' => '<b>{link_name}</b>',
    'separator'       => ' » '
];

$_pagination_template = [
    'container' => '<div class="pagination">{PAGINATION}</div>',
    'base_link' => '<a class="pagenav" href="{URL}%d{SUFFIX}">%s</a>',
    'disabled'  => '<span class="disabled">%s</span>',
    'current'   => '<span class="current"><b>%s</b></span>'
];

$_tab_template = [
    'inactive'  => '<a href="{url}">{name}</a>',
    'active'    => '{name}',
    'delimiter' => ' | ',
    'container' => '%s'
];

$_tag_template = [
    'content' => '<a href="{url}" class="tag">{name}</a>',
    'container' => '{TAGS}'
];
