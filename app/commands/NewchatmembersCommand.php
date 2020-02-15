<?php


namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * New chat member command
 */
class NewchatmembersCommand extends SystemCommand
{
	/**
	 * @var string
	 */
	protected $name = 'newchatmembers';
	/**
	 * @var string
	 */
	protected $description = 'New Chat Members';
	
	/**
	 * Command execute method
	 *
	 * @return ServerResponse
	 */
	public function execute()
	{
		$message = $this->getMessage();
		$chat_id = $message->getChat()->getId();
		
		return Request::deleteMessage([
			'chat_id'    => $chat_id,
			'message_id' => $message->getMessageId(),
		]);
	}
}