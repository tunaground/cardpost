<?php

namespace Tunacan\Bundle\DataObject;

class DenyDTO
{
    /** @var int */
    private $denyUID;
    /** @var int */
    private $cardUID;
    /** @var string */
    private $userUID;
    /** @var \DateTime */
    private $createDate;
    /** @var int */
    private $status;

    /**
     * @return int
     */
    public function getDenyUID(): int
    {
        return $this->denyUID;
    }

    /**
     * @param int $denyUID
     */
    public function setDenyUID(int $denyUID): void
    {
        $this->denyUID = $denyUID;
    }

    /**
     * @return int
     */
    public function getCardUID(): int
    {
        return $this->cardUID;
    }

    /**
     * @param int $cardUID
     */
    public function setCardUID(int $cardUID): void
    {
        $this->cardUID = $cardUID;
    }

    /**
     * @return string
     */
    public function getUserUID(): string
    {
        return $this->userUID;
    }

    /**
     * @param string $userUID
     */
    public function setUserUID(string $userUID): void
    {
        $this->userUID = $userUID;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate(\DateTime $createDate): void
    {
        $this->createDate = $createDate;
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
