<?php


namespace app\core;

use Longman\TelegramBot\ConversationDB;
use Longman\TelegramBot\Telegram;


class CatBotTelegram extends Telegram
{
	/**
	 * @inheritDoc
	 */
	public function enableMySql(array $credential, $table_prefix = null, $encoding = 'utf8mb4')
	{
		$this->pdo = CatBotDB::initialize($credential, $this, $table_prefix, $encoding);
		ConversationDB::initializeConversation();
		$this->mysql_enabled = true;
		
		return $this;
	}
}