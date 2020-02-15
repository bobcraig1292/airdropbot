<?php


namespace app\utils;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\TelegramLog;
use Longman\TelegramBot\Request;

class BotDevelopmentHelper
{
	/**
	 * Dumper, to send variable contents to the passed chat_id.
	 *
	 * Used to log and send variable dump (var_export) to the developer or any Telegram chat ID provided.
	 * Will return ServerResponse object for later use.
	 *
	 * @param mixed $data
	 * @param int   $chat_id
	 *
	 * @return ServerResponse
	 * @throws TelegramException
	 *
	 * @see https://github.com/php-telegram-bot/core/wiki/Snippets
	 */
	public static function dump($data, $chat_id = null)
	{
		$dump = var_export($data, true);
		
		// Write the dump to the debug log, if enabled.
		TelegramLog::debug($dump);
		
		// Send the dump to the passed chat_id.
		if ($chat_id !== null || (property_exists(self::class, 'dump_chat_id') && $chat_id = self::$dump_chat_id)) {
			$result = Request::sendMessage([
				'chat_id'                  => $chat_id,
				'text'                     => $dump,
				'disable_web_page_preview' => true,
				'disable_notification'     => true,
			]);
			
			if ($result->isOk()) {
				return $result;
			}
			
			TelegramLog::error('Var not dumped to chat_id %s; %s', $chat_id, $result->printError());
		}
		
		return Request::emptyResponse();
	}
}