<?php
namespace Tunacan\Bundle\DataObject;

class CardDto
{
    private $cardUid;
    private $bbsUid;
    private $title;
    private $password;
    /** @var \DateTime */
    private $openDate;
    /** @var \DateTime */
    private $refreshDate;
    private $dead;
    private $ownerOnly;
    private $status;
    private $owner;
    private $size;

    public function getCardUid(): int
    {
        return $this->cardUid;
    }

    public function setCardUid(int $cardUid): void
    {
        $this->cardUid = $cardUid;
    }

    public function getBbsUid(): string
    {
        return $this->bbsUid;
    }

    public function setBbsUid(string $bbsUid): void
    {
        $this->bbsUid = $bbsUid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getOpenDate(): \DateTime
    {
        return $this->openDate;
    }

    public function setOpenDate(\DateTime $openDate): void
    {
        $this->openDate = $openDate;
    }

    public function getRefreshDate(): \DateTime
    {
        return $this->refreshDate;
    }

    public function setRefreshDate(\DateTime $refreshDate): void
    {
        $this->refreshDate = $refreshDate;
    }

    public function isDead(): bool
    {
        return $this->dead;
    }

    public function setDead(bool $dead): void
    {
        $this->dead = $dead;
    }

    public function isOwnerOnly(): bool
    {
        return $this->ownerOnly;
    }

    public function setOwnerOnly(bool $ownerOnly): void
    {
        $this->ownerOnly = $ownerOnly;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}
