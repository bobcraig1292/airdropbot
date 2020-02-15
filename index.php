<?php
/**
 * Front controller
 *
 * https://github.com/imskaaz/airdropbot
 * Licensed under Apache 2.0
 * Any problem please contact me on :
 * https://t.me/imskaa
 * imskaa.co@gmail.com
 * https://brezehost.com
 */

require_once 'vendor/autoload.php';

define('APPROOT_DIR', dirname(__DIR__));

$localConfig = [];

if (file_exists('config.php')) {
	$localConfig = require('config.php');
}
else {
	die('config.php is missing.');
}

require('vendor/autoload.php');

$config = array_merge(
	require('app/config/bot.php'),
	$localConfig
);

$bot = \app\core\CatBot::create($config);
$bot->run();
?>