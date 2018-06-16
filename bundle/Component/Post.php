<?php
 
namespace Tunacan\Bundle\Component;

use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\MVC\AbstractComponent;

class Post extends AbstractComponent
{
    protected $htmlTemplateName = 'post';
    /**
     * @Inject("date.format.common")
     * @var string
     */
    private $dateFormat;
    /** @var PostDto */
    private $postDto;

    public function setPostDto(PostDto $postDto)
    {
        $this->postDto = $postDto;
    }

    public function __toString()
    {
        return $this->parser->parse($this->htmlTemplate, [
            'postUid' => $this->postDto->getPostUid(),
            'order' => $this->postDto->getOrder(),
            'name' => $this->postDto->getName(),
            'userId' => $this->postDto->getUserId(),
            'time' => $this->postDto->getCreatedate()->format($this->dateFormat),
            'content' => $this->postDto->getContent()->__toString(),
            'image' => $this->getImageWithTag()
        ]);
    }

    private function getImageWithTag()
    {
        $imageSrc = "http://public.tunaground.net/{$this->postDto->getImage()}";
        return ($this->postDto->getImage())
            ? "<a href='{$imageSrc}'><img class='thumbnail' src='{$imageSrc}'/></a>"
            : '';
    }
}
