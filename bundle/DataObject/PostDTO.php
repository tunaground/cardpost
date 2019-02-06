<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Bundle\Component\Content;

class PostDTO
{
    private $postUID;
    private $cardUID;
    private $bbsUID;
    private $order;
    private $name;
    private $userID;
    /** @var \DateTime */
    private $createDate;
    /** @var Content */
    private $content;
    private $image;
    private $ip;
    private $status;

    public function getPostUID(): int
    {
        return $this->postUID;
    }

    public function setPostUID(int $postUID)
    {
        $this->postUID = $postUID;
    }

    public function getCardUID(): int
    {
        return $this->cardUID;
    }

    public function setCardUID(int $cardUID)
    {
        $this->cardUID = $cardUID;
    }

    public function getBbsUID(): string
    {
        return $this->bbsUID;
    }

    public function setBbsUID(string $bbsUID)
    {
        $this->bbsUID = $bbsUID;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order)
    {
        $this->order = $order;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getUserID(): string
    {
        return $this->userID;
    }

    public function setUserID(string $userID)
    {
        $this->userID = $userID;
    }

    public function getCreateDate(): ?\DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function setContent(Content $content)
    {
        $this->content = $content;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $image = null)
    {
        $this->image = $image;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}
