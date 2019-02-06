<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\MVC\AbstractComponent;

class Card extends AbstractComponent
{
    protected $htmlTemplateName = 'cardSection';
    /**
     * @Inject("date.format.common")
     * @var string
     */
    private $dateFormat;
    /**
     * @Inject
     * @var DateTimeBuilder
     */
    private $dateTimeBuilder;
    /** @var int */
    protected $order;
    /** @var CardDTO */
    private $cardDTO;
    /** @var Post[] */
    private $postList;
    /** @var PostForm */
    private $postForm;

    public function getObject()
    {
        $card = new Card($this->loader, $this->parser);
        $card->setDateFormat($this->dateFormat);
        $card->setDateTimeBuilder($this->dateTimeBuilder);
        return $card;
    }

    public function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setDateTimeBuilder(DateTimeBuilder $dateTimeBuilder) {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

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

    public function __toString()
    {
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'order' => $this->order,
            'bbsUID' => $this->cardDTO->getBbsUID(),
            'cardUID' => $this->cardDTO->getCardUID(),
            'owner' => $this->cardDTO->getOwner(),
            'title' => $this->cardDTO->getTitle(),
            'size' => $this->cardDTO->getSize() - 1,
            'openTime' => $this->cardDTO->getOpenDate()
                ->setTimezone($this->dateTimeBuilder->getUserTimezone())
                ->format($this->dateFormat),
            'refreshTime' => $this->cardDTO->getRefreshDate()
                ->setTimezone($this->dateTimeBuilder->getUserTimezone())
                ->format($this->dateFormat),
            'postList' => array_reduce($this->postList, function (string $carry, Post $post) {
                return $carry . $post->__toString();
            }, ''),
            'postForm' => $this->postForm->__toString()
        ]);
    }
}
