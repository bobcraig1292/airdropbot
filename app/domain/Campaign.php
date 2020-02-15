<?php


namespace app\domain;


/**
 * Class Campaign
 *
 * @package app\domain
 */
class Campaign
{
	/**
	 * @var $id integer
	 */
	protected $id;
	/**
	 * @var $user_id integer
	 */
	protected $user_id;
	/**
	 * @var $is_follower integer
	 */
	protected $is_follower;
	/**
	 * @var $twitter_link string|null
	 */
	protected $twitter_link;
	/**
	 * @var $has_retweet integer
	 */
	protected $has_retweet;
	/**
	 * @var $ethereum_address string|null
	 */
	protected $ethereum_address;
	/**
	 * @var $ref_link string|null
	 */
	protected $ref_link;
	/**
	 * @var $partners_count integer
	 */
	protected $partners_count;
	/**
	 * @var $tokens_earned_count integer
	 */
	protected $tokens_earned_count;
	/**
	 * @var $created_at string|null
	 */
	protected $created_at;
	/**
	 * @var $updated_at string|null
	 */
	protected $updated_at;
	/**
	 * @var $finished_at string|null
	 */
	protected $finished_at;
	
	/**
	 * Campaign constructor.
	 *
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->assignMemberVariables($data);
	}
	
	/**
	 * Helper to set member variables
	 *
	 * @param array $data
	 */
	protected function assignMemberVariables(array $data)
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}
	
	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = intval($id);
	}
	
	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}
	
	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id): void
	{
		$this->user_id = $user_id;
	}
	
	/**
	 * @return int
	 */
	public function getIsFollower(): int
	{
		return $this->is_follower;
	}
	
	/**
	 * @param int $is_follower
	 */
	public function setIsFollower(int $is_follower): void
	{
		$this->is_follower = $is_follower;
	}
	
	/**
	 * @return string|null
	 */
	public function getTwitterLink(): ?string
	{
		return $this->twitter_link;
	}
	
	/**
	 * @param string|null $twitter_link
	 */
	public function setTwitterLink(?string $twitter_link): void
	{
		$this->twitter_link = $twitter_link;
	}
	
	/**
	 * @return int
	 */
	public function getHasRetweet(): int
	{
		return $this->has_retweet;
	}
	
	/**
	 * @param int $has_retweet
	 */
	public function setHasRetweet(int $has_retweet): void
	{
		$this->has_retweet = $has_retweet;
	}
	
	/**
	 * @return string|null
	 */
	public function getEthereumAddress(): ?string
	{
		return $this->ethereum_address;
	}
	
	/**
	 * @param string|null $ethereum_address
	 */
	public function setEthereumAddress(?string $ethereum_address): void
	{
		$this->ethereum_address = $ethereum_address;
	}
	
	/**
	 * @return string|null
	 */
	public function getRefLink(): ?string
	{
		return $this->ref_link;
	}
	
	/**
	 * @param string|null $ref_link
	 */
	public function setRefLink(?string $ref_link): void
	{
		$this->ref_link = $ref_link;
	}
	
	/**
	 * @return string|null
	 */
	public function getPartnersCount(): ?string
	{
		return $this->partners_count;
	}
	
	/**
	 * @param int $partners_count
	 */
	public function setPartnersCount(int $partners_count): void
	{
		$this->partners_count = $partners_count;
	}

	
	/**
	 * @return int
	 */
	public function getTokensEarnedCount(): int
	{
		return $this->tokens_earned_count;
	}
	
	/**
	 * @param int $tokens_earned_count
	 */
	public function setTokensEarnedCount(int $tokens_earned_count): void
	{
		$this->tokens_earned_count = $tokens_earned_count;
	}
	
	/**
	 * @return string|null
	 */
	public function getCreatedAt(): ?string
	{
		return $this->created_at;
	}
	
	/**
	 * @param string|null $created_at
	 */
	public function setCreatedAt(?string $created_at): void
	{
		$this->created_at = $created_at;
	}
	
	/**
	 * @return string|null
	 */
	public function getUpdatedAt(): ?string
	{
		return $this->updated_at;
	}
	
	/**
	 * @param string|null $updated_at
	 */
	public function setUpdatedAt(?string $updated_at): void
	{
		$this->updated_at = $updated_at;
	}
	
	/**
	 * @return string|null
	 */
	public function getFinishedAt(): ?string
	{
		return $this->finished_at;
	}
	
	/**
	 * @param string|null $finished_at
	 */
	public function setFinishedAt(?string $finished_at): void
	{
		$this->finished_at = $finished_at;
	}
	
	
}