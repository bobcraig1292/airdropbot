<?php


namespace app\core;


use app\domain\Campaign;
use app\domain\CampaignDBInterface;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;
use PDOException;

class CatBotDB extends DB implements CampaignDBInterface
{
	public static function getInstance(): CampaignDBInterface
	{
		return new self();
	}
	
	/**
	 * @inheritDoc
	 * @throws TelegramException
	 */
	public static function selectCampaign($user_id = null, $limit = null)
	{
		if (!self::isDbConnected()) {
			return false;
		}
		
		try {
			$sql = '
                SELECT *
                FROM `campaign`
            ';
			
			if ($user_id !== null) {
				$sql .= ' WHERE `user_id` = :user_id';
			} else {
				$sql .= ' ORDER BY `id` DESC';
			}
			
			if ($limit !== null) {
				$sql .= ' LIMIT :limit';
			}
			
			$sth = self::$pdo->prepare($sql);
			
			if ($limit !== null) {
				$sth->bindValue(':limit', $limit, PDO::PARAM_INT);
			}
			if ($user_id !== null) {
				$sth->bindValue(':user_id', $user_id);
			}
			
			$sth->execute();
			
			return $sth->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new TelegramException($e->getMessage());
		}
	}
	
	/**
	 * @inheritDoc
	 * @throws TelegramException
	 */
	public static function selectCampaignByReferralLink(string $link){
		if (!self::isDbConnected()) {
			return false;
		}
		
		try {
			$sql = 'SELECT * FROM `campaign` WHERE `ref_link` = :ref_link';
			
			$sth = self::$pdo->prepare($sql);
			
			$sth->bindValue(':ref_link', $link, PDO::PARAM_STR);

			$sth->execute();
			
			return $sth->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new TelegramException($e->getMessage());
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public static function insertCampaign(Campaign $campaign): bool
	{
		if ($campaign->getUserId() === null) {
			throw new TelegramException('$user_id is null. Can not create campaign');
		}
		
		if (!self::isDbConnected()) {
			return false;
		}
		
		try {
			$sth = self::$pdo->prepare('
                INSERT IGNORE INTO `campaign`
                (`user_id`,
                 `is_follower`,
                 `twitter_link`,
                 `has_retweet`,
                 `ethereum_address`,
                 `ref_link`,
                 `partners_count`,
                 `tokens_earned_count`,
                 `created_at`,
                 `updated_at`)
                VALUES
                (:user_id,
                 :is_follower,
                 :twitter_link,
                 :has_retweet,
                 :ethereum_address,
                 :ref_link,
                 :partners_count,
                 :tokens_earned_count,
                 :created_at,
                 :updated_at)
            ');
			
			$sth->bindValue(':user_id', $campaign->getUserId());
			$sth->bindValue(':is_follower', $campaign->getIsFollower());
			$sth->bindValue(':twitter_link', $campaign->getTwitterLink());
			$sth->bindValue(':has_retweet', $campaign->getHasRetweet());
			$sth->bindValue(':ethereum_address', $campaign->getEthereumAddress());
			$sth->bindValue(':ref_link', $campaign->getRefLink());
			$sth->bindValue(':partners_count', $campaign->getPartnersCount());
			$sth->bindValue(':tokens_earned_count', $campaign->getTokensEarnedCount());
			$sth->bindValue(':created_at', self::getTimestamp());
			$sth->bindValue(':updated_at', self::getTimestamp());
			
			return $sth->execute();
		} catch (PDOException $e) {
			throw new TelegramException($e->getMessage());
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public static function updateCampaign(Campaign $campaign):bool
	{
		
		if (!self::isDbConnected()) {
			return false;
		}
		
		try {
			$sth = self::$pdo->prepare('
                UPDATE `campaign` SET
					`is_follower` = :is_follower,
					`twitter_link` = :twitter_link,
					`has_retweet` = :has_retweet,
					`ethereum_address` = :ethereum_address,
                    `ref_link` = :ref_link,
					`partners_count` = :partners_count,
					`tokens_earned_count` = :tokens_earned_count,
		            `updated_at` = :updated_at,
                    `finished_at` = :finished_at
				WHERE `id` = :id;');
			
			$sth->bindValue(':is_follower', $campaign->getIsFollower());
			$sth->bindValue(':twitter_link', $campaign->getTwitterLink());
			$sth->bindValue(':has_retweet', $campaign->getHasRetweet());
			$sth->bindValue(':ethereum_address', $campaign->getEthereumAddress());
			$sth->bindValue(':ref_link', $campaign->getRefLink());
			$sth->bindValue(':partners_count', $campaign->getPartnersCount());
			$sth->bindValue(':tokens_earned_count', $campaign->getTokensEarnedCount());
			$sth->bindValue(':updated_at', self::getTimestamp());
			$sth->bindValue(':finished_at', $campaign->getFinishedAt());
			$sth->bindValue(':id', $campaign->getId());
			
			return $sth->execute();
		} catch (PDOException $e) {
			throw new TelegramException($e->getMessage());
		}
	}
}