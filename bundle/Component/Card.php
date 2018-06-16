<?php

namespace Tunacan\Bundle\Component;

use Tunacan\Bundle\DataObject\CardDto;
use Tunacan\MVC\AbstractComponent;

class Card extends AbstractComponent
{
    protected $htmlTemplateName = 'cardSection';
    /**
     * @Inject("date.format.common")
     * @var string
     */
    private $dateFormat;
    private $order;
    /** @var CardDto */
    private $cardDto;
    /** @var Post[] */
    private $postList;
    /** @var PostForm */
    private $postForm;

    public function setOrder(int $order)
    {
        $this->order = $order;
    }

    public function setCardDto(CardDto $cardDto)
    {
        $this->cardDto = $cardDto;
    }

    public function setPostList(array $postList)
    {
        $this->postList = $postList;
    }

    public function setPostForm(PostForm $postForm)
    {
        $this->postForm = $postForm;
    }

    public function __toString()
    {
        return $this->parser->parse($this->htmlTemplate, [
            'order' => $this->order,
            'cardUid' => $this->cardDto->getCardUid(),
            'owner' => $this->cardDto->getOwner(),
            'title' => $this->cardDto->getTitle(),
            'size' => $this->cardDto->getSize() - 1,
            'openTime' => $this->cardDto->getOpenDate()->format($this->dateFormat),
            'refreshTime' => $this->cardDto->getRefreshDate()->format($this->dateFormat),
            'postList' => array_reduce($this->postList, function (string $carry, Post $post) {
                return $carry . $post->__toString();
            }, ''),
            'postForm' => $this->postForm->__toString()
        ]);
    }
}
