<?php
defined('_MRKEN_CMS') or die('Restricted access');

class bbcode extends core
{
    private static $code_id;
    private static $code_index;
    private static $code_parts;
    private static $emoticons;
    private static $emoticons_regex;

    // Processing of tags and links
    public static function tags($var, $emoticons = false)
    {
        $var = self::process_code($var);           // Highlighting code
        $var = self::highlight_bb($var);               // Processing references
        $var = self::highlight_url($var);            // Processing references
        $var = self::highlight_bbcode_url($var);       // Processing references in BBcode
        $var = self::youtube($var);
        $var = self::soundcloud($var);
        if ($emoticons) {
            $var = self::process_emoticons($var);
        }
        $var = self::highlight_code($var);
        return $var;
    }
    /*
    -----------------------------------------------------------------
    Обработка смайлов
    -----------------------------------------------------------------
    */
    public static function process_emoticons($str)
    {
        if (empty(self::get_emoticons())) {
            return $str;
        } else {
            $emoticons_regex = self::get_emoticons_regex();
            $str = preg_replace($emoticons_regex['search'], $emoticons_regex['replacement'], $str, 5);
            return $str;
        }
    }

    private static function get_emoticons_regex()
    {
        if (null === self::$emoticons_regex) {
            $emoticons = self::get_emoticons();
            $assets_url = SITE_PATH . '/assets/emoticons/';
            foreach ($emoticons as $folder => $details) {
                foreach ($details['items'] as $key => $value) {
                    self::$emoticons_regex['search'][] = '/(?<=^|[\s\>])' . preg_quote(functions::checkout($key), '/') . '(?=[\s\<\.\?\!\,]|$)/is';
                    self::$emoticons_regex['replacement'][] = '<img src="' . functions::checkout($assets_url . $folder . '/' . $value['url']) . '" alt="' . functions::checkout($key) . '" title="'. functions::checkout($value['title']) . '" width="' . (isset($value['width']) ? $value['width'] : $details['width']) . '" height="' . (isset($value['height']) ? $value['height'] : $details['height']) . '" />';
                }
            }
        }
        return self::$emoticons_regex;
    }

    public static function get_emoticons()
    {
        if (null === self::$emoticons) {
            $emoticons = array();
            $file = ROOTPATH . 'system' . DS . 'configs' . DS . 'emoticons.php';
            if (file_exists($file)) {
                $emoticons = require_once($file);
            }
            self::$emoticons = $emoticons;
        }
        return self::$emoticons;
    }

