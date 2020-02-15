<?php


namespace app\domain;

use \Exception;

class CampaignService
{
	protected $db;
	
	public function __construct(CampaignDBInterface $db)
	{
		$this->db = $db;
	}
	
	/**`
	 * Build new Campaign entity form assoc array
	 *
	 * @param $campaign_data array
	 *
	 * @return Campaign
	 */
	private function buildCampaign(array $campaign_data){
		return new Campaign($campaign_data);
	}
	
	/**
	 * Create new campaign for user
	 *
	 * @param $user_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function createNewUserCampaign($user_id)
	{
		$campaignData = [
			'user_id' => $user_id,
			'is_follower' => 0,
			'twitter_link' => null,
			'has_retweet' => 0,
			'ethereum_address' => null,
			'ref_link' => null,
			'partners_count' => 0,
			'tokens_earned_count' => 0,
		];
		
		$newUserCampaign = $this->buildCampaign($campaignData);
		
		if (!$this->isUserHaveAlreadyStartedCampaign($user_id)){
			return $this->db::insertCampaign($newUserCampaign);
		}
		return true;
	}
	
	/**
	 * Get all started campaigns count
	 *
	 * @return int
	 * @throws Exception
	 */
	public function getStartedCampaignsCount()
	{
		return count($this->db::selectCampaign());
	}
	
	/**
	 * Get all user's campaigns as array of objects
	 *
	 * @param $user_id
	 *
	 * @return array|false
	 * @throws Exception
	 */
	public function getUserCampaigns($user_id)
	{
		$campaigns_as_assoc = $this->db::selectCampaign($user_id);
		$campaigns_as_obj = [];
		if (!empty($campaigns_as_assoc)){
			foreach ($campaigns_as_assoc as $campaign_assoc){
				$campaigns_as_obj[] = $this->buildCampaign($campaign_assoc);
			}
		}
		return $campaigns_as_obj;
	}
	
	/**
	 * @param $user_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function isUserHaveAlreadyStartedCampaign($user_id)
	{
		$campaigns = $this->getUserCampaigns($user_id);
		if (is_array($campaigns)){
			return count($campaigns) > 0;
		}
		return false;
	}
	
	/**
	 * Get presently active user's campaign
	 *
	 * @param $user_id
	 *
	 * @return Campaign|false
	 * @throws Exception
	 */
	public function getActiveUserCampaign($user_id)
	{
		$campaigns = $this->getUserCampaigns($user_id);
		if (!empty($campaigns)){
			foreach ($campaigns as $campaign){
				/**
				 * @var $campaign Campaign
				 */
				if (empty($campaign->getFinishedAt())){
					return $campaign;
				}
			}
		}
		return false;
	}
	
	/**
	 * Update campaign data in database
	 *
	 * @param $campaign Campaign
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function updateCampaign(Campaign $campaign)
	{
		return $this->db::updateCampaign($campaign);
	}
	
	/**
	 * Send reward for link referrer campaign in database
	 *
	 * @param $link string
	 * @param $token_count int
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function sendRewardToReferrer(string $link, int $token_count){
		$referrerCampaign = $this->db::selectCampaignByReferralLink($link);
		if (!empty($referrerCampaign)){
			$referrerCampaign = $this->buildCampaign($referrerCampaign);
			$referrerCampaign->setTokensEarnedCount($referrerCampaign->getTokensEarnedCount() + $token_count);
			$referrerCampaign->setPartnersCount($referrerCampaign->getPartnersCount() + 1);
			return $this->updateCampaign($referrerCampaign);
		}
		return false;
	}
	
}