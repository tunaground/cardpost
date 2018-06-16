<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Bundle\Component\Content;

class PostDto
{
    private $postUid;
    private $cardUid;
    private $bbsUid;
    private $order;
    private $name;
    private $userId;
    /** @var \DateTime */
    private $create_date;
    /** @var Content */
    private $content;
    private $image;
    private $ip;
    private $status;

    public function getPostUid(): int
    {
        return $this->postUid;
    }

    public function setPostUid(int $postUid)
    {
        $this->postUid = $postUid;
    }

    public function getCardUid(): int
    {
        return $this->cardUid;
    }

    public function setCardUid(int $cardUid)
    {
        $this->cardUid = $cardUid;
    }

    public function getBbsUid(): string
    {
        return $this->bbsUid;
    }

    public function setBbsUid(string $bbsUid)
    {
        $this->bbsUid = $bbsUid;
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

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    public function getCreateDate(): ?\DateTime
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTime $create_date)
    {
        $this->create_date = $create_date;
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
