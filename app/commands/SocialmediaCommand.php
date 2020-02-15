<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use app\core\CatBot;
use app\utils\KeyboardHelper;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class SocialmediaCommand extends UserCommand
{
	/**
	 * @var string
	 */
	protected $name = 'socialmedia';
	/**
	 * @var string
	 */
	protected $description = 'Show social media with information you can use to';
	/**
	 * @var string
	 */
	protected $usage = '/socialmedia - Show social media with information you can use to';
	
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
		
		$text = 'Our Social Media:';
		$text .= PHP_EOL . PHP_EOL;
		
		if (CatBot::app()->config->get('telegram_group_to_follow_link_url')){
			$text .= '<a href="'. CatBot::app()->config->get('telegram_group_to_follow_link_url') .'">Telegram Group</a>';
			$text .= PHP_EOL;
		}
		if (CatBot::app()->config->get('telegram_channel_to_follow_link_url')){
			$text .= '<a href="'. CatBot::app()->config->get('telegram_channel_to_follow_link_url') .'">Telegram Channel</a>';
			$text .= PHP_EOL;
		}
		if (CatBot::app()->config->get('twitter_profile_url')){
			$text .= '<a href="'. CatBot::app()->config->get('twitter_profile_url') .'">Twitter</a>';
			$text .= PHP_EOL;
		}
		if (CatBot::app()->config->get('instagram_profile_url')){
			$text .= '<a href="'. CatBot::app()->config->get('instagram_profile_url') .'">Instagram</a>';
			$text .= PHP_EOL;
		}
		if (CatBot::app()->config->get('website_profile_url')){
			$text .= '<a href="'. CatBot::app()->config->get('website_profile_url') .'">Website</a>';
			$text .= PHP_EOL;
		}
		$data = [
			'chat_id' => $chat_id,
			'text' => $text,
			'reply_markup' => KeyboardHelper::getMainMenuKeyboard(),
			'parse_mode' => 'html'
		];
		
		return Request::sendMessage($data);
	}
}