<?php


namespace app\utils;


class ErrorMessagesHelper
{
	/**
	 * Return common error message text.
	 * E.g. bot can't find any command user wants to execute, or do not wait for any data input.
	 *
	 * @return string
	 */
	public static function getCommonErrorText()
	{
		$text = 'I don\'t understand what are you want for me!';
		$text .= PHP_EOL . PHP_EOL;
		$text .= 'Start our campaign by by pressing "Start campaign" first if you still did not ';
		$text .= 'or just follow previous instructions carefully';
		
		return $text;
	}
	
	/**
	 * Return common error message text.
	 * E.g. bot can't find any command user wants to execute, or do not wait for any data input.
	 *
	 * @return string
	 */
	public static function getCampaignCompleteErrorText()
	{
		$text = 'I don\'t understand what are you want for me! 😿';
		$text .= PHP_EOL . PHP_EOL;
		$text .= 'Please, choose any command from menu';
		
		return $text;
	}
	
	
	/**
	 * Return wrong twitter link error message text.
	 * E.g. bot can't parse any twitter link on retweet waiting stage of campaign.
	 *
	 * @return string
	 */
	public static function getWrongRetweetLinkErrorText()
	{
		$text = 'It doesn\'t looks like your retweet. Please try again.';
		
		return $text;
	}
	
	/**
	 * Return wrong twitter Ethereum wallet error message text.
	 * E.g. bot can't parse any Ethereum wallet on Ethereum wallet waiting stage of campaign.
	 *
	 * @return string
	 */
	public static function getWrongWalletErrorText()
	{
		$text = 'It doesn\'t look like Ethereum address!';
		$text .= PHP_EOL . PHP_EOL;
		$text .= 'Address needs to begin with 0x and needs to be ERC 20 Compatible';
		
		return $text;
	}
	
}