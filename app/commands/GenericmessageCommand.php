<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use app\core\CatBot;
use app\domain\CampaignHelper;
use app\utils\CommunityHelper;
use app\utils\ErrorMessagesHelper;
use app\utils\KeyboardHelper;
use app\utils\TextHelper;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{
	/**
	 * @var string
	 */
	protected $name = 'genericmessage';
	
	/**
	 * @var string
	 */
	protected $description = 'Handle any generic message';
	
	private function getCommandAliasFromText($message_text){
		$commands_aliases = CatBot::app()->config->get('commands_aliases');
		if (is_array($commands_aliases) && !empty($commands_aliases)){
			foreach ($commands_aliases as $command => $commands_alias){
				$regexp = "/^$commands_alias/";
				if (CampaignHelper::getFragmentFromTextByRegexp($message_text, $regexp)){
					return $command;
				}
			}
		}
		return false;
	}
	
	/**
	 * Command execute method
	 *
	 * @return ServerResponse
	 * @throws TelegramException
	 */
	public function execute()
	{
		$message = $this->getMessage();
		$message_id = $message->getMessageId();
		$chat_id = $message->getChat()->getId();
		$user_id = $message->getFrom()->getId();
		$message_text = trim($message->getText(true));
		$keyboard = KeyboardHelper::getEmptyKeyboard();
		
		
		// Try to parse any text command alias from text
		if (CatBot::app()->config->get('use_commands_aliases')){
			
			$text_command = $this->getCommandAliasFromText($message_text);
			
			if (!empty($text_command)){
				$text_command = preg_replace('/\//', '', $text_command);
				return $this->getTelegram()->executeCommand($text_command);
			}
		}
		
		if ($message->getChat()->getType() == 'private'){
			// This part of command never be executed in group chats
			
			$user_campaign = CatBot::app()->campaignService->getActiveUserCampaign($user_id);
			
			Request::sendChatAction([
				'chat_id' => $chat_id,
				'action'  => ChatAction::TYPING,
			]);
			
			// Only if user is our follower and already have started campaign
			if (!empty($user_campaign) && $user_campaign->getIsFollower()){
				
				// Try to parse requested input from user
				$any_link = CampaignHelper::getTwitterLinkFromText($message_text);
				$any_wallet = CampaignHelper::getEthereumAddressFromText($message_text);
				
				if (empty($user_campaign->getHasRetweet())){
					if (!empty($any_link)){
						// If user input is a twitter link
						$text = 'Looks like Twitter link. Thank you for retweet.'. PHP_EOL . PHP_EOL;
						
						$user_campaign->setHasRetweet(1);
						$user_campaign->setTwitterLink($any_link);
						
						if (CatBot::app()->campaignService->updateCampaign($user_campaign)){
							$text .= 'Now we need to get your Ethereum address (Address needs to begin with 0x and needs to be ERC 20 Compatible)';
						}
						
						return Request::sendMessage([
							'chat_id' => $chat_id,
							'text'    => $text,
							'reply_markup' => $keyboard
						]);
					}
					else {
						return Request::sendMessage([
							'chat_id' => $chat_id,
							'text'    => ErrorMessagesHelper::getWrongRetweetLinkErrorText(),
							'reply_markup' => $keyboard
						]);
					}
				}
				else{
					if (empty($user_campaign->getEthereumAddress())){
						if (!empty($any_wallet)){
							$text = 'Looks like ethereum wallet address.'. PHP_EOL . PHP_EOL;
							
							$user_campaign->setEthereumAddress($any_wallet);
							$user_campaign->setTokensEarnedCount(CatBot::app()->config->get('campaign_complete_reward_tokens_count'));
							$user_campaign->setRefLink(CampaignHelper::getUniqueReferralLink(CatBot::app()->config->get('bot_username')));
							
							if (CatBot::app()->campaignService->updateCampaign($user_campaign)){
								$text .= 'Thanks! Your details have been submitted successfully.';
								$text .=  PHP_EOL . PHP_EOL;
								$text .= 'Congratulations, you have earned ';
								$text .=  CatBot::app()->config->get('campaign_complete_reward_tokens_count') . ' ' .  CatBot::app()->config->get('token_name');
								$text .= ' tokens! ';
								$text .= 'The following details have been logged:';
								$text .=  PHP_EOL . PHP_EOL;
								$text .= 'Address - ' . $user_campaign->getEthereumAddress();
								$text .=  PHP_EOL;
								$text .= 'Retweet - ' . $user_campaign->getTwitterLink();
								$text .=  PHP_EOL . PHP_EOL;
								$text .= 'Your unique referral link is: ' . $user_campaign->getRefLink();
								$text .=  PHP_EOL . PHP_EOL;
								$text .= 'Share and forward the referral link to your network and get ';
								$text .= CatBot::app()->config->get('referrer_invite_reward_tokens_count') . ' ' .  CatBot::app()->config->get('token_name');
								$text .= ' tokens for each friend invited!';
								$text .= 'They will have to join our chat and stay until the end of the Bounty campaign you to receive the reward!';
								$text .= 'Users who get caught cheating will be disqualified.';
								
								return Request::sendMessage([
									'chat_id' => $chat_id,
									'text'    => $text,
									'reply_markup' => KeyboardHelper::getMainMenuKeyboard()
								]);
							}
						}
						else {
							return Request::sendMessage([
								'chat_id' => $chat_id,
								'text'    => ErrorMessagesHelper::getWrongWalletErrorText(),
								'reply_markup' => $keyboard
							]);
						}
					}
					else{
						return Request::sendMessage([
							'chat_id' => $chat_id,
							'text'    => ErrorMessagesHelper::getCampaignCompleteErrorText(),
							'reply_markup' => KeyboardHelper::getMainMenuKeyboard()
						]);
					}
				}
			}
			else {
				// User is not our follower or have no started campaign
				return Request::sendMessage([
					'chat_id' => $chat_id,
					'text'    => ErrorMessagesHelper::getCommonErrorText(),
					'reply_markup' => KeyboardHelper::getStartCampaignKeyboard()
				]);
			}
			
			$data = [
				'chat_id' => $chat_id,
				'text'    => $text,
				'reply_markup' => $keyboard
			];
			
			return Request::sendMessage($data);
		} else {
			// This part of command will executed only for  bot's "group to follow" from config
			if ($chat_id == CatBot::app()->config->get('telegram_group_to_follow_id')
				&& !in_array($user_id, CatBot::app()->config->get('bot_admins'))
				&& !CommunityHelper::checkUserIsAdminOfChat($user_id, $chat_id)
			){
				// spam reaction
				$spam_reaction_config = CatBot::app()->config->get('spam_types');
				
				if (CatBot::app()->config->get('spam_reaction') && count($spam_reaction_config)) {
					
					$spam_detection = [];
					
					foreach ($spam_reaction_config as $option_name => $option_value) {
						switch ($option_name) {
							case 'links':
								if (TextHelper::isAnyUrlFoundInText($message_text)) {
									$spam_detection[$option_name] = true;
								}
								break;
							case 'user_nicknames':
								if (TextHelper::isAnyNicknameInText($message_text)) {
									$spam_detection[$option_name] = true;
								}
								break;
							case 'photo':
								if ($message->getPhoto()) {
									$spam_detection[$option_name] = true;
								}
								break;
							case 'stickers':
								if ($message->getSticker()) {
									$spam_detection[$option_name] = true;
								}
								break;
							case 'forwards':
								if ($message->getForwardFrom()) {
									$spam_detection[$option_name] = true;
								}
								break;
							case 'stop_words':
								if (TextHelper::findMatchesInText($option_value, $message_text)) {
									$spam_detection[$option_name] = true;
								}
								break;
						}
					}
					
					if (count(array_filter($spam_detection))){
						return Request::deleteMessage(compact('message_id', 'chat_id'));
					}
				}
				
				// keywords replies section
				$stop_words_vocabulary = CatBot::app()->config->get('keywords_vocabulary');
				
				if (CatBot::app()->config->get('keywords_reaction') && count($stop_words_vocabulary)){
					
					$matches = TextHelper::findMatchesInText(array_keys($stop_words_vocabulary), $message_text);
					
					if ($matches){
						foreach (array_keys($matches) as $stop_word){
							Request::sendMessage([
								'chat_id' => $chat_id,
								'text'    => $stop_words_vocabulary[$stop_word],
								'reply_to_message_id' => $message->getMessageId()
							]);
						}
					}
				}
				
			} else{
				return Request::emptyResponse();
			}
		}
	}
}