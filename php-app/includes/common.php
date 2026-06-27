<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!ini_get('register_globals')) {
    extract($_GET);
    extract($_POST);
    extract($_COOKIE);
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

date_default_timezone_set('UTC');

define('SITE_NAME', 'Retro Arcade Labs');
define('SITE_URL', 'http://localhost:8470');
?>