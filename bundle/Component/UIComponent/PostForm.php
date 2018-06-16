<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class PostForm extends AbstractComponent
{
    protected $htmlTemplateName = 'postForm';
    private $bbsUid;
    private $cardUid;

    public function getObject()
    {
        $postForm = new PostForm($this->loader, $this->parser);
        return $postForm;
    }

    public function setBbsUid(string $bbsUid): void
    {
        $this->bbsUid = $bbsUid;
    }

    public function setCardUid(string $cardUid): void
    {
        $this->cardUid = $cardUid;
    }

    public function __toString()
    {
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'bbsUid' => $this->bbsUid,
            'cardUid' => $this->cardUid
        ]);
    }
}