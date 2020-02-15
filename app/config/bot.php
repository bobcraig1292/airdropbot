<?php

return [
	/**
	 * Path to commands classes folder
	 * @see https://github.com/php-telegram-bot/core
	 */
	'commands_paths' => [
		dirname(__DIR__) . '/commands',
	],
	/**
	 * Path to logs folder
	 */
	'logs_path' => dirname(__DIR__) . '/logs',
	/**
	 * Use or do not use command text aliases
	 * Commands aliases vocabulary is provided by config.php file
	 */
	'use_commands_aliases' => true
];