<?php


namespace Longman\TelegramBot\Commands\UserCommands;


use app\core\CatBot;
use app\utils\CommunityHelper;
use app\utils\KeyboardHelper;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ChatMember;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class CheckmeCommand extends UserCommand
{
	/**
	 * @var string
	 */
	protected $name = 'checkme';
	/**
	 * @var string
	 */
	protected $description = 'Checks do you are already joined all necessary groups and channel';
	/**
	 * @var string
	 */
	protected $usage = '/checkme - Checks do you are already joined all necessary groups and channels';
	/**
	 * @var bool
	 */
	protected $private_only = false;
	
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
			'action' => ChatAction::TYPING,
		]);
		
		if (!CatBot::app()->campaignService->isUserHaveAlreadyStartedCampaign($user_id)) {
			$text = 'I think you did not started our campaign yet. Type "Start campaign" to start it.';
			$keyboard = KeyboardHelper::getEmptyKeyboard();
		} else {
			
			$user_campaign = CatBot::app()->campaignService->getActiveUserCampaign($user_id);
			
			$must_joined = [
				'group' => CommunityHelper::checkUserIsMemberOfChat($user_id, CatBot::app()->config->get('telegram_group_to_follow_id'))
			];
			
			if (CatBot::app()->config->get('telegram_channel_to_follow_id')) {
				$must_joined['channel'] = CommunityHelper::checkUserIsMemberOfChat($user_id, CatBot::app()->config->get('telegram_channel_to_follow_id'));
			}
			
			if (!in_array(false, $must_joined)) {
				$user_campaign->setIsFollower(1);
				CatBot::app()->campaignService->updateCampaign($user_campaign);
				Request::sendMessage([
					'chat_id' => $chat_id,
					'text'  => 'Awesome! You done with a first step.',
					'reply_markup'=> KeyboardHelper::getEmptyKeyboard()
				]);
				$text = 'Now you have to follow our twitter acc and retweet pinned tweet from it. Please, paste the link to you retweet below:';
				
				$keyboard = KeyboardHelper::getRetweetKeyboard( 'Retweet pinned tweet', CatBot::app()->config->get('twitter_profile_url'));
				
			} else {
				$keyboard = KeyboardHelper::getCheckMeKeyboard();
				
				$text = "You haven't pass the check. You did not join ";
				if (!$must_joined['group']) {
					$text .= 'our group ';
				}
				if (isset($must_joined['channel']) && !$must_joined['channel']) {
					if (!$must_joined['group']) {
						$text .= 'and ';
					}
					$text .= 'our channel';
				}
				$text .= '!' . PHP_EOL . PHP_EOL . 'Please, try again and tap "Check Me" button...';
			}
		}
		
		$data = [
			'chat_id' => $chat_id,
			'text' => $text
		];
		
		if (isset($keyboard)) {
			$data['reply_markup'] = $keyboard;
		}
		
		return Request::sendMessage($data);
	}
}