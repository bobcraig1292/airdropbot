<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\core\CatBot;
use app\domain\CampaignHelper;
use app\utils\KeyboardHelper;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends UserCommand
{
	/**
	 * @var string
	 */
	protected $name = 'start';
	/**
	 * @var string
	 */
	protected $description = 'Start command';
	/**
	 * @var string
	 */
	protected $usage = '/start - Just start this damn bot';
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
		$referral_code = $this->getMessage()->getText(true);
		
		Request::sendChatAction([
			'chat_id' => $chat_id,
			'action' => ChatAction::TYPING,
		]);
		
		if (!empty($referral_code)){
			$referral_link = CampaignHelper::getUniqueReferralLink(CatBot::app()->config->get('bot_username'), $referral_code);
			if (!CatBot::app()->campaignService->isUserHaveAlreadyStartedCampaign($user_id)){
				CatBot::app()->campaignService->sendRewardToReferrer($referral_link, CatBot::app()->config->get('referrer_invite_reward_tokens_count'));
			}
		}
		
		$text    =  'Hello, User!! I am the ' . CatBot::app()->config->get('bot_title') . '. 🎉'
					. PHP_EOL .
					'If you completely fulfill all Airdrop conditions, you will receive ' .
					CatBot::app()->config->get('campaign_complete_reward_tokens_count') . ' ' .  CatBot::app()->config->get('token_name') . ' ($5.00 USD) tokens' .
					' and ' . CatBot::app()->config->get('referrer_invite_reward_tokens_count') . ' ' .  CatBot::app()->config->get('token_name') . ' ($1 USD) ' .
					'per referral link recruited. 🏆'
					. PHP_EOL .
					'Press "Start campaign" button to start the Airdrop campaign 🚀';
		
		$data = [
			'chat_id' => $chat_id,
			'text'    => $text,
			'reply_markup' => KeyboardHelper::getStartCampaignKeyboard(),
		];
		
		return Request::sendMessage($data);
	}
}