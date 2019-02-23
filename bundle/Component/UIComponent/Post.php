<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\MVC\AbstractComponent;

class Post extends AbstractComponent
{
    protected static $templateName = 'post';
    /** @var PostDTO */
    private $postDTO;
    /** @var \DateTimeZone */
    private $timezone;
    /** @var string */
    private $dateFormat;
    /** @var string */
    private $imageDomain;

    public function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setImageDomain(string $imageDomain)
    {
        $this->imageDomain = $imageDomain;
    }

    public function setPostDTO(PostDTO $postDTO)
    {
        $this->postDTO = $postDTO;
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setTimezone(\DateTimeZone $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function __toString()
    {
        if ($this->postDTO->getStatus() == 1) {
            return $this->parser->parse($this->template, [
                'postUID' => $this->postDTO->getPostUID(),
                'order' => $this->postDTO->getOrder(),
                'name' => $this->postDTO->getName(),
                'userID' => $this->postDTO->getUserID(),
                'time' => $this->postDTO->getCreateDate()
                    ->setTimezone($this->timezone)
                    ->format($this->dateFormat),
                'content' => $this->postDTO->getContent()
                    ->applyAnchor($this->postDTO->getBbsUID(), $this->postDTO->getCardUID())
                    ->__toString(),
                'image' => $this->getImageWithTag()
            ]);
        } else {
            return '';
        }
    }

    private function getImageWithTag()
    {
        $imageEncodedSrc = $this->imageDomain."/".rawurlencode($this->postDTO->getImage());
        $imageSrc = $this->imageDomain."/".$this->postDTO->getImage();
        $noImageSrc = $this->imageDomain."/no-image.png";
        if ($this->postDTO->getImage()) {
            if ((@getimagesize($imageSrc) !== false)) {
                return "<a href='{$imageSrc}'><img class='thumbnail' src='{$imageSrc}'/></a>";
            } elseif ((@getimagesize($imageEncodedSrc) !== false)) {
                return "<a href='{$imageEncodedSrc}'><img class='thumbnail' src='{$imageEncodedSrc}'/></a>";
            } else {
                return "<img class='thumbnail' src='{$noImageSrc}'/>";
            }
        } else {
            return '';
        }
    }
}
