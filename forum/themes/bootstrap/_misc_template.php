<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$_breadcrumb_template = [
    'container'       => '<ol class="breadcrumb">{breadcrumb}</ol>',
    'start'           => '',
    'template_scope'  => '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . SITE_PATH . '{link}" itemprop="url"><span itemprop="title">{link_name}</span></a></li>',
    'template'        => '<li><a href="' . SITE_PATH . '{link}">{link_name}</a></li>',
    'template_active' => '<li class="active">{link_name}</li>',
    'separator'       => ''
];

$_pagination_template = [
    'container' => '<ul class="pagination pagination-sm">{PAGINATION}</ul>',
    'base_link' => '<li><a class="pagenav" href="{URL}%d{SUFFIX}">%s</a></li>',
    'disabled'  => '<li class="disabled"><span>%s</span></li>',
    'current'   => '<li class="active"><span class="current"><b>%s</b></span></li>'
];

$_tab_template = [
    'inactive'  => '<li><a href="{url}">{name}</a></li>',
    'active'    => '<li class="active"><a href="#">{name}</a></li>',
    'delimiter' => '',
    'container' => '<ul class="nav nav-tabs nav-response">%s</ul>' 
];

$_tag_template = [
    'content' => '<li><a href="{url}" class="tagItem"><span class="arrow"></span>{name}</a></li>',
    'container' => '<ul class="tagList">{TAGS}</ul>'
];