    /**
     * Парсинг ссылок
     * За основу взята доработанная функция от форума phpBB 3.x.x
     *
     * @param $text
     * @return mixed
     */
    public static function highlight_url($text)
    {
        if (!function_exists('url_callback')) {
            function url_callback($type, $whitespace, $url, $relative_url)
            {
                $orig_url = $url;
                $orig_relative = $relative_url;
                $url = htmlspecialchars_decode($url);
                $relative_url = htmlspecialchars_decode($relative_url);
                $text = '';
                $chars = array('<', '>', '"');
                $split = false;
                foreach ($chars as $char) {
                    $next_split = strpos($url, $char);
                    if ($next_split !== false) {
                        $split = ($split !== false) ? min($split, $next_split) : $next_split;
                    }
                }
                if ($split !== false) {
                    $url = substr($url, 0, $split);
                    $relative_url = '';
                } else {
                    if ($relative_url) {
                        $split = false;
                        foreach ($chars as $char) {
                            $next_split = strpos($relative_url, $char);
                            if ($next_split !== false) {
                                $split = ($split !== false) ? min($split, $next_split) : $next_split;
                            }
                        }
                        if ($split !== false) {
                            $relative_url = substr($relative_url, 0, $split);
                        }
                    }
                }
                $last_char = ($relative_url) ? $relative_url[strlen($relative_url) - 1] : $url[strlen($url) - 1];
                switch ($last_char) {
                    case '.':
                    case '?':
                    case '!':
                    case ':':
                    case ',':
                        $append = $last_char;
                        if ($relative_url) {
                            $relative_url = substr($relative_url, 0, -1);
                        } else {
                            $url = substr($url, 0, -1);
                        }
                        break;

                    default:
                        $append = '';
                        break;
                }
                $short_url = (mb_strlen($url) > 40) ? mb_substr($url, 0, 30) . ' ... ' . mb_substr($url, -5) : $url;
                switch ($type) {
                    case 1:
                        $relative_url = preg_replace('/[&?]sid=[0-9a-f]{32}$/', '', preg_replace('/([&?])sid=[0-9a-f]{32}&/', '$1', $relative_url));
                        $url = $url . '/' . $relative_url;
                        $text = $relative_url;
                        if (!$relative_url) {
                            return $whitespace . $orig_url . '/' . $orig_relative;
                        }
                        break;

                    case 2:
                        $text = $short_url;
                        if (!isset(core::$user_set['direct_url']) || !core::$user_set['direct_url']) {
                            $url = SITE_PATH . '/misc/go?url=' . rawurlencode($url);
                        }
                        break;

                    case 4:
                        $text = $short_url;
                        $url = 'mailto:' . $url;
                        break;
                }
                $url = htmlspecialchars($url);
                $text = htmlspecialchars($text);
                $append = htmlspecialchars($append);

                return $whitespace . '<a href="' . $url . '" target="_blank">' . $text . '</a>' . $append;
            }
        }

        // Обработка внутренних ссылок
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])(' . preg_quote(SITE_URL, '#') . ')/((?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(1, $matches[1], $matches[2], $matches[3]);
            },
            $text
        );

        // Обработка обычных ссылок типа xxxx://aaaaa.bbb.cccc. ...
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])([a-z][a-z\d+]*:/{2}(?:(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(2, $matches[1], $matches[2], '');
            },
            $text
        );

        return $text;
    }

    /*
    -----------------------------------------------------------------
    Удаление bbCode из текста
    -----------------------------------------------------------------
    */
    static function notags($var = '', $emoticons = false)
    {
        $var = preg_replace('#\[color=(.+?)\](.+?)\[/color]#si', '$2', $var);
        $var = preg_replace('#\[code=(.+?)\](.+?)\[/code]#si', '$2', $var);
        $var = preg_replace('#\[spoiler=(.+?)](.+?)\[/spoiler]#si', '$2', $var);
        $var = preg_replace('#\[url=(.+?)](.+?)\[/url]#si', '$2 ($1)', $var);
        $var = preg_replace('#\[quote=([^\]]+?)](.+?)\[/quote]#si', '', $var);
        $var = preg_replace('#\[img](.+?)\[/img]#i', '$1', $var);
        $var = preg_replace('#\[img=[1-9]+[0-9]+x[1-9]+[0-9]+\](.+?)\[/img\]#i', '$1', $var);
        $replace = array(
            '[small]'  => '',
            '[/small]' => '',
            '[big]'    => '',
            '[/big]'   => '',
            '[green]'  => '',
            '[/green]' => '',
            '[red]'    => '',
            '[/red]'   => '',
            '[blue]'   => '',
            '[/blue]'  => '',
            '[b]'      => '',
            '[/b]'     => '',
            '[i]'      => '',
            '[/i]'     => '',
            '[u]'      => '',
            '[/u]'     => '',
            '[s]'      => '',
            '[/s]'     => '',
            '[img]'    => '',
            '[/img]'   => '',
            '[quote]'  => '',
            '[/quote]' => '',
            '[*]'      => '',
            '[/*]'     => '',
            '[php]'    => '',
            '[/php]'   => ''
        );

        $var = strtr($var, $replace);
        if ($emoticons) {
            $var = self::process_emoticons($var);
        }
        return $var;
    }


    /*
    -----------------------------------------------------------------
    Подсветка кода
    -----------------------------------------------------------------
    */
    private static function process_code($var)
    {
        self::$code_id = uniqid();
        self::$code_index = 0;
        self::$code_parts = array();
        $var = preg_replace_callback('#\[code=(.+?)\](.+?)\[\/code]#is', 'self::codeCallback', $var);
        $var = preg_replace_callback('#\[php\](.+?)\[\/php\]#s', 'self::phpCodeCallback', $var);

        return $var;
    }

    private static $geshi;

    private static function phpCodeCallback($code)
    {
        return self::codeCallback(array(1 => 'php', 2 => $code[1]));
    }

    private static function codeCallback($code)
    {
        $parsers = array(
            'php'  => 'php',
            'css'  => 'css',
            'html' => 'html5',
            'js'   => 'javascript',
            'sql'  => 'sql',
            'twig' => 'twig',
            'c++'  => 'cpp',
            'cpp'  => 'cpp',
            'text' => 'text'
        );

        $parser = isset($code[1]) && isset($parsers[$code[1]]) ? $parsers[$code[1]] : 'php';

        if (null === self::$geshi) {
            self::$geshi = new \GeSHi;
            self::$geshi->set_link_styles(GESHI_LINK, 'text-decoration: none');
            self::$geshi->set_link_target('_blank');
            self::$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
            self::$geshi->set_line_style('background: rgba(255, 255, 255, 0.5)', 'background: rgba(255, 255, 255, 0.35)', false);
            self::$geshi->set_code_style('padding-left: 6px; white-space: pre-wrap');
            self::$geshi->enable_keyword_links(false);
        }

        self::$geshi->set_language($parser);
        $php = strtr($code[2], array('<br />' => '', '</p><p>' => ''));
        $php = html_entity_decode(trim($php), ENT_QUOTES, 'UTF-8');
        self::$geshi->set_source($php);
        self::$code_index++;
        self::$code_parts[self::$code_index] = array(
            'type'   => $parser,
            'source' => self::$geshi->parse_code()
        );

        return '[code|' . self::$code_id . ']' . self::$code_index . '[/code]';
    }

    private static function highlight_code($var)
    {
        $var = preg_replace_callback(
            '#\[code\|' . self::$code_id . '\](\d+)\[\/code\]#s',
            function ($code)
            {
                $part = self::$code_parts[$code[1]];
                unset(self::$code_parts[$code[1]]);
                return '</p><div class="bbCodeBlock bbCodePHP"><div class="type">' . mb_strtoupper($part['type']) . '</div><div class="code" style="overflow-x: auto">' . $part['source'] . '</div></div><p>';
            },
            $var);

        return $var;
    }

    /*
    -----------------------------------------------------------------
    Обработка URL в тэгах BBcode
    -----------------------------------------------------------------
    */
    private static function highlight_bbcode_url($var)
    {
        if (!function_exists('process_url')) {
            function process_url($url)
            {
                $home = parse_url(SITE_URL);
                $tmp = parse_url($url[1]);
                if ($home['host'] == $tmp['host'] || isset(core::$user_set['direct_url']) && core::$user_set['direct_url']) {
                    return '<a href="' . $url[1] . '">' . $url[2] . '</a>';
                } else {
                    return '<a href="' . SITE_PATH . '/misc/go?url=' . urlencode(htmlspecialchars_decode($url[1])) . '" target="_blank">' . $url[2] . '</a>';
                }
            }
        }

        return preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]~', 'process_url', $var);
    }

    /*
    -----------------------------------------------------------------
    Обработка bbCode
    -----------------------------------------------------------------
    */
    private static function highlight_bb($var)
    {
        $image_limit = defined('IMAGE_PER_MESSAGE') ? IMAGE_PER_MESSAGE : 5;
        if (!function_exists('process_imgur')) {
            function process_imgur($matches) {
                $size = (core::$device == 'wap' ? 'm' : 'h');

                if (core::$device == 'wap') {
                    return '</p><div style="text-align:center"><img src="//i.imgur.com/' . $matches[4] . $size . '.' . $matches[5] . '" alt="[IMAGE]" ' . ($matches[1] ? ' data-width="' . $matches[2] . '" data-height="' . $matches[3] . '"' : '') . ' /></div><div class="center"><small><a href="//i.imgur.com/' . $matches[4] . '.' . $matches[5] . '" target="_blank">View full size</a></small></div><p>';
                }

                return '</p><div style="text-align:center"><a class="noPusher" data-fancybox="' . /* md5($matches[4] . '.' . $matches[5]) . */ '" ' . ($matches[1] ? ' data-width="' . $matches[2] . '" data-height="' . $matches[3] . '"' : '') . ' href="//i.imgur.com/' . $matches[4] . '.' . $matches[5] . '"><img src="//i.imgur.com/' . $matches[4] . $size . '.' . $matches[5] . '" alt="[IMAGE]" ' . ($matches[1] ? ' data-width="' . $matches[2] . '" data-height="' . $matches[3] . '"' : '') . ' /></a></div><p>';
            }
        }
        $var = preg_replace_callback('#\[img(=([1-9][0-9]+)x([1-9][0-9]+))?\]https?://i.imgur.com/([\da-z]+)\.(png|jpg)\[/img\]#is', 'process_imgur', $var, 5, $count);

        // search list
        $search = array(
            '#(\r\n|[\r\n])#',
            '#\[b](.+?)\[/b]#is', // Bold
            '#\[i](.+?)\[/i]#is', // Italic
            '#\[u](.+?)\[/u]#is', // Underline
            '#\[s](.+?)\[/s]#is', // Strikethrough
            '#\[small](.+?)\[/small]#is', // Small Font
            '#\[big](.+?)\[/big]#is', // Big font
            '#\[red](.+?)\[/red]#is', // red
            '#\[green](.+?)\[/green]#is', // green
            '#\[blue](.+?)\[/blue]#is', // blue
            '!\[color=(#[0-9a-f]{3}|#[0-9a-f]{6}|[a-z\-]+)](.+?)\[/color]!is', // font color
            '#\[quote](.+?)\[/quote]#is', // quote
            '#\[quote=([\d]+?),([\d]+?),([\da-z.@_]+?)](.+?)\[/quote]#is', // quote
            '#\[\*](.+?)\[/\*]#is', // list
            '#\[spoiler=(.+?)](.+?)\[/spoiler]#is' // spoiler
        );
        // List of replacement
        $replace = array(
            '',
            '<span style="font-weight: bold">$1</span>', // Жирный
            '<span style="font-style:italic">$1</span>', // Курсив
            '<span style="text-decoration:underline">$1</span>', // Подчеркнутый
            '<span style="text-decoration:line-through">$1</span>', // Зачеркнутый
            '<span style="font-size:x-small">$1</span>', // Маленький шрифт
            '<span style="font-size:large">$1</span>', // Большой шрифт
            '<span style="color:red">$1</span>', // Красный
            '<span style="color:green">$1</span>', // Зеленый
            '<span style="color:blue">$1</span>', // Синий
            '<span style="color:$1">$2</span>', // Цвет шрифта
            '</p><div class="quote"><blockquote>$1</blockquote></div><p>', // Цитата
            '</p><div class="bbCodeBlock bbCodeQuote"><div class="attribution type"><a href="' . SITE_URL . '/profile/$3.$2/">$3</a> đã viết <a href="' . SITE_URL . '/forum/posts/$1/">↑</a></div><blockquote><p>$4</p></blockquote></div><p>', // Цитата
            '</p><div class="bblist">$1</div><p>', // Список
            '</p><div><div class="spoilerhead" onclick="var _n=this.parentNode.getElementsByTagName(\'div\')[1];if(_n.style.display==\'none\'){_n.style.display=\'\';}else{_n.style.display=\'none\';}">$1 (+/-)</div><div class="spoilerbody" style="display:none">$2</div></div><p>'
        );
        if ($count < $image_limit){
            $var = preg_replace_callback('#\[img(=([1-9][0-9]+)x([1-9][0-9]+))?](https?://)([\da-z.\-_/]+)\.(png|jpg)\[/img]#is',
                function($matches)
                {
                    return '</p><div style="text-align:center"><img src="' . SITE_URL . '/proxy.php?url=' . $matches[4] . '' . $matches[5] . '.' . $matches[6] . '" alt="[IMAGE]" ' . ($matches[1] ? ' data-width="' . $matches[2] . '" data-height="' . $matches[3] . '"' : '') . ' /></div><p>';
                },
                $var, $image_limit - $count
            );
        }
        return preg_replace($search, $replace, $var);
    }

    public static function soundcloud($var)
    {

        return preg_replace_callback(
            '#\[soundcloud\](.+?)\[\/soundcloud\]#s',
            function ($matches) {
                if (core::$device !== 'wap') {
                    return '</p><div class="embed-wrapper"><div class="embed-container soundcloud"><iframe allowfullscreen="allowfullscreen" src="//w.soundcloud.com/player/?url=' . $matches[1] . '&auto_play=false&visual=false" frameborder="0"></iframe></div></div><p>';
                } else {
                    return '</p><div class="embed-wrapper"><a target="_blank" href="//w.soundcloud.com/player/?url=' . $matches[1] . '&auto_play=false&visual=false">' . $matches[1] . '</a></div><p>';
                }
            },
            $var, 3
        );
    }

    public static function youtube($var)
    {

        return preg_replace_callback(
            '#\[youtube\](.+?)\[\/youtube\]#s',
            function ($matches) {
                if (preg_match('/youtube\.com/', $matches[1])) {
                    $values = explode('v=', $matches[1]);
                    if (isset($values[1])) {
                        $valuesto = explode('&', $values[1]);
                        return self::youtubePlayer($valuesto[0]);
                    }
                    return $matches[0];
                } elseif (preg_match('/youtu\.be/', $matches[1])) {
                    return self::youtubePlayer(trim(parse_url($matches[1], PHP_URL_PATH), '/'));
                } else {
                    $valuesto = explode('&', $matches[1]);
                    return self::youtubePlayer($valuesto[0]);
                }
            },
            $var, 3
        );
    }

    public static function youtubePlayer($result)
    {
        if (core::$device !== 'wap') {
            return '</p><div class="embed-wrapper"><div class="embed-container"><iframe allowfullscreen="allowfullscreen" src="//www.youtube.com/embed/' . $result . '?autoplay=0&rel=0&modestbranding=1" frameborder="0"></iframe></div></div><p>';
        } else {
            return '</p><div class="embed-wrapper"><a target="_blank" href="//m.youtube.com/watch?v=' . $result . '"><img src="//img.youtube.com/vi/' . $result . '/1.jpg" border="0" alt="youtube.com/embed/' . $result . '"></a></div><p>';
        }
    }

    /*
    -----------------------------------------------------------------
    Панель кнопок bbCode (для компьютеров)
    -----------------------------------------------------------------
    */
    public static function auto_bb($form, $field)
    {
        if (self::$device === 'wap')
        {
            return;
        }
        $colors = array(
            'ffffff', 'bcbcbc', '708090', '6c6c6c', '454545',
            'fcc9c9', 'fe8c8c', 'fe5e5e', 'fd5b36', 'f82e00',
            'ffe1c6', 'ffc998', 'fcad66', 'ff9331', 'ff810f',
            'd8ffe0', '92f9a7', '34ff5d', 'b2fb82', '89f641',
            'b7e9ec', '56e5ed', '21cad3', '03939b', '039b80',
            'cac8e9', '9690ea', '6a60ec', '4866e7', '173bd3',
            'f3cafb', 'e287f4', 'c238dd', 'a476af', 'b53dd2'
        );

        $font_color = '';
        foreach ($colors as $value) {
            $font_color .= '<a href="javascript:tag(\'[color=#' . $value . ']\', \'[/color]\'); show_hide(\'color\');" style="background-color:#' . $value . ';" tabindex="-1"></a>';
        }
        $emoticons = self::get_emoticons();
        $emoticons = $emoticons['default'];
        $bb_smileys = '';
        if (!empty($emoticons)) {
            foreach ($emoticons['items'] as $key => $value) {
                $bb_smileys .= '<a href="javascript:tag(\' ' . functions::checkout(str_replace("'", "\'", $key)) . '\', \'\'); show_hide(\'sm\');" tabindex="-1">' . functions::checkout($key) . '</a> ';
            }
            $bb_smileys = self::process_emoticons($bb_smileys);
        }
        $code = array(
            'php',
            'css',
            'js',
            'html',
            'sql',
            'twig',
            'c++',
            'text'
        );
        $codebtn = '';
        foreach ($code as $val) {
            $codebtn .= '<a href="javascript:tag(\'[code=' . $val . ']\', \'[/code]\'); show_hide(\'code\');" tabindex="-1">' . strtoupper($val) . '</a>';
        }
        $out = '<div class="bbcode-editor"><script type="text/javascript">'.
            'function tag(text1,text2){if((document.selection)){document.' . $form . '.' . $field . '.focus();document.' . $form . '.document.selection.createRange().text = text1+document.' . $form . '.document.selection.createRange().text+text2}else if(document.forms[\'' . $form . '\'].elements[\'' . $field . '\'].selectionStart!=undefined){var element=document.forms[\'' . $form . '\'].elements[\'' . $field . '\'];var str=element.value;var start=element.selectionStart;var length=element.selectionEnd-element.selectionStart;element.value=str.substr(0,start)+text1+str.substr(start,length)+text2+str.substr(start+length)}else{document.' . $form . '.' . $field . '.value+=text1+text2}}'.
            'function show_hide(a){b=document.getElementById(a);if(b.style.display=="none"){b.style.display="block"}else{b.style.display="none"}}'.
            '</script>'.
            '<div class="toolbar"><a href="javascript:tag(\'[b]\', \'[/b]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/bold.gif" alt="b" title="' . self::$lng['tag_bold'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[i]\', \'[/i]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/italics.gif" alt="i" title="' . self::$lng['tag_italic'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[u]\', \'[/u]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/underline.gif" alt="u" title="' . self::$lng['tag_underline'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[s]\', \'[/s]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/strike.gif" alt="s" title="' . self::$lng['tag_strike'] . '" border="0"/></a> ' .
            '<a href="javascript:show_hide(\'color\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/color.gif" title="' . self::$lng['color_text'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[*]\', \'[/*]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/list.gif" alt="s" title="' . self::$lng['tag_list'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[spoiler=]\', \'[/spoiler]\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/sp.gif" alt="spoiler" title="Spoiler" border="0"/></a> ' .
            '<a href="javascript:tag(\'[quote]\', \'[/quote]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/quote.gif" alt="quote" title="' . self::$lng['tag_quote'] . '" border="0"/></a> ' .
            '<a href="javascript:tag(\'[url=]\', \'[/url]\')" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/link.gif" alt="url" title="' . self::$lng['tag_link'] . '" border="0"/></a> ' .
            '<a href="javascript:show_hide(\'code\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/code.gif" title="Code" border="0"/></a> ' .
            '<a href="javascript:show_hide(\'img\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/image.gif" alt="img" title="IMG" border="0"/></a> ' .
            '<a href="javascript:tag(\'[youtube]\', \'[/youtube]\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/youtube.gif" title="Youtube" border="0"/></a> ' .
            '<a href="javascript:show_hide(\'sm\');" tabindex="-1"><img src="' . SITE_PATH . '/assets/images/bb/smileys.gif" alt="sm" title="' . self::$lng['smileys'] . '" border="0"/></a></div>'.
                '<div id="sm" style="display:none">' . $bb_smileys . '</div>' .
                '<div id="code" class="codepopup" style="display:none;">' . $codebtn . '</div>' .
                '<div id="color" class="bbpopup" style="display:none;">' . $font_color . '</div>' .
                '<div id="img" class="codepopup" style="display:none;"><a href="javascript:tag(\'[img]\', \'[/img]\'); show_hide(\'img\');" tabindex="-1">IMG</a><a href="' . SITE_URL . '/tools/image-upload/upload" tabindex="-1" target="_blank">Upload new Image</a></div></div>';

        return $out;
    }
}