<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\MVC\AbstractComponent;

class Card extends AbstractComponent
{
    protected static $templateName = 'cardSection';
    /** @var int */
    protected $order;
    /** @var CardDTO */
    private $cardDTO;
    /** @var Post[] */
    private $postList;
    /** @var PostForm */
    private $postForm;
    /** @var \DateTimeZone */
    private $timezone;
    /** @var string */
    private $dateFormat;

    public function setOrder(int $order)
    {
        $this->order = $order;
    }

    public function setCardDTO(CardDTO $cardDTO)
    {
        $this->cardDTO = $cardDTO;
    }

    public function setPostList(array $postList)
    {
        $this->postList = $postList;
    }

    public function setPostForm(PostForm $postForm)
    {
        $this->postForm = $postForm;
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setTimezone(\DateTimeZone $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    public function __toString()
    {
        return $this->parser->parse($this->template, [
            'order' => $this->order,
            'bbsUID' => $this->cardDTO->getBbsUID(),
            'cardUID' => $this->cardDTO->getCardUID(),
            'owner' => $this->cardDTO->getOwner(),
            'title' => $this->cardDTO->getTitle(),
            'size' => $this->cardDTO->getSize() - 1,
            'openTime' => $this->cardDTO->getOpenDate()
                ->setTimezone($this->timezone)
                ->format($this->dateFormat),
            'refreshTime' => $this->cardDTO->getRefreshDate()
                ->setTimezone($this->timezone)
                ->format($this->dateFormat),
            'postList' => array_reduce($this->postList, function (string $carry, Post $post) {
                return $carry . $post->__toString();
            }, ''),
            'postForm' => $this->postForm->__toString()
        ]);
    }
}
