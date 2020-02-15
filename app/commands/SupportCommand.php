<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use app\core\CatBot;
use app\utils\KeyboardHelper;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class SupportCommand extends UserCommand
{
	/**
	 * @var string
	 */
	protected $name = 'support';
	/**
	 * @var string
	 */
	protected $description = 'Show support service contacts';
	/**
	 * @var string
	 */
	protected $usage = '/support - Show support service contacts';
	/**
	 * @var bool
	 */
	protected $private_only = true;
	
	/**
	 * Command execute method
	 *
	 * @return ServerResponse
	 * @throws TelegramException
	 */
	public function execute()
	{
		$message = $this->getMessage();
		$chat_id = $message->getChat()->getId();
		
		Request::sendChatAction([
			'chat_id' => $chat_id,
			'action' => ChatAction::TYPING,
		]);
		
		$text .= 'If you need help ☎️, ask in our group: ' . CatBot::app()->config->get('support_link');
		$text .= PHP_EOL;
		$text .= 'This bot made by @imskaa';
		$data = [
			'chat_id' => $chat_id,
			'text' => $text,
			'reply_markup' => KeyboardHelper::getMainMenuKeyboard()
		];
		
		return Request::sendMessage($data);
	}
}