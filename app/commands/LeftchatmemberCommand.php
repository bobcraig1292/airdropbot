<?php


namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Left chat member command
 *
 * Gets executed when a member leaves the chat.
 */
class LeftchatmemberCommand extends SystemCommand
{
	/**
	 * @var string
	 */
	protected $name = 'leftchatmember';
	/**
	 * @var string
	 */
	protected $description = 'Left Chat Member';
	
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