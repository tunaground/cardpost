<?php
namespace Tunacan\Bundle\DataObject;

class DenyDTO
{
    /** @var int */
    private $ban_uid;
    /** @var int */
    private $card_uid;
    /** @var string */
    private $user_id;
    /** @var \DateTime */
    private $create_date;
    /** @var int */
    private $status;

    /**
     * @return int
     */
    public function getBanUid(): int
    {
        return $this->ban_uid;
    }

    /**
     * @param int $ban_uid
     */
    public function setBanUid(int $ban_uid): void
    {
        $this->ban_uid = $ban_uid;
    }

    /**
     * @return int
     */
    public function getCardUid(): int
    {
        return $this->card_uid;
    }

    /**
     * @param int $card_uid
     */
    public function setCardUid(int $card_uid): void
    {
        $this->card_uid = $card_uid;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->create_date;
    }

    /**
     * @param \DateTime $create_date
     */
    public function setCreateDate(\DateTime $create_date): void
    {
        $this->create_date = $create_date;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
