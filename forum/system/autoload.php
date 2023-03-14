<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

// Autoload class
function autoload($name) {
    if (preg_match('#[^a-zA-Z_]#', $name)) {
        return;
    }
    $name = str_replace('_', DS, $name);
    $file = ROOTPATH . 'system' . DS . 'classes' . DS . $name . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
}
spl_autoload_register('autoload');