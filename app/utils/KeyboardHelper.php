<?php


namespace app\utils;

use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;

class KeyboardHelper
{
	/**
	 * Return "follow to" links set keyboard array
	 *
	 * $group_url and $channel_url MUST BE valid http(s) protocol links.
	 * Telegram API will return 400 Bad Request response code if not.
	 *
	 * @param $group_url string
	 * @param $channel_url string|null
	 *
	 * @return InlineKeyboard
	 */
	public static function getJoinToKeyboard(string $group_url, string $channel_url = null)
	{
		$keyboard_array = [
			['text' => 'Join our group', 'url' => $group_url],
		];
		if (!empty($channel_url)){
			$keyboard_array[] = ['text' => 'Join our channel', 'url' => $channel_url];
		}
		
		$keyboard = new InlineKeyboard($keyboard_array);
		$keyboard->setResizeKeyboard(true);
		
		return $keyboard;
	}
	
	/**
	 * Return "Check me" keyboard
	 *
	 * @return Keyboard
	 */
	public static function getCheckMeKeyboard()
	{
		$keyboard = new Keyboard([
			['text' => 'Check me']
		]);
		$keyboard->setResizeKeyboard(true);
		$keyboard->setOneTimeKeyboard(true);
		
		return $keyboard;
	}
	
	/**
	 * Return "Start campaign" keyboard
	 *
	 * @return Keyboard
	 */
	public static function getStartCampaignKeyboard()
	{
		$keyboard = new Keyboard([
			['text' => 'Start campaign 🚀']
		]);
		$keyboard->setResizeKeyboard(true);
		$keyboard->setOneTimeKeyboard(true);
		
		return $keyboard;
	}
	
	/**
	 * Return "Retweet last tweet" keyboard
	 *
	 * $twitter_profile_url MUST BE valid http(s) protocol links.
	 * Telegram API will return 400 Bad Request response code if not.
	 *
	 * @param string $link_text
	 * @param string $twitter_profile_url
	 *
	 * @return Keyboard
	 */
	public static function getRetweetKeyboard(string $link_text, string $twitter_profile_url)
	{
		$keyboard = new InlineKeyboard([
			[ 'text' => $link_text, 'url' => $twitter_profile_url]
		]);
		$keyboard->setResizeKeyboard(true);
		
		return $keyboard;
	}
	
	/**
	 * Return "Main menu" keyboard
	 *
	 * @return Keyboard
	 */
	public static function getMainMenuKeyboard()
	{
		$keyboard = new Keyboard(
			[
				['text' => 'Balance 💰'],
				['text' => 'Referral link 🔗'],
			],
			[
				['text' => 'Support ☎'],
				['text' => 'Social media 👥']
			]
		);
		$keyboard->setResizeKeyboard(true);
		
		return $keyboard;
	}
	
	/**
	 * Remove keyboard from chat
	 *
	 * @return Keyboard
	 */
	public static function getEmptyKeyboard()
	{
		return Keyboard::remove();
	}


}