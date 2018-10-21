<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\MVC\AbstractComponent;

class CardListNode extends AbstractComponent
{
    protected $htmlTemplateName = 'cardListNode';
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
    /** @var string */
    private $bbsUid;
    /** @var int */
    private $cardUid;
    /** @var int */
    private $order;
    /** @var string */
    private $title;
    /** @var int */
    private $size;
    /** @var string */
    private $owner;
    /** @var \DateTime */
    private $refreshDate;

    public function getObject()
    {
        $obj = new CardListNode($this->loader, $this->parser);
        $obj->setDateFormat($this->dateFormat);
        $obj->setDateTimeBuilder($this->dateTimeBuilder);
        return $obj;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param DateTimeBuilder $dateTimeBuilder
     */
    public function setDateTimeBuilder(DateTimeBuilder $dateTimeBuilder): void
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    /**
     * @param string $bbsUid
     */
    public function setBbsUid(string $bbsUid): void
    {
        $this->bbsUid = $bbsUid;
    }

    /**
     * @param int $cardUid
     */
    public function setCardUid(int $cardUid): void
    {
        $this->cardUid = $cardUid;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @param \DateTime $refreshDate
     */
    public function setRefreshDate(\DateTime $refreshDate): void
    {
        $this->refreshDate = $refreshDate;
    }

    public function __toString()
    {
        // TODO: link 기준 하드링크 수정해야함
        $linkCriteria = 10;
        if ($this->order <= $linkCriteria) {
            $orderLink = "/trace/{$this->bbsUid}/{$this->cardUid}";
            $sizeLink = "{$orderLink}/recent";
            $titleLink = "#{$this->order}";
        } else {
            $orderLink = "";
            $sizeLink = "/trace/{$this->bbsUid}/{$this->cardUid}";
            $titleLink = "{$orderLink}/recent";
        }
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'order_link' => $orderLink,
            'size_link' => $sizeLink,
            'title_link' => $titleLink,
            'order' => $this->order,
            'title' => $this->title,
            'size' => $this->size,
            'owner' => $this->owner,
            'refresh_date' => $this->refreshDate
                ->setTimezone($this->dateTimeBuilder->getUserTimezone())
                ->format($this->dateFormat)
        ]);
    }
}