<?php
defined('_MRKEN_CMS') or die('Restricted access');

class breadcrumb
{
    private $container       = '';
    private $start           = '';
    private $template        = '';
    private $active_template = '';
    private $separator       = '';
    private $makeShort       = false;
    private $items           = array();

    function __construct($scope = false, $makeShort = false)
    {
        global $_breadcrumb_template;

        $this->makeShort = $makeShort;

        if (isset($_breadcrumb_template['start'])) {
            $this->start = $_breadcrumb_template['start'];
        }

        if ($scope) {
            if (isset($_breadcrumb_template['template_scope'])) {
                $this->template = $_breadcrumb_template['template_scope'];
            }
        } else {
            if (isset($_breadcrumb_template['template'])) {
                $this->template = $_breadcrumb_template['template'];
            }
        }

        if (isset($_breadcrumb_template['template_active'])) {
            $this->active_template = $_breadcrumb_template['template_active'];
        }

        if (isset($_breadcrumb_template['separator'])) {
            $this->separator = $_breadcrumb_template['separator'];
        }

        if (isset($_breadcrumb_template['container'])) {
            $this->container = $_breadcrumb_template['container'];
        }
    }

    public function add()
    {
        $args = func_get_args();
        $num_args = count($args);
        if ($num_args == 2) {
            if (empty($args[1])) {
                $args[1] = $args[0];
            }
            $this->items[] = $args;
        } elseif ($num_args == 1) {
            if (is_array($args[0])) {
                foreach ($args[0] as $arg) {
                    if (is_array($arg)) {
                        if (count($arg) == 2) {
                           $this->items[] = array($arg[0], $arg[1]);
                        } else {
                            $this->items[] = array($arg[0], '');
                        }
                    } else {
                        $this->items[] = array($arg, '');
                    }
                }
            } else {
                $this->items[] = array($args[0], '');
            }
        }
    }

    public function out()
    {
        $out = array();
        if (!empty($this->start)) {
           $out[] = $this->start;
        }

        foreach ($this->items as $key => $link)
        {
            if (empty($link[1])) {
                if ($this->makeShort) {
                    $link[0] = mb_strlen($link[0]) > 31 ? (mb_substr($link[0], 0, 31) . '...') : $link[0];
                }
                $out[] = str_replace('{link_name}', $link[0], $this->active_template);
                break;
            } else {
                if ($this->makeShort) {
                    $link[1] = mb_strlen($link[1]) > 31 ? (mb_substr($link[1], 0, 31) . '...') : $link[1];
                }
                $out[] = strtr($this->template, array(
                    '{link}' => functions::checkout($link[0]),
                    '{link_name}' => functions::checkout($link[1])
                ));
            }
        }
        $out = implode($this->separator, $out);
        if (!empty($this->container)) {
            $out = str_replace('{breadcrumb}', $out, $this->container);
        }
        return $out;
    }
}