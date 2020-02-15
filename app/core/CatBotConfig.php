<?php

namespace app\core;

class CatBotConfig
{
	/**
	 * @var $config array
	 */
	private static $_config;
	
	public function __construct($config){
		self::$_config = $config;
	}
	/**
	 * Returns configuration parameter value
	 * @param $key string
	 *
	 * @return mixed
	 */
	public function get(string $key)
	{
		return isset(self::$_config[$key]) ? self::$_config[$key] : null;
	}
}