<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use app\core\CatBot;
use app\utils\KeyboardHelper;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\ChatAction;

class StartCampaignCommand extends UserCommand
{
	/**
	 * @var string
	 */
	protected $name = 'startcampaign';
	/**
	 * @var string
	 */
	protected $description = 'Start our token earning campaign';
	/**
	 * @var string
	 */
	protected $usage = '/startcampaign - Start our token campaign, fulfill all conditions and earn FREE tokens';
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
		$user_id = $message->getFrom()->getId();
		
		
		Request::sendChatAction([
			'chat_id' => $chat_id,
			'action'  => ChatAction::TYPING,
		]);
		
		$text = '';
		
		if (CatBot::app()->campaignService->getStartedCampaignsCount() >= CatBot::app()->config->get('max_campaigns_count') && !CatBot::app()->campaignService->isUserHaveAlreadyStartedCampaign($user_id)){
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'  => "Sorry, you are late. Airdrop phase is closed.",
				'reply_markup'=> KeyboardHelper::getEmptyKeyboard()
			]);
			return Request::emptyResponse();
		}
		
		if (CatBot::app()->campaignService->isUserHaveAlreadyStartedCampaign($user_id)){
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'  => "I think you already have started campaign. Check chat history.",
				'reply_markup'=> KeyboardHelper::getEmptyKeyboard()
			]);
			return Request::emptyResponse();
		}
		
		Request::sendMessage([
			'chat_id' => $chat_id,
			'text'  => "OK. Let's start. Brother !",
			'reply_markup'=> KeyboardHelper::getEmptyKeyboard()
		]);
		
		
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'  => 'Note, all was recorded and was checked before we send the reward. If you enter incorrect data, you will not receive the rewards.',
				'reply_markup'=> KeyboardHelper::getEmptyKeyboard()
			]);
			
		$campaignStarted = CatBot::app()->campaignService->createNewUserCampaign($user_id);
		
		if ($campaignStarted){
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'  => 'First, you have to join our telegram group and channel.',
				'reply_markup'=> KeyboardHelper::getJoinToKeyboard(
					CatBot::app()->config->get('telegram_group_to_follow_link_url'),
					CatBot::app()->config->get('telegram_channel_to_follow_link_url')
					
				)
			]);
			
			
			Request::sendMessage([
				'chat_id' => $chat_id,
				'text'  => 'After that tab "Check me" button.',
				'reply_markup'=> KeyboardHelper::getCheckMeKeyboard()
			]);
		}
		else{
			$text = 'I think i have broken database. 💀'
				. PHP_EOL .
				'Sorry, but i can not make you happy with tokens right now. 😿'
				. PHP_EOL .
				'Try again later. ⌛';
		}
		
		$data = [
			'chat_id' => $chat_id,
			'text'    => $text
		];
		
		if (isset($keyboard)){
			$data['reply_markup'] = $keyboard;
		}
		
		return Request::sendMessage($data);
	}
}