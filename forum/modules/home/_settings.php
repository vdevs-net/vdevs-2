<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (isset($_SESSION['ref'])) {
    unset($_SESSION['ref']);
}