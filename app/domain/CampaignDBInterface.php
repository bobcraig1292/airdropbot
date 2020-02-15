<?php


namespace app\domain;


use Longman\TelegramBot\Exception\TelegramException;

/**
 * Interface CampaignDBInterface
 *
 * @package app\domain
 */
interface CampaignDBInterface
{
	/**
	 * Get class instance
	 *
	 * @return CampaignDBInterface
	 */
	public static function getInstance(): self;
	
	/**
	 * Fetch campaign(s) from DB
	 *
	 * @param string $user_id    Check for unique campaign id
	 * @param int $limit Limit the number of campaigns to fetch
	 *
	 * @return array|bool Fetched data or false if not connected
	 */
	public static function selectCampaign($user_id = null, $limit = null);
	
	/**
	 * Fetch campaign from DB by given referral link
	 *
	 * @param string $link    Check for unique campaign id
	 *
	 * @return array|bool Fetched data or false if not connected
	 */
	public static function selectCampaignByReferralLink(string $link);
	
	/**
	 * Insert new user's campaign to Database
	 *
	 * @param $campaign Campaign
	 *
	 * @return bool
	 * @throws TelegramException
	 */
	public static function insertCampaign(Campaign $campaign): bool;
	
	/**
	 * Updates user's campaign in Database
	 *
	 * @param Campaign $campaign
	 *
	 * @return bool
	 * @throws TelegramException
	 */
	public static function updateCampaign(Campaign $campaign): bool;
}