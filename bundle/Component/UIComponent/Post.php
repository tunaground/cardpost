<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\MVC\AbstractComponent;

class Post extends AbstractComponent
{
    protected $htmlTemplateName = 'post';
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
    /** @var PostDto */
    private $postDto;

    public function getObject()
    {
        $post = new Post($this->loader, $this->parser);
        $post->setDateFormat($this->dateFormat);
        $post->setDateTimeBuilder($this->dateTimeBuilder);
        return $post;
    }

    public function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setDateTimeBuilder(DateTimeBuilder $dateTimeBuilder) {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    public function setPostDto(PostDto $postDto)
    {
        $this->postDto = $postDto;
    }

    public function __toString()
    {
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'postUid' => $this->postDto->getPostUid(),
            'order' => $this->postDto->getOrder(),
            'name' => $this->postDto->getName(),
            'userId' => $this->postDto->getUserId(),
            'time' => $this->postDto->getCreateDate()
                ->setTimezone($this->dateTimeBuilder->getUserTimezone())
                ->format($this->dateFormat),
            'content' => $this->postDto->getContent()
                ->applyAnchor($this->postDto->getBbsUid(), $this->postDto->getCardUid())
                ->__toString(),
            'image' => $this->getImageWithTag()
        ]);
    }

    private function getImageWithTag()
    {
        $imageSrc = "http://public.tunaground.net/{$this->postDto->getImage()}";
        $noImageSrc = "http://public.tunaground.net/system/no-image.png";
        if ($this->postDto->getImage()) {
            if ((@getimagesize($imageSrc) === false)) {
                return "<img class='thumbnail' src='{$noImageSrc}'/>";
            } else {
                return "<a href='{$imageSrc}'><img class='thumbnail' src='{$imageSrc}'/></a>";
            }
        } else {
            return '';
        }
    }
}
